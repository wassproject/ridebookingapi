<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\Ride;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class DriverRideController extends Controller
{

//public function upcoming()
//{
//    $driverId = auth('driver-api')->id();
//
//    $rides = Ride::with(['user', 'city'])
//        ->where('driver_id', $driverId)
//        ->where('status', 'upcoming')
//        ->orderBy('ride_time', 'asc')
//        ->get();
//
//    $formattedRides = $rides->map(function ($ride) {
//        return [
//            'ride_id' => $ride->id,
//            'user_name' => 'MR. ' . strtoupper($ride->user->name),
//            'user_photo' => $ride->user->photo ?? 'https://example.com/images/default_user.jpg',
//
//            // Using lat/lng instead of location strings
//            'pickup_lat' => $ride->pickup_lat,
//            'pickup_lng' => $ride->pickup_lng,
//            'drop_lat' => $ride->drop_lat,
//            'drop_lng' => $ride->drop_lng,
//
//            'ride_time' => \Carbon\Carbon::parse($ride->ride_time)->format('h:i A'),
//            'is_today' => \Carbon\Carbon::parse($ride->ride_time)->isToday(),
//            'ride_type' => $ride->ride_type ?? 'oneway',
//            'duration' => $ride->duration ?? '2 hours',
//            'fare' => number_format($ride->fare, 2),
//        ];
//    });
//
//    \Log::info('Rides:', $rides->toArray());
//
//    return response()->json([
//        'status' => true,
//        'total_upcoming_rides' => $formattedRides->count(),
//        'data' => $formattedRides,
//    ]);
//}
    public function upcoming()
    {
        $driver = auth('driver-api')->user();

        if (!$driver) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        // Get rides: assigned to driver OR available in same city
        $rides = Ride::with(['user', 'city'])
            ->where(function ($query) use ($driver) {
                $query->where('driver_id', $driver->id)
                    ->orWhere(function ($subQuery) use ($driver) {
                        $subQuery->whereNull('driver_id')
                            ->where('city_id', function ($q) use ($driver) {
                                $q->select('id')
                                    ->from('cities')
                                    ->where('name', $driver->city)
                                    ->limit(1);
                            });
                    });
            })
            ->where('status', 'upcoming')
            ->orderBy('ride_time', 'asc')
            ->get();

        $formattedRides = $rides->map(function ($ride) {
            return [
                'ride_id' => $ride->id,
                'user_name' => $ride->user ? 'MR. ' . strtoupper($ride->user->name) : 'N/A',
                'user_photo' => $ride->user->photo ?? 'https://example.com/images/default_user.jpg',

                'pickup_lat' => $ride->pickup_lat,
                'pickup_lng' => $ride->pickup_lng,
                'drop_lat' => $ride->drop_lat,
                'drop_lng' => $ride->drop_lng,

                'ride_time' => \Carbon\Carbon::parse($ride->ride_time)->format('h:i A'),
                'is_today' => \Carbon\Carbon::parse($ride->ride_time)->isToday(),
                'ride_type' => $ride->ride_type ?? 'oneway',
                'duration' => $ride->duration ?? '2 hours',
                'fare' => number_format($ride->fare, 2),
            ];
        });

        return response()->json([
            'status' => true,
            'total_upcoming_rides' => $formattedRides->count(),
            'data' => $formattedRides,
        ]);
    }

public function today()
{
    $driverId = auth('driver-api')->id();

    $rides = Ride::with(['user', 'city'])
        ->where('driver_id', $driverId)
        ->whereDate('ride_time', Carbon::today()) // Only today’s rides
        ->orderBy('ride_time', 'asc')
        ->get();

    $formattedRides = $rides->map(function ($ride) {
        return [
            'ride_id' => $ride->id, // 👈 so driver can take actions later
            'status' => $ride->status, // 👈 so frontend can show badge color
            'user_name' => 'MR. ' . strtoupper($ride->user->name),
            'user_photo' => $ride->user->photo ?? 'https://example.com/images/default_user.jpg',
            'pickup_location' => $ride->pickup_location,
            'drop_location' => $ride->drop_location,
            'ride_time' => Carbon::parse($ride->ride_time)->format('h:i A'),
            'is_today' => true,
            'ride_type' => $ride->ride_type ?? 'oneway',
            'duration' => $ride->duration ?? '2 hours',
            'fare' => number_format($ride->fare, 2),
            'city' => $ride->city->name ?? null,
        ];
    });

    return response()->json([
        'status' => true,
        'total_today_rides' => $formattedRides->count(),
        'data' => $formattedRides,
    ]);
}


public function past()
{
    $driverId = auth('driver-api')->id();

    $rides = Ride::with(['user', 'city'])
        ->where('driver_id', $driverId)
       ->whereIn('status', ['completed', 'cancelled']) // completed rides only
        ->whereDate('ride_time', '<', Carbon::today()) // before today
        ->orderBy('ride_time', 'desc')
        ->get();

    $formattedRides = $rides->map(function ($ride) {
        return [
            'user_name' => 'MR. ' . strtoupper($ride->user->name),
            'user_photo' => $ride->user->photo ?? 'https://example.com/images/default_user.jpg',
            'pickup_location' => $ride->pickup_location,
            'drop_location' => $ride->drop_location,
            'ride_time' => Carbon::parse($ride->ride_time)->format('d M Y h:i A'),
            'is_today' => false,
            'ride_type' => $ride->ride_type ?? 'oneway',
            'duration' => $ride->duration ?? '2 hours',
            'fare' => number_format($ride->fare, 2),
            'city' => $ride->city->name ?? null,
        ];
    });

    return response()->json([
        'status' => true,
        'total_past_rides' => $formattedRides->count(),
        'data' => $formattedRides,
    ]);
}


    // Get total completed rides for the driver
    public function totalRides()
    {
        $driverId = auth('driver-api')->id();

        $count = Ride::where('driver_id', $driverId)
            ->where('status', 'completed')
            ->count();

        return response()->json([
            'status' => true,
            'total_completed_rides' => $count,
        ]);
    }


public function earnings()
{
    $driverId = auth('driver-api')->id();

    // Total fare from completed rides
    $totalFare = Ride::where('driver_id', $driverId)
        ->where('status', 'completed')
        ->sum('fare');

    // 80% goes to the driver
    $driverEarnings = $totalFare * 0.8;

    return response()->json([
        'status' => true,
        'total_earnings' => round($driverEarnings, 2), // Optional rounding
    ]);
}
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:completed,cancelled',
        ]);

        $driverId = auth('driver-api')->id();

        $ride = Ride::where('id', $id)
            ->where('driver_id', $driverId)
            ->first();

        if (!$ride) {
            return response()->json([
                'status' => false,
                'message' => 'Ride not found or unauthorized access',
            ], 404);
        }

        // Prevent re-updating finished rides
        if (in_array($ride->status, ['completed', 'cancelled'])) {
            return response()->json([
                'status' => false,
                'message' => 'Ride is already finished and cannot be updated',
            ], 400);
        }

        $ride->status = $request->status;
        $ride->save();

        return response()->json([
            'status' => true,
            'message' => 'Ride status updated successfully',
            'ride' => $ride,
        ]);
    }




    public function profile(Request $request)
{
    $user = $request->user();
    $driver = \App\Models\Driver::where('email', $user->email)->first();

    if (!$driver) {
        return response()->json(['status' => false, 'message' => 'Driver not found'], 404);
    }

    if ($request->isMethod('get')) {
        return response()->json([
            'status' => true,
            'data' => [
                'name' => 'MR. ' . strtoupper($driver->name . ' ' . $driver->middle_name . ' ' . $driver->last_name),
                'profile_success' => '89%',
                'description_title' => $driver->description_title ?? 'Reference site about Lorem',
                'description' => $driver->description ?? 'Reference site about Lorem Ipsum...',
                'user_photo' => $driver->user_photo ? asset('storage/' . $driver->user_photo) : null,
                'in_city' => (bool) $driver->in_city,
                'mt_drive' => (bool) $driver->mt_drive,
                'rating' => 4.8,
                'experience_years' => $driver->dl_registration_date
                    ? now()->diffInYears($driver->dl_registration_date)
                    : 0,
            ]
        ]);
    }

    // For PUT method – update profile
    $request->validate([
        'name' => 'nullable|string|max:255',
        'description_title' => 'nullable|string|max:255',
        'description' => 'nullable|string|max:1000',
        'user_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'in_city' => 'nullable|boolean',
        'mt_drive' => 'nullable|boolean',
        'experience_years' => 'nullable|numeric',
    ]);

    // Split name back to parts (if needed)
    if ($request->filled('name')) {
        $nameString = trim(str_replace(['MR.', 'MR', 'Mr.', 'Mr'], '', $request->input('name')));
        $parts = explode(' ', $nameString);
        $driver->name = $parts[0] ?? null;
        $driver->middle_name = $parts[1] ?? null;
        $driver->last_name = $parts[2] ?? null;
    }

    if ($request->hasFile('user_photo')) {
        $path = $request->file('user_photo')->store('user_photos', 'public');
        $driver->user_photo = $path;
    }

    $driver->description_title = $request->input('description_title', $driver->description_title);
    $driver->description = $request->input('description', $driver->description);
    $driver->in_city = $request->input('in_city', $driver->in_city);
    $driver->mt_drive = $request->input('mt_drive', $driver->mt_drive);

    if ($request->filled('experience_years')) {
        $months = intval($request->input('experience_years') * 12);
        $driver->dl_registration_date = now()->subMonths($months);
    }

    $driver->save();

    return response()->json([
        'status' => true,
        'message' => 'Driver profile updated successfully'
    ]);
}

