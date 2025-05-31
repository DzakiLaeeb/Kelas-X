<?php

namespace App\Http\Controllers;

class ApiController extends Controller
{
    /**
     * Get welcome message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function welcome()
    {
        return response()->json([
            'message' => 'Welcome to Lumen API',
            'status' => 'success',
            'timestamp' => date('Y-m-d H:i:s'),
            'version' => app()->version()
        ]);
    }
}
