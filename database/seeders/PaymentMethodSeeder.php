<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $paymentMethods = [
            [
                'name' => 'Credit/Debit Card',
                'slug' => 'stripe',
                'description' => 'Secure payment via Stripe - Visa, MasterCard, American Express',
                'icon' => 'fas fa-credit-card',
                'color' => '#635BFF',
                'is_active' => true,
                'processing_fee_percentage' => 2.9,
                'processing_fee_fixed' => 0.30,
                'minimum_amount' => 1.00,
                'instructions' => 'Your payment will be processed securely via Stripe. Please have your card details ready.',
                'sort_order' => 1,
            ],
            [
                'name' => 'PayPal',
                'slug' => 'paypal',
                'description' => 'Pay safely with your PayPal account',
                'icon' => 'fab fa-paypal',
                'color' => '#0070ba',
                'is_active' => true,
                'processing_fee_percentage' => 3.49,
                'processing_fee_fixed' => 0.49,
                'minimum_amount' => 1.00,
                'instructions' => 'You will be redirected to PayPal to complete your payment securely.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Bank Transfer',
                'slug' => 'bank-transfer',
                'description' => 'Direct bank transfer or wire transfer',
                'icon' => 'fas fa-university',
                'color' => '#28a745',
                'is_active' => true,
                'processing_fee_percentage' => 0,
                'processing_fee_fixed' => 0,
                'minimum_amount' => 50.00,
                'instructions' => 'Bank transfer details will be provided after booking confirmation. Please allow 1-3 business days for processing.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Cryptocurrency',
                'slug' => 'crypto',
                'description' => 'Bitcoin, Ethereum and other cryptocurrencies',
                'icon' => 'fab fa-bitcoin',
                'color' => '#f7931a',
                'is_active' => true,
                'processing_fee_percentage' => 1.0,
                'processing_fee_fixed' => 0,
                'minimum_amount' => 10.00,
                'instructions' => 'Cryptocurrency wallet address will be provided after booking confirmation. Transaction must be confirmed on the blockchain.',
                'sort_order' => 4,
            ],
            [
                'name' => 'Cash Payment',
                'slug' => 'cash',
                'description' => 'Pay in cash at the event location',
                'icon' => 'fas fa-money-bill-wave',
                'color' => '#20c997',
                'is_active' => true,
                'processing_fee_percentage' => 0,
                'processing_fee_fixed' => 0,
                'minimum_amount' => 100.00,
                'maximum_amount' => 10000.00,
                'instructions' => 'Cash payment must be made in full at the event location. Please bring exact amount or change will be provided.',
                'sort_order' => 5,
            ],
            [
                'name' => 'Check Payment',
                'slug' => 'check',
                'description' => 'Payment by personal or business check',
                'icon' => 'fas fa-money-check',
                'color' => '#6f42c1',
                'is_active' => false,
                'processing_fee_percentage' => 0,
                'processing_fee_fixed' => 5.00,
                'minimum_amount' => 100.00,
                'instructions' => 'Check must be made payable to the agency and received at least 7 days before the event. Check processing fee applies.',
                'sort_order' => 6,
            ],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::updateOrCreate(
                ['slug' => $method['slug']],
                $method
            );
        }
    }
}
