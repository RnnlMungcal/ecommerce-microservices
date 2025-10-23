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
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach ($order['products'] as $product)
                                <li>{{ $product }}</li>
                            @endforeach
                        </ul>
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
