<?php

namespace App\Http\Controllers\API;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class PelangganController extends BaseController
{
    /**
     * Display a listing of customers.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $customers = Pelanggan::all();
        return $this->successResponse($customers, 'Daftar pelanggan berhasil diambil');
    }

    /**
     * Store a newly created customer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pelanggan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'telepon' => 'required|string|max:20'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $customer = Pelanggan::create($request->all());

        return $this->successResponse(
            $customer,
            'Pelanggan berhasil ditambahkan',
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified customer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $customer = Pelanggan::find($id);

        if (!$customer) {
            return $this->errorResponse(
                'Pelanggan tidak ditemukan',
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->successResponse($customer, 'Pelanggan berhasil diambil');
    }

    /**
     * Update the specified customer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'pelanggan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'telepon' => 'required|string|max:20'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                $validator->errors(),
                Response::HTTP_BAD_REQUEST
            );
        }

        // Find customer by id_pelanggan
        $customer = Pelanggan::where('id_pelanggan', $id)->first();

        if (!$customer) {
            return $this->errorResponse(
                'Pelanggan tidak ditemukan',
                Response::HTTP_NOT_FOUND
            );
        }

        // Update the customer
        $customer->update($request->all());

        return $this->successResponse(
            $customer,
            'Pelanggan berhasil diperbarui'
        );
    }

    /**
     * Remove the specified customer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Find customer by id_pelanggan
        $customer = Pelanggan::where('id_pelanggan', $id)->first();

        if (!$customer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], Response::HTTP_NOT_FOUND);
        }

        $customer->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data sudah dihapus'
        ], Response::HTTP_OK);
    }
}