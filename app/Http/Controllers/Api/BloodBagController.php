<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBloodBagRequest;
use App\Http\Requests\UpdateBloodBagRequest;
use App\Http\Resources\BloodBagResource;
use App\Models\BloodBag;


class BloodBagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         $bloodBags = BloodBag::query()

       
            ->with(['refrigerator.bloodBank'])
            ->when($request->filled('blood_group'), function ($query) use ($request) {
                $query->where('blood_group', strtoupper($request->blood_group));
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('refrigerator_id'), function ($query) use ($request) {
                $query->where('refrigerator_id', $request->refrigerator_id);
            })
            ->when($request->filled('blood_bank_id'), function ($query) use ($request) {
                $query->whereHas('refrigerator', function ($refrigeratorQuery) use ($request) {
                    $refrigeratorQuery->where('blood_bank_id', $request->blood_bank_id);
                });
            })
            ->when($request->boolean('expiring_soon'), function ($query) {
                $query->whereBetween('expiry_date', [
                    now()->toDateString(),
                    now()->addDay()->toDateString(),
                ]);
            })
            ->latest()
            ->paginate($request->integer('per_page', 15));

            return BloodBagResource::collection($bloodBags);
    
    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBloodBagRequest $request)
    {
        $bloodBag = BloodBag::create($request->validated());

        return BloodBagResource::make(
            $bloodBag->load('refrigerator.bloodBank')
        )->additional([
            'message' => 'Blood bag created successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(BloodBag  $bloodBag)
    {
        return BloodBagResource::make(
            $bloodBag->load('refrigerator.bloodBank')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBloodBagRequest $request, BloodBag $bloodBag)
    {
        $bloodBag->update($request->validated());

         return BloodBagResource::make(
            $bloodBag->load('refrigerator.bloodBank')
        )->additional([
            'message' => 'Blood bag updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BloodBag $bloodBag)
    {
        $bloodBag->delete();

        return response()->json([
            'message' => 'Blood bag deleted successfully',
        ]);
    }
}
