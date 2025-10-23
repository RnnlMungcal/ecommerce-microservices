<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Validator;

class EmailController extends Controller
{
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer',
            'send_to' => 'required|email',
            'products' => 'required|array|min:1',
            'total' => 'required|numeric',
            // Optional: validate nested product structure
            'products.*.id' => 'required|integer',
            'products.*.name' => 'required|string',
            'products.*.price' => 'required|numeric',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.subtotal' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $validated = $validator->validated();

        // Send email
        Mail::to($validated['send_to'])->send(new OrderConfirmationMail($validated));

        return response()->json(['status' => 'Email sent']);
    }
}
