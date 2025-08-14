<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Car;
use Illuminate\Support\Facades\Auth;
class DriverCarController extends Controller
{
     public function getCarDetails(Request $request)
    {
        $car = Auth::user()->car()->with(['carType', 'timeRanges'])->first();

        if (!$car) {
            return response()->json(['status' => false, 'message' => 'No car details found'], 404);
        }

        return response()->json([
            'status' => true,
            'car' => [
                'id' => $car->id,
                'car_type' => $car->carType ? [
                    'id' => $car->carType->id,
                    'name' => $car->carType->name,
                    'description' => $car->carType->description,
                ] : null,
                'transmission' => $car->transmission,
                'time_ranges' => $car->timeRanges->map(fn($t) => [
                    'id' => $t->id,
                    'label' => $t->label,
                    'start_time' => $t->start_time,
                    'end_time' => $t->end_time,
                ]),
            ]
        ]);
    }

    // PUT /driver/car
    public function updateCarDetails(Request $request)
    {
        $request->validate([
            'car_type_id' => 'nullable|exists:car_types,id',
            'transmission' => 'required|in:manual,auto',
            'time_ranges' => 'nullable|array',
            'time_ranges.*' => 'exists:time_ranges,id',
        ]);

        $driverId = Auth::id();

        $car = Car::updateOrCreate(
            ['driver_id' => $driverId],
            [
                'car_type_id' => $request->input('car_type_id'),
                'transmission' => $request->input('transmission'),
                // leave description as is (admin sets type/description).
            ]
        );

        // sync selected time ranges (driver chooses pre-existing admin ranges)
        if ($request->has('time_ranges')) {
            $car->timeRanges()->sync($request->input('time_ranges', []));
        }

        return response()->json([
            'status' => true,
            'message' => 'Car details updated successfully',
            'car' => $car->load(['carType', 'timeRanges']),
        ]);
    }

     public function updateDetails(Request $request)
    {
        $driver = Auth::guard('driver-api')->user(); // Logged-in driver

        $validated = $request->validate([
            'email'             => 'nullable|email',
            'address'           => 'nullable|string|max:255',
            'city'              => 'nullable|string|max:100',
            'state'             => 'nullable|string|max:100',
            'pin'               => 'nullable|string|max:10',
            'dl_number'         => 'nullable|string|max:50',
            'dl_validity_date'  => 'nullable|date',
        ]);

        $driver->update($validated);

        return response()->json([
            'message' => 'Driver details updated successfully',
            'driver'  => $driver
        ]);
    }
}
