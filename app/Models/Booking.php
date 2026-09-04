<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_number',
        'user_id',
        'celebrity_id',
        'service_type_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_message',
        'event_date',
        'event_time',
        'event_location',
        'event_details',
        'event_description',
        'duration_hours',
        'base_price',
        'additional_charges',
        'total_amount',
        'status',
        'payment_status',
        'payment_method',
        'payment_reference',
        'crypto_wallet_id',
        'payment_tx_hash',
        'payment_proof_image',
        'payment_notes',
        'payment_submitted_at',
        'payment_reviewed_at',
        'special_requests',
        'admin_notes',
        'approved_at',
        'rejected_at',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'event_time' => 'datetime',
        'base_price' => 'decimal:2',
        'additional_charges' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'payment_submitted_at' => 'datetime',
        'payment_reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($booking) {
            if (!$booking->booking_number) {
                $booking->booking_number = 'BK-' . strtoupper(uniqid());
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function celebrity()
    {
        return $this->belongsTo(Celebrity::class);
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function paymentMethodModel()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method', 'slug');
    }

    public function cryptoWallet()
    {
        return $this->belongsTo(CryptoWallet::class);
    }

    public function getPaymentProofImageUrlAttribute()
    {
        return $this->payment_proof_image ? asset('storage/' . $this->payment_proof_image) : null;
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'pending_payment_verification' => 'info',
            'approved' => 'success',
            'rejected' => 'danger',
            'completed' => 'info',
            'cancelled' => 'dark',
            default => 'secondary'
        };
    }

    public function getPaymentStatusColorAttribute()
    {
        return match($this->payment_status) {
            'pending' => 'warning',
            'pending_verification' => 'info',
            'paid' => 'success',
            'failed' => 'danger',
            'refunded' => 'info',
            default => 'secondary'
        };
    }
}
