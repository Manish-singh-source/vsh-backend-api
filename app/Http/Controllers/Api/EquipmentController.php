<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Http\Resources\EquipmentResource;
use App\Http\Requests\Equipment\CreateEquipmentRequest;
use App\Http\Requests\Equipment\UpdateEquipmentRequest;
use Illuminate\Support\Facades\Storage;

class EquipmentController extends Controller
{
    public function equipmentsList(Request $request)
    {
        $query = Equipment::with('user')->orderBy('name', 'asc');

        if ($request->has('page') || $request->has('per_page')) {
            $perPage = (int) $request->get('per_page', 20);
            $items = $query->paginate($perPage);
            return EquipmentResource::collection($items)->additional(['success' => true]);
        }

        $items = $query->get();
        return EquipmentResource::collection($items)->additional(['success' => true]);
    }

    public function show(Equipment $equipment)
    {
        $equipment->load('user');
        return (new EquipmentResource($equipment))->additional(['success' => true]);
    }

    public function createEquipment(CreateEquipmentRequest $request)
    {
        $data = $request->validated();

        if ($file = $request->file('image')) {
            $path = $file->store('equipment', 'public');
            $data['image'] = basename($path);
        }

        $user = $request->user();
        $data['added_by'] = $user->id ?? null;
        $data['added_at'] = now();

        $equipment = Equipment::create($data);

        return (new EquipmentResource($equipment->load('user')))->additional(['success' => true])->response()->setStatusCode(201);
    }

    public function updateEquipment(UpdateEquipmentRequest $request, Equipment $equipment)
    {
        $data = $request->validated();

        if ($file = $request->file('image')) {
            if ($equipment->image && Storage::disk('public')->exists('equipment/' . $equipment->image)) {
                Storage::disk('public')->delete('equipment/' . $equipment->image);
            }
            $path = $file->store('equipment', 'public');
            $data['image'] = basename($path);
        }

        $equipment->update($data);

        return (new EquipmentResource($equipment->fresh()->load('user')))->additional(['success' => true]);
    }

    public function deleteEquipment(Equipment $equipment)
    {
        $equipment->delete();
        return response()->json(['success' => true], 200);
    }
}
