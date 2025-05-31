<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends BaseController
{
    /**
     * Display a listing of orders.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Simulate order data
        $orders = [
            [
                'id' => 1,
                'customer_id' => 1,
                'total_amount' => 52000,
                'status' => 'pending',
                'order_date' => '2025-05-31'
            ],
            [
                'id' => 2,
                'customer_id' => 2,
                'total_amount' => 75000,
                'status' => 'completed',
                'order_date' => '2025-05-31'
            ]
        ];

        return $this->successResponse($orders, 'Orders retrieved successfully');
    }

    /**
     * Store a new order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'customer_id' => 'required|integer',
            'items' => 'required|array',
            'items.*.menu_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        // Simulate order creation
        $order = [
            'id' => 3,
            'customer_id' => $request->input('customer_id'),
            'total_amount' => 45000, // This would be calculated based on items
            'status' => 'pending',
            'order_date' => date('Y-m-d')
        ];

        return $this->successResponse($order, 'Order created successfully', Response::HTTP_CREATED);
    }

    /**
     * Display the specified order.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Simulate finding an order
        $order = [
            'id' => $id,
            'customer_id' => 1,
            'total_amount' => 52000,
            'status' => 'pending',
            'order_date' => '2025-05-31',
            'items' => [
                [
                    'menu_id' => 1,
                    'quantity' => 2,
                    'price' => 25000
                ]
            ]
        ];

        return $this->successResponse($order, 'Order retrieved successfully');
    }

    /**
     * Update the specified order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        // Simulate updating order
        $order = [
            'id' => $id,
            'status' => $request->input('status'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        return $this->successResponse($order, 'Order updated successfully');
    }

    /**
     * Remove the specified order.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Simulate deleting order
        return $this->successResponse(['id' => $id], 'Order cancelled successfully');
    }
}
