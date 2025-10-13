<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Booking Request</title>
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
            background: linear-gradient(135deg, #dc3545, #FFD700);
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
            border-left: 4px solid #dc3545;
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
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .footer {
            background: #1a1a1a;
            color: #FFD700;
            padding: 20px;
            text-align: center;
            font-size: 14px;
        }
        .urgent {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚨 New Booking Request</h1>
            <p>Admin Notification - Action Required</p>
        </div>
        
        <div class="content">
            <div class="urgent">
                <strong>⚡ URGENT:</strong> A new celebrity booking request has been submitted and requires your immediate attention.
            </div>
            
            <div class="booking-details">
                <h3 style="margin-top: 0; color: #dc3545;">📋 Booking Information</h3>
                
                <div class="detail-row">
                    <span><strong>Booking Number:</strong></span>
                    <span>{{ $booking->booking_number }}</span>
                </div>
                
                <div class="detail-row">
                    <span><strong>Customer:</strong></span>
                    <span>{{ $booking->customer_name }}</span>
                </div>
                
                <div class="detail-row">
                    <span><strong>Email:</strong></span>
                    <span>{{ $booking->customer_email }}</span>
                </div>
                
                <div class="detail-row">
                    <span><strong>Phone:</strong></span>
                    <span>{{ $booking->customer_phone }}</span>
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
                    <span><strong>Total Amount:</strong></span>
                    <span><strong style="color: #28a745;">${{ number_format($booking->total_amount, 2) }}</strong></span>
                </div>
                
                <div class="detail-row">
                    <span><strong>Submitted:</strong></span>
                    <span>{{ $booking->created_at->format('F j, Y \a\t g:i A') }}</span>
                </div>
            </div>
            
            @if($booking->event_details)
            <h3 style="color: #dc3545;">📝 Event Details</h3>
            <div style="background: #f8f9fa; padding: 15px; border-radius: 5px;">
                {{ $booking->event_details }}
            </div>
            @endif
            
            @if($booking->customer_message)
            <h3 style="color: #dc3545;">💬 Customer Message</h3>
            <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; font-style: italic;">
                "{{ $booking->customer_message }}"
            </div>
            @endif
            
            <div style="text-align: center; margin: 30px 0;">
                <p><strong>Take Action Now:</strong></p>
                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn">👀 Review Booking</a>
                <a href="{{ route('admin.bookings.index') }}" class="btn">📊 All Bookings</a>
            </div>
            
            <div style="background: #e7f3ff; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <h4 style="margin-top: 0; color: #0066cc;">⏰ Next Steps:</h4>
                <ol>
                    <li>Review the booking details carefully</li>
                    <li>Check celebrity availability for the requested date</li>
                    <li>Verify pricing and any special requirements</li>
                    <li>Approve or reject the booking with appropriate notes</li>
                    <li>Customer will be automatically notified of your decision</li>
                </ol>
            </div>
            
            <p><strong>⚠️ Important:</strong> Customer is waiting for your response. Please review and respond within 24-48 hours to maintain excellent service standards.</p>
        </div>
        
        <div class="footer">
            <p><strong>{{ $settings->site_name }} - Admin Panel</strong></p>
            <p>This is an automated notification. Do not reply to this email.</p>
        </div>
    </div>
</body>
</html>