<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Approved</title>
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
            background: linear-gradient(135deg, #28a745, #20c997);
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
            border-left: 4px solid #28a745;
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
            color: #28a745;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 10px 5px;
        }
        .btn-secondary {
            background: #6c757d;
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
            background: #28a745;
            color: white;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .success-box {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
        }
        .admin-notes {
            background: #e9ecef;
            border-left: 4px solid #6c757d;
            padding: 15px;
            margin: 15px 0;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Booking Approved!</h1>
            <p>Great news! Your celebrity booking has been confirmed</p>
        </div>
        
        <div class="content">
            <div class="success-box">
                <h2 style="margin: 0 0 10px 0; color: #155724;">✅ Congratulations!</h2>
                <p style="margin: 0; font-size: 16px;">Your booking request has been <strong>APPROVED</strong> and confirmed!</p>
            </div>

            <p>Dear {{ $booking->customer_name }},</p>
            
            <p>Fantastic news! We're thrilled to inform you that your celebrity booking request has been <strong>approved and confirmed</strong>. Get ready for an incredible experience!</p>
            
            <div class="booking-details">
                <h3 style="margin-top: 0; color: #28a745;">📋 Confirmed Booking Details</h3>
                
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
                    <span><span class="status">✅ Approved</span></span>
                </div>
                
                <div class="detail-row">
                    <span><strong>Total Amount:</strong></span>
                    <span>${{ number_format($booking->total_amount, 2) }}</span>
                </div>
            </div>

            @if($booking->admin_notes)
            <div class="admin-notes">
                <h4 style="margin-top: 0; color: #6c757d;">📝 Message from Our Team:</h4>
                <p style="margin: 0;">"{{ $booking->admin_notes }}"</p>
            </div>
            @endif
            
            {{-- <h3 style="color: #28a745;">🎯 Next Steps</h3>
            <ol>
                <li><strong>Payment Processing:</strong> Complete your payment using the method you selected during booking</li>
                <li><strong>Event Coordination:</strong> Our event coordinator will contact you within 24 hours to finalize details</li>
                <li><strong>Special Requirements:</strong> We'll discuss any special requests or logistics for your event</li>
                <li><strong>Final Confirmation:</strong> You'll receive final event details 48 hours before your event</li>
            </ol> --}}

            {{-- <h3 style="color: #28a745;">💰 Payment Information</h3>
            <p>
                <strong>Payment Status:</strong> {{ ucfirst($booking->payment_status) }}<br>
                <strong>Payment Method:</strong> {{ ucfirst(str_replace('-', ' ', $booking->payment_method ?? 'To be determined')) }}<br>
                @if($booking->payment_status === 'pending')
                <em>Our team will contact you with payment instructions within 24 hours.</em>
                @endif
            </p> --}}

            @if($booking->event_details)
            <h3 style="color: #28a745;">🎪 Event Details</h3>
            <div style="background: #f8f9fa; padding: 15px; border-radius: 5px;">
                {{ $booking->event_details }}
            </div>
            @endif
            
            <div style="text-align: center; margin: 30px 0;">
                <p><strong>Need assistance or have questions?</strong></p>
                <a href="mailto:{{ $settings->site_email }}" class="btn">📧 Contact Us</a>
                <a href="tel:{{ $settings->site_phone }}" class="btn btn-secondary">📞 Call Us</a>
            </div>
            
            <h3 style="color: #28a745;">⚠️ Important Reminders</h3>
            <ul>
                <li><strong>Arrival Time:</strong> Please arrive 15 minutes before your scheduled event time</li>
                <li><strong>Contact Information:</strong> Ensure we have your correct phone number for day-of coordination</li>
                <li><strong>Special Requests:</strong> Any last-minute changes must be confirmed 48 hours in advance</li>
                <li><strong>Cancellation Policy:</strong> Please review our cancellation terms and conditions</li>
                <li><strong>Keep This Email:</strong> Save this confirmation for your records and event day reference</li>
            </ul>
            
            <div style="background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <p style="margin: 0;"><strong>🌟 We're excited to make your event unforgettable!</strong><br>
                Thank you for choosing {{ $settings->site_name }}. We can't wait to deliver an amazing experience for you and your guests.</p>
            </div>
            
            <p>Warm regards,<br>
            <strong>{{ $settings->site_name }} Team</strong><br>
            <em>Making dreams come true, one event at a time</em></p>
        </div>
        
        <div class="footer">
            <p><strong>{{ $settings->site_name }}</strong></p>
            <p>{{ $settings->site_email }} | {{ $settings->site_phone }}</p>
            <p>{{ $settings->site_address }}</p>
        </div>
    </div>
</body>
</html>
