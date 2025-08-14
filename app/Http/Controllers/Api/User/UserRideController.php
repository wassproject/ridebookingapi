<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Models\City;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class UserRideController extends Controller
{
     public function index()
    {
        $userId = Auth::id();

        $rides = Ride::with(['driver', 'city']) // 👈 Eager load city & driver info
            ->where('user_id', $userId)
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'rides' => $rides
        ]);
    }


//    public function store(Request $request)
//    {
//        $request->validate([
//            'pickup_lat' => 'required|numeric',
//            'pickup_lng' => 'required|numeric',
//            'drop_lat'   => 'required|numeric',
//            'drop_lng'   => 'required|numeric',
//            'city_name'  => 'required|string',
//        ]);
//
//        // 🔍 Find city
//        $city = City::where('name', $request->city_name)->first();
//        if (!$city) {
//            return response()->json([
//                'status' => false,
//                'message' => 'City not found',
//            ], 404);
//        }
//
//        // 💰 Fare calculation
//        $baseFare = 50;
//        $variableFare = rand(50, 150);
//        $calculatedFare = $baseFare + $variableFare;
//
//        // 📝 Create ride (no driver assigned yet)
//        $ride = Ride::create([
//            'driver_id'       => null, // no driver assigned yet
//            'user_id'         => auth()->id(),
//            'pickup_lat'      => $request->pickup_lat,
//            'pickup_lng'      => $request->pickup_lng,
//            'drop_lat'        => $request->drop_lat,
//            'drop_lng'        => $request->drop_lng,
//            'ride_time'       => now(),
//            'status'          => 'upcoming', // waiting for a driver to accept
//            'fare'            => $calculatedFare,
//            'city_id'         => $city->id,
//        ]);
//
//        // 🚖 Get all drivers in that city
//        $driversInCity = Driver::where('city', $city->name)->get();
//
//        // 🔔 Send notification to all drivers in that city
////        foreach ($driversInCity as $driver) {
////            // Example: you can use Laravel events/notifications
////           // $driver->notify(new \App\Notifications\NewRideRequest($ride));
////        }
//
//        return response()->json([
//            'status' => true,
//            'message' => 'Ride created and all drivers in city notified',
//            'calculated_fare' => $calculatedFare,
//            'ride' => $ride,
//            'notified_drivers_count' => $driversInCity->count(),
//            'assigned_drivers' => $driversInCity,
//        ]);
//    }
    /**
     * Show a specific ride.
     */
    public function show($id)
    {
        $userId = Auth::id();

        $ride = Ride::with(['driver', 'city']) // 👈 Add relations
            ->where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        return response()->json([
            'status' => true,
            'ride' => $ride
        ]);
    }

//    public function store(Request $request)
//    {
//        $request->validate([
//            'pickup_lat' => 'required|numeric',
//            'pickup_lng' => 'required|numeric',
//            'drop_lat'   => 'required|numeric',
//            'drop_lng'   => 'required|numeric',
//            'city_name'  => 'required|string',
//        ]);
//
//        // 🔍 Find city
//        $city = City::where('name', $request->city_name)->first();
//        if (!$city) {
//            return response()->json([
//                'status' => false,
//                'message' => 'City not found',
//            ], 404);
//        }
//
//        // 🌍 Reverse Geocode pickup & drop using Nominatim
//        $pickupAddress = $this->getAddressFromLatLng($request->pickup_lat, $request->pickup_lng);
//        $dropAddress   = $this->getAddressFromLatLng($request->drop_lat, $request->drop_lng);
//
//        // 💰 Fare calculation
//        $baseFare = 50;
//        $variableFare = rand(50, 150);
//        $calculatedFare = $baseFare + $variableFare;
//
//        // 📝 Create ride (no driver assigned yet)
//        $ride = Ride::create([
//            'driver_id'       => null,
//            'user_id'         => auth()->id(),
//            'pickup_lat'      => $request->pickup_lat,
//            'pickup_lng'      => $request->pickup_lng,
//            'pickup_location'  => $pickupAddress,  // new
//            'drop_lat'        => $request->drop_lat,
//            'drop_lng'        => $request->drop_lng,
//            'drop_location'    => $dropAddress,    // new
//            'ride_time'       => now(),
//            'status'          => 'upcoming',
//            'fare'            => $calculatedFare,
//            'city_id'         => $city->id,
//        ]);
//
//        // 🚖 Get all drivers in that city
//        $driversInCity = Driver::where('city', $city->name)->get();
//
//        return response()->json([
//            'status' => true,
//            'message' => 'Ride created and all drivers in city notified',
//            'calculated_fare' => $calculatedFare,
//            'ride' => $ride,
//            'notified_drivers_count' => $driversInCity->count(),
//            'assigned_drivers' => $driversInCity,
//        ]);
//    }
//
//    /**
//     * Get address from latitude & longitude using Nominatim API
//     */
//    private function getAddressFromLatLng($lat, $lng)
//    {
//        $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$lat}&lon={$lng}&zoom=18&addressdetails=1";
//
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_USERAGENT, 'LaravelApp/1.0'); // Required by Nominatim
//        $response = curl_exec($ch);
//        curl_close($ch);
//
//        $data = json_decode($response, true);
//
//        return $data['display_name'] ?? 'Unknown location';
//    }


