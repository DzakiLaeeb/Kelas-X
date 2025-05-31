<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CustomerController extends BaseController
{
    /**
     * Display a listing of customers.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Simulate customer data (will be replaced with database query)
        $customers = [
            ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'phone' => '08123456789'],
            ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com', 'phone' => '08234567890']
        ];

        return $this->successResponse($customers, 'Customer list retrieved successfully');
    }

    /**
     * Store a newly created customer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20'
        ]);

        // Simulate storing customer (will be replaced with database insert)
        $customer = [
            'id' => 3,
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone')
        ];

        return $this->successResponse($customer, 'Customer created successfully', Response::HTTP_CREATED);
    }

    /**
     * Display the specified customer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Simulate finding customer (will be replaced with database query)
        $customer = [
            'id' => $id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '08123456789'
        ];

        return $this->successResponse($customer, 'Customer retrieved successfully');
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
        $this->validate($request, [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255',
            'phone' => 'sometimes|required|string|max:20'
        ]);

        // Simulate updating customer (will be replaced with database update)
        $customer = [
            'id' => $id,
            'name' => $request->input('name', 'John Doe'),
            'email' => $request->input('email', 'john@example.com'),
            'phone' => $request->input('phone', '08123456789')
        ];

        return $this->successResponse($customer, 'Customer updated successfully');
    }

    /**
     * Remove the specified customer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Simulate deleting customer (will be replaced with database delete)
        return $this->successResponse(['id' => $id], 'Customer deleted successfully');
    }
}
