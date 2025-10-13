<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #1a1a1a, #FFD700);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px 20px;
        }
        .booking-details {
            background: #f8f9fa;
            border-left: 4px solid #FFD700;
            padding: 20px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }
        .detail-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 18px;
            color: #B8860B;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #FFD700;
            color: #1a1a1a;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 10px 5px;
        }
        .footer {
            background: #1a1a1a;
            color: #FFD700;
            padding: 20px;
            text-align: center;
            font-size: 14px;
        }
        .status {
            display: inline-block;
            padding: 5px 15px;
            background: #ffc107;
            color: #1a1a1a;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🌟 Booking Confirmation</h1>
            <p>Thank you for choosing {{ $settings->site_name }}</p>
        </div>
        
        <div class="content">
            <p>Dear {{ $booking->customer_name }},</p>
            
            <p>We have received your celebrity booking request and are excited to help you create an unforgettable experience! Here are the details of your booking:</p>
            
            <div class="booking-details">
                <h3 style="margin-top: 0; color: #B8860B;">📋 Booking Details</h3>
                
                <div class="detail-row">
                    <span><strong>Booking Number:</strong></span>
                    <span>{{ $booking->booking_number }}</span>
                </div>
                
                <div class="detail-row">
                    <span><strong>Celebrity:</strong></span>
                    <span>{{ $booking->celebrity->name }}</span>
                </div>
                
                <div class="detail-row">
                    <span><strong>Service:</strong></span>
                    <span>{{ $booking->serviceType->name }}</span>
                </div>
                
                <div class="detail-row">
                    <span><strong>Event Date:</strong></span>
                    <span>{{ $booking->event_date->format('F j, Y \a\t g:i A') }}</span>
                </div>
                
                <div class="detail-row">
                    <span><strong>Duration:</strong></span>
                    <span>{{ $booking->duration_hours }} hour{{ $booking->duration_hours > 1 ? 's' : '' }}</span>
                </div>
                
                @if($booking->event_location)
                <div class="detail-row">
                    <span><strong>Location:</strong></span>
                    <span>{{ $booking->event_location }}</span>
                </div>
                @endif
                
                <div class="detail-row">
                    <span><strong>Status:</strong></span>
                    <span><span class="status">{{ ucfirst($booking->status) }}</span></span>
                </div>
                
                <div class="detail-row">
                    <span><strong>Total Amount:</strong></span>
                    <span>${{ number_format($booking->total_amount, 2) }}</span>
                </div>
            </div>
            
            <h3 style="color: #B8860B;">📝 What Happens Next?</h3>
            <ol>
                <li><strong>Review Process:</strong> Our team will review your booking request within 24-48 hours</li>
                <li><strong>Confirmation:</strong> You'll receive an email confirmation once your booking is approved</li>
                <li><strong>Payment:</strong> Payment instructions will be provided upon approval</li>
                <li><strong>Event Coordination:</strong> Our team will coordinate all event details with you</li>
            </ol>
            
            @if($booking->customer_message)
            <h3 style="color: #B8860B;">💬 Your Message</h3>
            <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; font-style: italic;">
                "{{ $booking->customer_message }}"
            </div>
            @endif
            
            <div style="text-align: center; margin: 30px 0;">
                <p><strong>Questions? Need to make changes?</strong></p>
                <a href="mailto:{{ $settings->site_email }}" class="btn">📧 Contact Us</a>
                <a href="tel:{{ $settings->site_phone }}" class="btn">📞 Call Us</a>
            </div>
            
            <p><strong>Important Notes:</strong></p>
            <ul>
                <li>This booking is subject to celebrity availability and approval</li>
                <li>Final pricing may include additional fees for special requests or travel</li>
                <li>Cancellation policy applies as per our terms and conditions</li>
                <li>Please keep this booking reference number for your records</li>
            </ul>
            
            <p>Thank you for choosing {{ $settings->site_name }}. We look forward to creating an amazing experience for you!</p>
            
            <p>Best regards,<br>
            <strong>{{ $settings->site_name }} Team</strong></p>
        </div>
        
        <div class="footer">
            <p><strong>{{ $settings->site_name }}</strong></p>
            <p>{{ $settings->site_email }} | {{ $settings->site_phone }}</p>
            <p>{{ $settings->site_address }}</p>
        </div>
    </div>
</body>
</html>