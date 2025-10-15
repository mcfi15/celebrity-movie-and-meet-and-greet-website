<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'is_active',
        'sort_order',
        'processing_fee_percentage',
        'processing_fee_fixed',
        'minimum_amount',
        'maximum_amount',
        'instructions',
        'api_key',
        'api_secret',
        'webhook_url',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'processing_fee_percentage' => 'decimal:2',
        'processing_fee_fixed' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'maximum_amount' => 'decimal:2',
        'settings' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($paymentMethod) {
            if (empty($paymentMethod->slug)) {
                $paymentMethod->slug = Str::slug($paymentMethod->name);
            }
            
            if (is_null($paymentMethod->sort_order)) {
                $paymentMethod->sort_order = static::max('sort_order') + 1;
            }
        });

        static::updating(function ($paymentMethod) {
            if ($paymentMethod->isDirty('name') && empty($paymentMethod->slug)) {
                $paymentMethod->slug = Str::slug($paymentMethod->name);
            }
        });
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Relationships
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'payment_method', 'slug');
    }

    // Accessors
    public function getIconHtmlAttribute()
    {
        if (str_starts_with($this->icon, 'fa')) {
            return '<i class="' . $this->icon . '" style="color: ' . $this->color . ';"></i>';
        }
        
        return '<img src="' . $this->icon . '" alt="' . $this->name . '" class="payment-icon" style="width: 24px; height: 24px;">';
    }

    public function getFormattedProcessingFeeAttribute()
    {
        $fee = '';
        
        if ($this->processing_fee_percentage > 0) {
            $fee .= $this->processing_fee_percentage . '%';
        }
        
        if ($this->processing_fee_fixed > 0) {
            if (!empty($fee)) {
                $fee .= ' + ';
            }
            $fee .= '$' . number_format($this->processing_fee_fixed, 2);
        }
        
        return $fee ?: 'No fee';
    }

    // Methods
    public function calculateProcessingFee($amount)
    {
        $percentageFee = ($amount * $this->processing_fee_percentage) / 100;
        $totalFee = $percentageFee + $this->processing_fee_fixed;
        
        return round($totalFee, 2);
    }

    public function isAmountValid($amount)
    {
        if ($this->minimum_amount && $amount < $this->minimum_amount) {
            return false;
        }
        
        if ($this->maximum_amount && $amount > $this->maximum_amount) {
            return false;
        }
        
        return true;
    }

    public function getAmountLimitsText()
    {
        if ($this->minimum_amount && $this->maximum_amount) {
            return 'Min: $' . number_format($this->minimum_amount, 2) . ' - Max: $' . number_format($this->maximum_amount, 2);
        } elseif ($this->minimum_amount) {
            return 'Min: $' . number_format($this->minimum_amount, 2);
        } elseif ($this->maximum_amount) {
            return 'Max: $' . number_format($this->maximum_amount, 2);
        }
        
        return 'No limits';
    }

    public function getAmountLimitsTextAttribute()
    {
        return $this->getAmountLimitsText();
    }
}