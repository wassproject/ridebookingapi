<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DriverRideInviteController extends Controller
{
    public function index(Request $request)
    {
        $driver = auth('driver-api')->user();

        // Driver image
        $driverImage = $driver->selfie_photo
            ? asset('storage/' . $driver->selfie_photo)
            : null;

        $ridesForDriver = [];

        // 1️⃣ Get cached pending rides
        $pendingRideIds = Cache::get('pending_ride_ids', []);
        foreach ($pendingRideIds as $rideId) {
            $rideData = Cache::get("pending_ride_{$rideId}");
            if ($rideData && $rideData['city_name'] === $driver->city) {
                $distance = $this->calculateDistanceFromDriver($driver->id, $rideData['pickup_lat'], $rideData['pickup_lng']);

                $ridesForDriver[] = [
                    'ride_id' => $rideId,
                    'pickup_location' => $rideData['pickup_location'],
                    'drop_location' => $rideData['drop_location'],
                    'fare' => $rideData['fare'],
                    'hourly' => $rideData['hourly'],
                    'trip_type' => $rideData['trip_type'],
                    'car_type' => $rideData['car_type'],
                    'time' => $rideData['time'],
                    'trip_starting_date' => $rideData['trip_starting_date'],
                    'trip_ending_date' => $rideData['trip_ending_date'],
                    'transmission' => $rideData['transmission'],
                    'distance_km' => $distance,
                    //'status' => 'pending', // cached ride
                    'status'  => $rideData['status'],
                ];
            }
        }

        // 2️⃣ Get DB rides that are upcoming and unassigned
        $dbRides = Ride::where('city_id', $driver->city_id ?? null) // ensure city_id exists
        ->whereNull('driver_id')
            ->where('status', 'pending')
            ->get();

        foreach ($dbRides as $ride) {
            $distance = $this->calculateDistanceFromDriver($driver->id, $ride->pickup_lat, $ride->pickup_lng);

            $ridesForDriver[] = [
                'ride_id' => $ride->id,
                'pickup_location' => $ride->pickup_location,
                'drop_location' => $ride->drop_location,
                'fare' => $ride->fare,
                'hourly' => $ride->hourly,
                'trip_type' => $ride->trip_type,
                'car_type' => $ride->car_type,
                'time' => $ride->time,
                'trip_starting_date' => $ride->trip_starting_date,
                'trip_ending_date' => $ride->trip_ending_date,
                'transmission' => $ride->transmission,
                'distance_km' => $distance,
                'status' => $ride->status,
            ];
        }

        return response()->json([
            'status' => true,
            'message' => 'Pending rides for this driver',
            'driver_image' => $driverImage,
            'rides' => $ridesForDriver,
        ]);
    }

    private function calculateDistanceFromDriver($driverId, $pickupLat, $pickupLng)
    {
        $driverLocation = Cache::get("driver_location_{$driverId}");
        if (!$driverLocation) return null;

        $earthRadius = 6371; // km
        $dLat = deg2rad($pickupLat - $driverLocation['lat']);
        $dLon = deg2rad($pickupLng - $driverLocation['lng']);

        $a = sin($dLat/2) * sin($dLat/2) +
            cos(deg2rad($driverLocation['lat'])) * cos(deg2rad($pickupLat)) *
            sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return round($earthRadius * $c, 2);
    }

}
