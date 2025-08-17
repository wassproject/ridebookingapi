<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\TimeRange;
use Illuminate\Http\Request;

class AdminTimeRangeController extends Controller
{
    public function index()
    {
        return response()->json(TimeRange::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
        ]);

        $timeRange = TimeRange::create($request->all());

        return response()->json($timeRange, 201);
    }

    public function show(TimeRange $timeRange)
    {
        return response()->json($timeRange);
    }

    public function update(Request $request, TimeRange $timeRange)
    {
        $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
        ]);

        $timeRange->update($request->all());

        return response()->json($timeRange);
    }

    public function destroy(TimeRange $timeRange)
    {
        $timeRange->delete();
        return response()->json(null, 204);
    }
}
