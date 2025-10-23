<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json(Order::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|integer',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $validated = $validator->validated();

        $products = $validated['products'];
        $email = $validated['email'];

        $client = new Client();
        $productDetails = [];
        $total = 0;

        foreach ($products as $item) {
            $productId = $item['id'] ?? null;
            $quantity = $item['quantity'] ?? 1;

            if (!$productId) {
                return response()->json(['error' => 'Invalid product payload'], 400);
            }

            try {
                $response = $client->get(env('CATALOG_URL') . "/products/{$productId}");
                $product = json_decode($response->getBody(), true);

                if (!$product || !isset($product['price'])) {
                    return response()->json(['error' => "Invalid product data for ID: $productId"], 400);
                }

                $productTotal = $product['price'] * $quantity;
                $total += $productTotal;

                $productDetails[] = [
                    'id' => $productId,
                    'name' => $product['name'] ?? 'Unknown Product',
                    'price' => $product['price'],
                    'quantity' => $quantity,
                    'subtotal' => $productTotal,
                ];
            } catch (\Exception $e) {
                Log::error("Error fetching product {$productId}: " . $e->getMessage());
                return response()->json(['error' => "Failed to fetch product ID: $productId"], 500);
            }
        }

        // Create order
        $order = Order::create([
            'products' => json_encode($productDetails),
            'total' => $total,
            'status' => 'confirmed',
        ]);

        // Send email notification
        try {
            $client->post(env('EMAIL_URL') . '/send', [
                'json' => [
                    'order_id' => $order->id,
                    'send_to' => $email,
                    'products' => $productDetails,
                    'total' => $total,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error("Error sending email for order {$order->id}: " . $e->getMessage());
        }

        return response()->json([
            'order_id' => $order->id,
            'products' => $productDetails,
            'total' => $total,
            'status' => 'Order confirmed and email sent',
        ]);
    }
}
