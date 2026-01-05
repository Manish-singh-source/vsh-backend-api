<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notice;
use App\Http\Resources\NoticeResource;
use App\Http\Requests\Notice\CreateNoticeRequest;
use App\Http\Requests\Notice\UpdateNoticeRequest;
use Illuminate\Support\Facades\Storage;

class NoticeController extends Controller
{
    public function noticesList(Request $request)
    {
        $query = Notice::with('user')->orderBy('start_date', 'desc');

        if ($request->has('page') || $request->has('per_page')) {
            $perPage = (int) $request->get('per_page', 20);
            $items = $query->paginate($perPage);
            return NoticeResource::collection($items)->additional(['success' => true]);
        }

        $items = $query->get();
        return NoticeResource::collection($items)->additional(['success' => true]);
    }

    public function show(Notice $notice)
    {
        $notice->load('user');
        return (new NoticeResource($notice))->additional(['success' => true]);
    }

    public function createNotice(CreateNoticeRequest $request)
    {
        $data = $request->validated();

        if ($file = $request->file('image')) {
            $path = $file->store('notices', 'public');
            $data['image'] = basename($path);
        }

        $user = $request->user();
        $data['added_by'] = $user->id ?? null;
        $data['added_at'] = now();

        $notice = Notice::create($data);

        return (new NoticeResource($notice->load('user')))->additional(['success' => true])->response()->setStatusCode(201);
    }

    public function updateNotice(UpdateNoticeRequest $request, Notice $notice)
    {
        $data = $request->validated();

        if ($file = $request->file('image')) {
            if ($notice->image && Storage::disk('public')->exists('notices/' . $notice->image)) {
                Storage::disk('public')->delete('notices/' . $notice->image);
            }
            $path = $file->store('notices', 'public');
            $data['image'] = basename($path);
        }

        $notice->update($data);

        return (new NoticeResource($notice->fresh()->load('user')))->additional(['success' => true]);
    }

    public function deleteNotice(Notice $notice)
    {
        $notice->delete();
        return response()->json(['success' => true], 200);
    }
}
