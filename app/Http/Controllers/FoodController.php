<?php

namespace App\Http\Controllers;

use App\Http\Resources\FoodResource;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $foods = Food::all();

        $responseData = [
            'total' => count($foods),
            'retrieved' => count($foods),
            'data' => FoodResource::collection($foods),
        ];

        return response()->json($responseData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:1',
            'description' => 'required|string',
        ], [
            'name.required' => 'Name is required.',
            'price.required' => 'Price is required.',
            'description.required' => 'Description is required.',
            'price.integer' => 'Price must be a valid integer.',
            'price.min' => 'Price must be more than 0.',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $errors,
            ], 422);
        }

        $food = Food::create($request->all());

        $foodResource = new FoodResource($food);

        return response()->json($foodResource, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $food = Food::find($id);

        if (!$food) {
            return response()->json(['message' => 'The given food resource is not found.'], 404);
        }

        $foodResource = new FoodResource($food);

        return response()->json($foodResource, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $food = Food::find($id);

        if (!$food) {
            return response()->json(['message' => 'The given food resource is not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:1',
            'description' => 'required|string',
        ], [
            'name.required' => 'Name is required.',
            'price.required' => 'Price is required.',
            'description.required' => 'Description is required.',
            'price.integer' => 'Price must be a valid integer.',
            'price.min' => 'Price must be more than 0.',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $errors,
            ], 422);
        }

        $food->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
        ]);

        $foodResource = new FoodResource($food);

        return response()->json($foodResource, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $food = Food::find($id);

        if (!$food) {
            return response()->json(['message' => 'The given food resource is not found.'], 404);
        }

        $food->delete();
    }
}
