<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Aws\Rekognition\RekognitionClient;
use Aws\Rekognition\RekognitionException;
use Illuminate\Support\Facades\Validator;

class RekognitionController extends Controller
{
    // detect faces only
    public function detectFaces(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120', // max 5MB
        ]);

        $path = $request->file('image')->getRealPath();
        $bytes = file_get_contents($path);

        try {
            $rek = app(\App\Services\RekognitionService::class);
            $faces = $rek->detectFacesFromBytes($bytes);
            return response()->json($faces);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Rekognition error: ' . $e->getMessage(),
            ], 500);
        }
    }

    // upload an image for a visitor and queue indexing
    public function uploadAndIndex(Request $request)
    {
        $validated = Validator::make($request->all(), ([
            'image' => 'required|image|max:5120',
            'visitor_id' => 'required|integer|exists:visitors,id',
        ]));

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()], 422);
        }
        
        
        $visitor = \App\Models\Visitor::find($request->visitor_id);

        if (! $visitor) {
            return response()->json(['message' => 'Visitor not found'], 404);
        }

        $path = $request->file('image')->store('public/visitors');
        $visitor->image_path = $path;
        $visitor->save();

        // perform indexing immediately and return result
        try {
            $localPath = \Illuminate\Support\Facades\Storage::path($path);
            $bytes = file_get_contents($localPath);
            $rek = app(\App\Services\RekognitionService::class);

            $faceId = $rek->detectAndIndexFromBytes($bytes, 'visitor_' . $visitor->id);

            if ($faceId) {
                $visitor->face_id = $faceId;
                $visitor->save();
                return response()->json(['status' => 'indexed', 'face_id' => $faceId, 'image_path' => $path], 200);
            }

            return response()->json(['status' => 'no_face_detected', 'image_path' => $path], 200);
        } catch (\Exception $e) {
            logger()->error('uploadAndIndex error for visitor ' . $visitor->id . ': ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // identify visitor by uploaded image
    public function identify(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120',
            'threshold' => 'nullable|numeric|min:50|max:100',
            'max_faces' => 'nullable|integer|min:1|max:10',
        ]);

        $path = $request->file('image')->getRealPath();
        $bytes = file_get_contents($path);

        $threshold = $request->input('threshold', 85.0);
        $maxFaces = $request->input('max_faces', 5);

        try {
            $rek = app(\App\Services\RekognitionService::class);
            $matches = $rek->searchByImageBytes($bytes, (float)$threshold, (int)$maxFaces);

            if (empty($matches)) {
                return response()->json(['status' => 'no_match'], 200);
            }

            // sort by similarity desc and pick best
            usort($matches, function ($a, $b) {
                return ($b['Similarity'] <=> $a['Similarity']);
            });

            $best = $matches[0];
            $face = $best['Face'] ?? null;

            if (! $face) {
                return response()->json(['status' => 'no_match'], 200);
            }

            $visitor = null;

            // try ExternalImageId parsing first
            if (! empty($face['ExternalImageId']) && preg_match('/visitor_(\d+)/', $face['ExternalImageId'], $m)) {
                $visitor = \App\Models\Visitor::find((int) $m[1]);
            }

            // fallback: lookup by face_id stored on visitor
            if (! $visitor && ! empty($face['FaceId'])) {
                $visitor = \App\Models\Visitor::where('face_id', $face['FaceId'])->first();
            }

            if ($visitor) {
                return response()->json([
                    'status' => 'match',
                    'similarity' => $best['Similarity'],
                    'visitor' => $visitor->only(['id','name','phone','user_id','face_id','image_path']),
                ], 200);
            }

            // face matched in collection but no local visitor mapping
            return response()->json([
                'status' => 'match_but_no_visitor_record',
                'similarity' => $best['Similarity'],
                'face' => $face,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
