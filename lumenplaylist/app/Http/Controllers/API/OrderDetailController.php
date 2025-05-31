<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderDetailController extends BaseController
{
    /**
     * Display order details for a specific order.
     *
     * @param  int  $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($orderId)
    {
        // Simulate retrieving order details
        $orderDetails = [
            [
                'id' => 1,
                'order_id' => $orderId,
                'menu_id' => 1,
                'quantity' => 2,
                'price' => 25000,
                'subtotal' => 50000
            ],
            [
                'id' => 2,
                'order_id' => $orderId,
                'menu_id' => 3,
                'quantity' => 1,
                'price' => 5000,
                'subtotal' => 5000
            ]
        ];

        return $this->successResponse($orderDetails, 'Order details retrieved successfully');
    }

    /**
     * Add item to order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $orderId)
    {
        $this->validate($request, [
            'menu_id' => 'required|integer',
            'quantity' => 'required|integer|min:1'
        ]);

        // Simulate adding item to order
        $orderDetail = [
            'id' => 3,
            'order_id' => $orderId,
            'menu_id' => $request->input('menu_id'),
            'quantity' => $request->input('quantity'),
            'price' => 25000, // This would be fetched from menu
            'subtotal' => 25000 * $request->input('quantity')
        ];

        return $this->successResponse($orderDetail, 'Item added to order successfully', Response::HTTP_CREATED);
    }

    /**
     * Update order item quantity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $orderId
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $orderId, $id)
    {
        $this->validate($request, [
            'quantity' => 'required|integer|min:1'
        ]);

        // Simulate updating order item
        $orderDetail = [
            'id' => $id,
            'order_id' => $orderId,
            'quantity' => $request->input('quantity'),
            'subtotal' => 25000 * $request->input('quantity') // Recalculate subtotal
        ];

        return $this->successResponse($orderDetail, 'Order item updated successfully');
    }

    /**
     * Remove item from order.
     *
     * @param  int  $orderId
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($orderId, $id)
    {
        // Simulate removing item from order
        return $this->successResponse(
            ['order_id' => $orderId, 'item_id' => $id],
            'Item removed from order successfully'
        );
    }
}
