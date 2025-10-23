<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use GuzzleHttp\Client;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json(Order::all());
    }

    public function store(Request $request)
    {
        $products = $request->input('products');
        $client = new Client();

        // Validate each product via Catalog Service
        foreach ($products as $id) {
            $response = $client->get(env('CATALOG_URL') . "/products/{$id}");
            if ($response->getStatusCode() !== 200) {
                return response()->json(['error' => "Invalid product ID: $id"], 400);
            }
        }

        $order = Order::create([
            'products' => json_encode($products),
            'total' => $request->input('total', 0),
            'status' => 'confirmed',
        ]);

        // Send email notification
        $client->post(env('EMAIL_URL') . '/send', [
            'json' => [
                'order_id' => $order->id,
                'products' => $products,
                'total' => $order->total,
            ]
        ]);

        return response()->json($order);
    }
}