public function rideDetail($id)
{
    $driverId = auth('driver-api')->id();

    $ride = Ride::with(['user', 'city'])
        ->where('driver_id', $driverId)
        ->where('id', $id)
        ->first();

    if (!$ride) {
        return response()->json([
            'status' => false,
            'message' => 'Ride not found'
        ], 404);
    }

    return response()->json([
        'status' => true,
        'data' => [
            'ride_id'       => $ride->id,
            'user_name'     => 'MR. ' . strtoupper($ride->user->name),
            'user_photo'    => $ride->user->photo ?? 'https://example.com/images/default_user.jpg',
            'pickup_location' => $ride->pickup_location,
            'pickup_lat'    => $ride->pickup_lat,
            'pickup_lng'    => $ride->pickup_lng,
            'drop_location' => $ride->drop_location,
            'drop_lat'      => $ride->drop_lat,
            'drop_lng'      => $ride->drop_lng,
            'ride_time'     => \Carbon\Carbon::parse($ride->ride_time)->format('h:i A'),
            'fare'          => number_format($ride->fare, 2),
            'google_maps_pickup_url' => "https://www.google.com/maps?q={$ride->pickup_lat},{$ride->pickup_lng}",
            'google_maps_drop_url'   => "https://www.google.com/maps?q={$ride->drop_lat},{$ride->drop_lng}"
        ]
    ]);
}

