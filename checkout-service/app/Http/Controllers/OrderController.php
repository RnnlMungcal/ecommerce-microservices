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
        $productIds = $request->input('products');
        $client = new Client();
        $productDetails = [];
        $total = 0;

        // Fetch each product from Catalog Service
        foreach ($productIds as $id) {
            $response = $client->get(env('CATALOG_URL') . "/products/{$id}");
            if ($response->getStatusCode() !== 200) {
                return response()->json(['error' => "Invalid product ID: $id"], 400);
            }

            $product = json_decode($response->getBody(), true);
            $productDetails[] = $product;
            $total += $product['price'];
        }

        // Create order
        $order = Order::create([
            'products' => json_encode($productDetails),
            'total' => $total,
            'status' => 'confirmed',
        ]);

        // Send email with product names
        $client->post(env('EMAIL_URL') . '/send', [
            'json' => [
                'order_id' => $order->id,
                'products' => $productDetails,
                'total' => $total,
            ]
        ]);

        return response()->json([
            'order_id' => $order->id,
            'products' => $productDetails,
            'total' => $total,
            'status' => 'Email sent to customer',
        ]);
    }
}
