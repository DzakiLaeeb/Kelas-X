<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Simulate getting categories (will be replaced with database query later)
        $categories = [
            ['id' => 1, 'name' => 'Electronics'],
            ['id' => 2, 'name' => 'Books'],
            ['id' => 3, 'name' => 'Clothing']
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Categories retrieved successfully',
            'data' => $categories
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created category.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate request
        $this->validate($request, [
            'name' => 'required|string|max:255'
        ]);

        // Simulate storing category (will be replaced with database insert later)
        $category = [
            'id' => 4, // Simulated ID
            'name' => $request->input('name')
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Category created successfully',
            'data' => $category
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified category.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Simulate finding category (will be replaced with database query later)
        $category = [
            'id' => $id,
            'name' => 'Category ' . $id
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Category retrieved successfully',
            'data' => $category
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified category.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Validate request
        $this->validate($request, [
            'name' => 'required|string|max:255'
        ]);

        // Simulate updating category (will be replaced with database update later)
        $category = [
            'id' => $id,
            'name' => $request->input('name')
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Category updated successfully',
            'data' => $category
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified category.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Simulate deleting category (will be replaced with database delete later)
        return response()->json([
            'status' => 'success',
            'message' => 'Category deleted successfully',
            'data' => ['id' => $id]
        ], Response::HTTP_OK);
    }
}
