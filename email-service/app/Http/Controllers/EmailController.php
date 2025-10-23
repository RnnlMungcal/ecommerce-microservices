<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail;

class EmailController extends Controller
{
    public function send(Request $request)
    {
        $order = $request->all();

        Mail::to('customer@example.com')->send(new OrderConfirmationMail($order));

        return response()->json(['status' => 'Email sent']);
    }
}
