<?php
// filepath: /Users/marco/Documents/Projects/marco/BudgetControl-universe/Website/src/Services/PaymentService.php

namespace Mlab\BudetControl\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Customer;

class PaymentService
{
    private string $stripeSecretKey;
    private string $stripePublicKey;
    
    public function __construct()
    {
        $this->stripeSecretKey = $_ENV['STRIPE_SECRET_KEY'] ?? '';
        $this->stripePublicKey = $_ENV['STRIPE_PUBLIC_KEY'] ?? '';
        
        if (!empty($this->stripeSecretKey)) {
            Stripe::setApiKey($this->stripeSecretKey);
        }
    }
    
    public function createPaymentIntent(array $orderData): array
    {
        try {
            // Create customer
            $customer = Customer::create([
                'email' => $orderData['email'],
                'name' => $orderData['first_name'] . ' ' . $orderData['last_name'],
                'address' => [
                    'line1' => $orderData['address'],
                    'city' => $orderData['city'],
                    'postal_code' => $orderData['postal_code'],
                    'country' => $orderData['country']
                ]
            ]);
            
            // Create payment intent
            $paymentIntent = PaymentIntent::create([
                'amount' => $orderData['price'] * 100, // Convert to cents
                'currency' => 'eur',
                'customer' => $customer->id,
                'metadata' => [
                    'product_id' => $orderData['product_id'],
                    'customer_email' => $orderData['email'],
                    'customer_name' => $orderData['first_name'] . ' ' . $orderData['last_name']
                ],
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);
            
            return [
                'success' => true,
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id
            ];
            
        } catch (\Exception $e) {
            error_log("Payment intent creation failed: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Payment setup failed. Please try again.'
            ];
        }
    }
    
    public function verifyPayment(string $paymentIntentId): array
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            
            if ($paymentIntent->status === 'succeeded') {
                return [
                    'success' => true,
                    'payment_intent' => $paymentIntent,
                    'customer_email' => $paymentIntent->metadata->customer_email ?? '',
                    'amount' => $paymentIntent->amount / 100
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Payment not completed'
            ];
            
        } catch (\Exception $e) {
            error_log("Payment verification failed: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Payment verification failed'
            ];
        }
    }
    
    public function getPublicKey(): string
    {
        return $this->stripePublicKey;
    }
}