//    public function store(Request $request)
//    {
//        $request->validate([
//            'pickup_lat' => 'required|numeric',
//            'pickup_lng' => 'required|numeric',
//            'drop_lat'   => 'required|numeric',
//            'drop_lng'   => 'required|numeric',
//            'city_name'  => 'required|string',
//        ]);
//
//        // Find city
//        $city = City::where('name', $request->city_name)->first();
//        if (!$city) {
//            return response()->json([
//                'status' => false,
//                'message' => 'City not found',
//            ], 404);
//        }
//
//        // Reverse Geocode pickup & drop using Nominatim
//        $pickupAddress = $this->getAddressFromLatLng($request->pickup_lat, $request->pickup_lng);
//        $dropAddress   = $this->getAddressFromLatLng($request->drop_lat, $request->drop_lng);
//
//        // Fare calculation
//        $baseFare = 50;
//        $variableFare = rand(50, 150);
//        $calculatedFare = $baseFare + $variableFare;
//
//        // Create ride
//        $ride = Ride::create([
//            'driver_id'       => null,
//            'user_id'         => auth()->id(),
//            'pickup_lat'      => $request->pickup_lat,
//            'pickup_lng'      => $request->pickup_lng,
//            'pickup_location' => $pickupAddress,
//            'drop_lat'        => $request->drop_lat,
//            'drop_lng'        => $request->drop_lng,
//            'drop_location'   => $dropAddress,
//            'ride_time'       => now(),
//            'status'          => 'upcoming',
//            'fare'            => $calculatedFare,
//            'city_id'         => $city->id,
//        ]);
//
//        // Get all drivers in that city
//        $driversInCity = Driver::where('city', $city->name)->get();
//
//        // Prepare driver list with distance
//        $driversWithDistance = [];
//        foreach ($driversInCity as $driver) {
//            $driverLocation = Cache::get("driver_location_{$driver->id}");
//
//            if ($driverLocation) {
//                $distance = $this->calculateDistance(
//                    $driverLocation['lat'],
//                    $driverLocation['lng'],
//                    $request->pickup_lat,
//                    $request->pickup_lng
//                );
//            } else {
//                $distance = null;
//            }
//
//            $driversWithDistance[] = [
//                'driver_id' => $driver->id,
//                'name' => $driver->name,
//                'driver_lat' => $driverLocation['lat'] ?? null,
//                'driver_lng' => $driverLocation['lng'] ?? null,
//                'distance_km' => $distance,
//                'pickup_location' => $pickupAddress,
//                'drop_location' => $dropAddress,
//            ];
//        }
//
//        return response()->json([
//            'status' => true,
//            'message' => 'Ride created, drivers with distance returned',
//            'calculated_fare' => $calculatedFare,
//            'ride' => $ride,
//            'drivers' => $driversWithDistance
//        ]);
//    }
//
//    /**
//     * Reverse Geocode with Nominatim
//     */
//    private function getAddressFromLatLng($lat, $lng)
//    {
//        $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$lat}&lon={$lng}&zoom=18&addressdetails=1";
//
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_USERAGENT, 'LaravelApp/1.0');
//        $response = curl_exec($ch);
//        curl_close($ch);
//
//        $data = json_decode($response, true);
//        return $data['display_name'] ?? 'Unknown location';
//    }
//
//    /**
//     * Calculate distance between 2 lat/lng points (Haversine formula)
//     */
//    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
//    {
//        $earthRadius = 6371; // km
//        $dLat = deg2rad($lat2 - $lat1);
//        $dLon = deg2rad($lon2 - $lon1);
//
//        $a = sin($dLat/2) * sin($dLat/2) +
//            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
//            sin($dLon/2) * sin($dLon/2);
//        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
//
//        return round($earthRadius * $c, 2); // km
//    }

    public function store(Request $request)
    {
        $request->validate([
            'pickup_lat' => 'required|numeric',
            'pickup_lng' => 'required|numeric',
            'drop_lat'   => 'required|numeric',
            'drop_lng'   => 'required|numeric',
            'city_name'  => 'required|string',
        ]);

        // Find city
        $city = City::where('name', $request->city_name)->first();
        if (!$city) {
            return response()->json([
                'status' => false,
                'message' => 'City not found',
            ], 404);
        }

        // Reverse Geocode pickup & drop using Nominatim
        $pickupAddress = $this->getAddressFromLatLng($request->pickup_lat, $request->pickup_lng);
        $dropAddress   = $this->getAddressFromLatLng($request->drop_lat, $request->drop_lng);

        // Fare calculation
        $baseFare = 50;
        $variableFare = rand(50, 150);
        $calculatedFare = $baseFare + $variableFare;

        // Create ride
        $ride = Ride::create([
            'driver_id'       => null,
            'user_id'         => auth()->id(),
            'pickup_lat'      => $request->pickup_lat,
            'pickup_lng'      => $request->pickup_lng,
            'pickup_location' => $pickupAddress,
            'drop_lat'        => $request->drop_lat,
            'drop_lng'        => $request->drop_lng,
            'drop_location'   => $dropAddress,
            'ride_time'       => now(),
            'status'          => 'upcoming',
            'fare'            => $calculatedFare,
            'city_id'         => $city->id,
        ]);

        // Get all drivers in that city with their car & car type
        $driversInCity = Driver::with(['car.carType'])
            ->where('city', $city->name)
            ->get();

        // Prepare driver list with distance + car name
        $driversWithDistance = [];
        foreach ($driversInCity as $driver) {
            $driverLocation = Cache::get("driver_location_{$driver->id}");

            if ($driverLocation) {
                $distance = $this->calculateDistance(
                    $driverLocation['lat'],
                    $driverLocation['lng'],
                    $request->pickup_lat,
                    $request->pickup_lng
                );
            } else {
                $distance = null;
            }

            $driversWithDistance[] = [
                'driver_id'        => $driver->id,
                'name'             => $driver->name,
                'car_name'         => $driver->car->carType->name ?? 'Unknown',
                'driver_lat'       => $driverLocation['lat'] ?? null,
                'driver_lng'       => $driverLocation['lng'] ?? null,
                'distance_km'      => $distance,
                'pickup_location'  => $pickupAddress,
                'drop_location'    => $dropAddress,
            ];
        }

        return response()->json([
            'status' => true,
            'message' => 'Ride created, drivers with distance returned',
            'calculated_fare' => $calculatedFare,
            'ride' => $ride,
            'drivers' => $driversWithDistance
        ]);
    }

    /**
     * Reverse Geocode with Nominatim
     */
    private function getAddressFromLatLng($lat, $lng)
    {
        $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$lat}&lon={$lng}&zoom=18&addressdetails=1";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'LaravelApp/1.0');
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        return $data['display_name'] ?? 'Unknown location';
    }

    /**
     * Calculate distance between 2 lat/lng points (Haversine formula)
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) * sin($dLat/2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return round($earthRadius * $c, 2); // km
    }



}
