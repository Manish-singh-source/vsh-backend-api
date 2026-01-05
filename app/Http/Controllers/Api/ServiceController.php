<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Http\Resources\ServiceResource;
use App\Http\Requests\Service\CreateServiceRequest;
use App\Http\Requests\Service\UpdateServiceRequest;

class ServiceController extends Controller
{
    public function servicesList(Request $request)
    {
        $query = Service::with('user')->orderBy('name', 'asc');

        if ($request->has('page') || $request->has('per_page')) {
            $perPage = (int) $request->get('per_page', 20);
            $items = $query->paginate($perPage);
            return ServiceResource::collection($items)->additional(['success' => true]);
        }

        $items = $query->get();
        return ServiceResource::collection($items)->additional(['success' => true]);
    }

    public function show(Service $service)
    {
        $service->load('user');
        return (new ServiceResource($service))->additional(['success' => true]);
    }

    public function createService(CreateServiceRequest $request)
    {
        $data = $request->validated();

        $user = $request->user();
        $data['added_by'] = $user->id ?? null;
        $data['added_at'] = now();

        $service = Service::create($data);

        return (new ServiceResource($service->load('user')))->additional(['success' => true])->response()->setStatusCode(201);
    }

    public function updateService(UpdateServiceRequest $request, Service $service)
    {
        $data = $request->validated();

        $service->update($data);

        return (new ServiceResource($service->fresh()->load('user')))->additional(['success' => true]);
    }

    public function deleteService(Service $service)
    {
        $service->delete();
        return response()->json(['success' => true], 200);
    }
}
