<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Update</title>
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
            background: linear-gradient(135deg, #dc3545, #c82333);
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
            background: #007bff;
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
            background: #dc3545;
            color: white;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .alert-box {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
        }
        .admin-notes {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 15px 0;
        }
        .alternatives {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Booking Update</h1>
            <p>Important information about your booking request</p>
        </div>
        
        <div class="content">
            <div class="alert-box">
                <h2 style="margin: 0 0 10px 0; color: #721c24;">📝 Booking Status Update</h2>
                <p style="margin: 0; font-size: 16px;">We regret to inform you that your booking request could not be approved at this time.</p>
            </div>

            <p>Dear {{ $booking->customer_name }},</p>
            
            <p>Thank you for your interest in {{ $settings->site_name }} and for choosing us for your special event. After careful review, we unfortunately cannot approve your booking request for the following:</p>
            
            <div class="booking-details">
                <h3 style="margin-top: 0; color: #dc3545;">📋 Booking Request Details</h3>
                
                <div class="detail-row">
                    <span><strong>Booking Number:</strong></span>
                    <span>{{ $booking->booking_number }}</span>
                </div>
                
                <div class="detail-row">
                    <span><strong>Celebrity Requested:</strong></span>
                    <span>{{ $booking->celebrity->name }}</span>
                </div>
                
                <div class="detail-row">
                    <span><strong>Service Requested:</strong></span>
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
                    <span><span class="status">❌ Not Approved</span></span>
                </div>
            </div>

            @if($booking->admin_notes)
            <div class="admin_notes">
                <h4 style="margin-top: 0; color: #856404;">💬 Reason for Status:</h4>
                <p style="margin: 0;">"{{ $booking->admin_notes }}"</p>
            </div>
            @endif
            
            <h3 style="color: #007bff;">🔄 What You Can Do Next</h3>
            <div class="alternatives">
                <h4 style="margin-top: 0; color: #0c5460;">We'd love to help you find alternatives!</h4>
                <ul style="margin: 10px 0;">
                    <li><strong>Different Date:</strong> The celebrity might be available on different dates</li>
                    <li><strong>Alternative Celebrity:</strong> We can suggest similar celebrities who are available</li>
                    <li><strong>Modified Service:</strong> Consider a different service type that might be available</li>
                    <li><strong>Waitlist:</strong> Join our waitlist in case of cancellations</li>
                </ul>
            </div>

            <h3 style="color: #007bff;">💡 Common Reasons for Booking Changes</h3>
            <ul>
                <li><strong>Celebrity Availability:</strong> The celebrity may have conflicting commitments</li>
                <li><strong>Location Constraints:</strong> Travel or venue limitations</li>
                <li><strong>Service Compatibility:</strong> The requested service may not be available for this celebrity</li>
                <li><strong>Timing Issues:</strong> Scheduling conflicts or insufficient advance notice</li>
                <li><strong>Special Requirements:</strong> Unable to accommodate specific requests</li>
            </ul>

            <h3 style="color: #007bff;">🎯 Let's Find You the Perfect Experience</h3>
            <p>While this particular booking couldn't be approved, we're committed to helping you create an amazing event. Our experienced team has many alternatives and can work with you to find a solution that exceeds your expectations.</p>

            <div style="text-align: center; margin: 30px 0;">
                <p><strong>Ready to explore alternatives?</strong></p>
                <a href="mailto:{{ $settings->site_email }}?subject=Alternative Booking Options - {{ $booking->booking_number }}" class="btn">📧 Discuss Alternatives</a>
                <a href="tel:{{ $settings->site_phone }}" class="btn btn-secondary">📞 Call Us Now</a>
            </div>

            <h3 style="color: #007bff;">🕐 Booking Again</h3>
            <p>You're welcome to submit a new booking request at any time. Here are some tips for a successful booking:</p>
            <ul>
                <li><strong>Flexible Dates:</strong> Provide multiple date options when possible</li>
                <li><strong>Advance Notice:</strong> Book as far in advance as possible</li>
                <li><strong>Clear Requirements:</strong> Be specific about your event needs</li>
                <li><strong>Budget Considerations:</strong> Discuss budget openly to find suitable options</li>
            </ul>

            <div style="background: #e9ecef; border-left: 4px solid #6c757d; padding: 15px; margin: 20px 0;">
                <p style="margin: 0;"><strong>📞 Immediate Assistance Available</strong><br>
                If you have questions about this decision or want to discuss alternatives immediately, our team is standing by to help you find the perfect celebrity experience.</p>
            </div>
            
            <p>We sincerely apologize for any disappointment and genuinely appreciate your understanding. We're here to help you create an unforgettable event, and we're confident we can find an amazing alternative that will exceed your expectations.</p>
            
            <p>Thank you for choosing {{ $settings->site_name }}. We look forward to working with you to create something truly special.</p>
            
            <p>Warm regards,<br>
            <strong>{{ $settings->site_name }} Team</strong><br>
            <em>Committed to making your event extraordinary</em></p>
        </div>
        
        <div class="footer">
            <p><strong>{{ $settings->site_name }}</strong></p>
            <p>{{ $settings->site_email }} | {{ $settings->site_phone }}</p>
            <p>{{ $settings->site_address }}</p>
        </div>
    </div>
</body>
</html>
