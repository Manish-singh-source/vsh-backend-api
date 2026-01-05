<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\Http\Resources\AdvertisementResource;
use App\Http\Requests\Advertisement\CreateAdvertisementRequest;
use App\Http\Requests\Advertisement\UpdateAdvertisementRequest;
use Illuminate\Support\Facades\Storage;

class AdvertisementController extends Controller
{
    public function advertisementsList(Request $request)
    {
        $query = Advertisement::with('user')->orderBy('start_date', 'desc');

        if ($request->has('page') || $request->has('per_page')) {
            $perPage = (int) $request->get('per_page', 20);
            $items = $query->paginate($perPage);
            return AdvertisementResource::collection($items)->additional(['success' => true]);
        }

        $items = $query->get();
        return AdvertisementResource::collection($items)->additional(['success' => true]);
    }

    public function show(Advertisement $advertisement)
    {
        $advertisement->load('user');
        return (new AdvertisementResource($advertisement))->additional(['success' => true]);
    }

    public function createAdvertisement(CreateAdvertisementRequest $request)
    {
        $data = $request->validated();

        if ($file = $request->file('image')) {
            $path = $file->store('advertisements', 'public');
            $data['image'] = basename($path);
        }

        $user = $request->user();
        $data['added_by'] = $user->id ?? null;
        $data['added_at'] = now();

        $advertisement = Advertisement::create($data);

        return (new AdvertisementResource($advertisement->load('user')))->additional(['success' => true])->response()->setStatusCode(201);
    }

    public function updateAdvertisement(UpdateAdvertisementRequest $request, Advertisement $advertisement)
    {
        $data = $request->validated();

        if ($file = $request->file('image')) {
            if ($advertisement->image && Storage::disk('public')->exists('advertisements/' . $advertisement->image)) {
                Storage::disk('public')->delete('advertisements/' . $advertisement->image);
            }
            $path = $file->store('advertisements', 'public');
            $data['image'] = basename($path);
        }

        $advertisement->update($data);

        return (new AdvertisementResource($advertisement->fresh()->load('user')))->additional(['success' => true]);
    }

    public function deleteAdvertisement(Advertisement $advertisement)
    {
        $advertisement->delete();
        return response()->json(['success' => true], 200);
    }
}
