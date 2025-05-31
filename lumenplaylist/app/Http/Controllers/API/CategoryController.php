<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CategoryController extends BaseController
{
    /**
     * Display a listing of categories.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $categories = Category::all();
        return $this->successResponse($categories, 'Daftar kategori berhasil diambil');
    }

    /**
     * Store a newly created category.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kategori' => 'required|string|max:255',
            'keterangan' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $category = Category::create($request->all());

        return $this->successResponse(
            $category,
            'Kategori berhasil ditambahkan',
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified category.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->errorResponse(
                'Kategori tidak ditemukan',
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->successResponse($category, 'Kategori berhasil diambil');
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
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'kategori' => 'required|string|max:255',
            'keterangan' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                $validator->errors(),
                Response::HTTP_BAD_REQUEST
            );
        }

        // Find category by id_kategori
        $category = Category::where('id_kategori', $id)->first();

        if (!$category) {
            return $this->errorResponse(
                'Kategori tidak ditemukan',
                Response::HTTP_NOT_FOUND
            );
        }

        // Update the category
        $category->update($request->all());

        return $this->successResponse(
            $category,
            'Kategori berhasil diperbarui'
        );
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Find category by id_kategori
        $category = Category::where('id_kategori', $id)->first();
        
        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], Response::HTTP_NOT_FOUND);
        }

        $category->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data sudah dihapus'
        ], Response::HTTP_OK);
    }
}