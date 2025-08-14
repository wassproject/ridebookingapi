<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CarType;

class AdminCarTypeController extends Controller
{

   public function index()
    {
        return response()->json(CarType::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $carType = CarType::create($request->all());

        return response()->json($carType, 201);
    }

    public function show(CarType $carType)
    {
        return response()->json($carType);
    }

    public function update(Request $request, CarType $carType)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $carType->update($request->all());

        return response()->json($carType);
    }

    public function destroy(CarType $carType)
    {
        $carType->delete();
        return response()->json(null, 204);
    }
}
