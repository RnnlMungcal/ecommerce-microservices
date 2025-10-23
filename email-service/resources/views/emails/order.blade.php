<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Order Confirmation</title>
</head>

<body style="font-family: Arial, sans-serif; color: #333; background-color: #f9f9f9; padding: 20px;">
    <div
        style="max-width: 600px; margin: 0 auto; background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <div style="background-color: #4CAF50; color: #fff; padding: 16px; text-align: center;">
            <h2 style="margin: 0;">Order Confirmation</h2>
        </div>
        <div style="padding: 24px;">
            <p>Hi {{ $order['customer_name'] ?? 'Customer' }},</p>
            <p>Thank you for your order! Here are your order details:</p>

            <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse: collapse;">
                <tr>
                    <td style="border-bottom: 1px solid #ddd;"><strong>Order ID:</strong></td>
                    <td style="border-bottom: 1px solid #ddd;">{{ $order['order_id'] }}</td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #ddd;"><strong>Total:</strong></td>
                    <td style="border-bottom: 1px solid #ddd;">${{ number_format($order['total'], 2) }}</td>
                </tr>
                <tr>
                    <td valign="top"><strong>Products:</strong></td>
                    <td>
                        <table width="100%" cellpadding="6" cellspacing="0" style="border-collapse: collapse;">
                            <thead>
                                <tr style="background-color: #f2f2f2;">
                                    <th align="left" style="border-bottom: 1px solid #ddd;">Product</th>
                                    <th align="center" style="border-bottom: 1px solid #ddd;">Quantity</th>
                                    <th align="right" style="border-bottom: 1px solid #ddd;">Price</th>
                                    <th align="right" style="border-bottom: 1px solid #ddd;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order['products'] as $product)
                                <tr>
                                    <td style="border-bottom: 1px solid #eee;">{{ $product['name'] }}</td>
                                    <td align="center" style="border-bottom: 1px solid #eee;">{{ $product['quantity'] }}</td>
                                    <td align="right" style="border-bottom: 1px solid #eee;">${{ number_format($product['price'], 2) }}</td>
                                    <td align="right" style="border-bottom: 1px solid #eee;">${{ number_format($product['subtotal'], 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>

            <p style="margin-top: 20px;">We’ll notify you once your order has shipped.</p>
            <p>Thank you for shopping with us!</p>

            <p style="margin-top: 40px; font-size: 12px; color: #777;">&copy; {{ date('Y') }}
                {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