public function callClient($rideId)
{
    $driverId = auth('driver-api')->id();

    $ride = Ride::with('user')
        ->where('driver_id', $driverId)
        ->where('id', $rideId)
        ->first();

    if (!$ride) {
        return response()->json([
            'status' => false,
            'message' => 'Ride not found'
        ], 404);
    }

    return response()->json([
        'status' => true,
        'data' => [
            'user_name'   => $ride->user->name,
            'phone'       => $ride->user->phone, // Driver will use this to call
            'ride_id'     => $ride->id
        ]
    ]);
}

//    public function acceptRide($id)
//    {
//        $driverId = auth('driver-api')->id();
//
//        $ride = Ride::where('id', $id)
//            ->whereNull('driver_id') // Must not already have a driver
//            ->first();
//
//        if (!$ride) {
//            return response()->json([
//                'status' => false,
//                'message' => 'Ride not found or already assigned to another driver',
//            ], 404);
//        }
//
//        // Only allow accepting rides that are still upcoming
//        if ($ride->status !== 'upcoming') {
//            return response()->json([
//                'status' => false,
//                'message' => 'Ride cannot be accepted because it is not upcoming',
//            ], 400);
//        }
//
//        $ride->driver_id = $driverId;
//        $ride->status = 'accepted';
//        $ride->save();
//
//        return response()->json([
//            'status' => true,
//            'message' => 'Ride accepted successfully',
//            'ride' => $ride,
//        ]);
//    }
    public function acceptRide($id)
    {
        $driver = auth('driver-api')->user(); // Get full driver model
        $driverId = $driver->id;

        $ride = Ride::where('id', $id)
            ->whereNull('driver_id') // Must not already have a driver
            ->first();

        if (!$ride) {
            return response()->json([
                'status' => false,
                'message' => 'Ride not found or already assigned to another driver',
            ], 404);
        }

        // Only allow accepting rides that are still upcoming
        if ($ride->status !== 'upcoming') {
            return response()->json([
                'status' => false,
                'message' => 'Ride cannot be accepted because it is not upcoming',
            ], 400);
        }

        // Get fare from ride table & calculate 20% commission
        $debitedAmount = round($ride->fare * 0.20, 2);

        // Check if driver has enough wallet balance
        if ($driver->wallet_balance < $debitedAmount) {
            return response()->json([
                'status' => false,
                'message' => 'Insufficient wallet balance to accept this ride. Minimum required: ' . number_format($debitedAmount, 2),
            ], 400);
        }

        // Deduct from wallet
        $driver->wallet_balance -= $debitedAmount;
        $driver->save();

        // Assign driver to ride
        $ride->driver_id = $driverId;
        $ride->status = 'accepted';
        $ride->save();

        return response()->json([
            'status' => true,
            'message' => 'Ride accepted successfully',
            'debited_amount' => $debitedAmount,
            'wallet_balance' => $driver->wallet_balance,
            'ride' => $ride
        ]);
    }


    public function updateLocation(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $driver = auth()->user(); // Must be logged-in driver

        // Save to cache (driver_location_DRIVERID)
        Cache::put("driver_location_{$driver->id}", [
            'lat' => $request->lat,
            'lng' => $request->lng,
            'updated_at' => now()->toDateTimeString(),
        ], now()->addHours(1)); // Keep in cache for 1 hour

        return response()->json([
            'status' => true,
            'message' => 'Driver location updated successfully',
            'driver_id' => $driver->id,
            'lat' => $request->lat,
            'lng' => $request->lng,
        ]);
    }



}
