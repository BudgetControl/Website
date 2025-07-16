<?php
// filepath: /Users/marco/Documents/Projects/marco/BudgetControl-universe/Website/src/Http/Controller/CheckoutController.php

namespace Mlab\BudetControl\Http\Controller;

use Mlab\BudetControl\Services\PaymentService;
use Mlab\BudetControl\Services\LicenseService;
use Mlab\BudetControl\Services\EmailService;
use Mlab\BudetControl\View\Checkout;
use Mlab\BudetControl\View\CheckoutSuccess;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CheckoutController extends Controller
{
    private PaymentService $paymentService;
    private LicenseService $licenseService;
    
    public function __construct()
    {
        $this->paymentService = new PaymentService();
        $emailService = new EmailService();
        $this->licenseService = new LicenseService($emailService);
    }
    
    public function index(Request $request, Response $response, array $args)
    {
        $view = new Checkout();
        
        $data = [
            'seo' => ['title' => 'Checkout - Budget Control Pro'],
            'price' => $this->getProPrice(),
            'stripe_public_key' => $this->paymentService->getPublicKey()
        ];
        
        $view->render($data);
    }
    
    public function createPaymentIntent(Request $request, Response $response, array $args)
    {
        $body = $request->getParsedBody();
        
        // Validate required fields
        $requiredFields = ['first_name', 'last_name', 'email', 'address', 'city', 'postal_code', 'country'];
        foreach ($requiredFields as $field) {
            if (empty($body[$field])) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => "Field '{$field}' is required"
                ], 400);
            }
        }
        
        // Validate email
        if (!filter_var($body['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Invalid email format'
            ], 400);
        }
        
        $orderData = [
            'product_id' => $body['product_id'] ?? 'budget-control-pro',
            'price' => $this->getProPrice(),
            'first_name' => $body['first_name'],
            'last_name' => $body['last_name'],
            'email' => $body['email'],
            'company' => $body['company'] ?? '',
            'address' => $body['address'],
            'city' => $body['city'],
            'postal_code' => $body['postal_code'],
            'country' => $body['country']
        ];
        
        $result = $this->paymentService->createPaymentIntent($orderData);
        
        if ($result['success']) {
            // Store order data in session for later use
            session_start();
            $_SESSION['checkout_order_data'] = $orderData;
            $_SESSION['payment_intent_id'] = $result['payment_intent_id'];
        }
        
        return $this->jsonResponse($result);
    }
    
    public function success(Request $request, Response $response, array $args)
    {
        session_start();
        
        // Verify payment was successful
        $paymentIntentId = $_SESSION['payment_intent_id'] ?? '';
        $orderData = $_SESSION['checkout_order_data'] ?? [];
        
        if (empty($paymentIntentId) || empty($orderData)) {
            // Redirect to checkout if no session data
            header('Location: /checkout');
            exit;
        }
        
        $paymentResult = $this->paymentService->verifyPayment($paymentIntentId);
        
        if (!$paymentResult['success']) {
            // Redirect to checkout with error
            header('Location: /checkout?error=payment_failed');
            exit;
        }
        
        // Create license
        $licenseResult = $this->licenseService->createLicense($orderData);
        
        if (!$licenseResult['success']) {
            error_log("License creation failed after successful payment: " . $licenseResult['message']);
            // Still show success page but log the error
        }
        
        // Generate order ID
        $orderId = 'BC-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        
        $view = new CheckoutSuccess();
        $data = [
            'seo' => ['title' => 'Purchase Successful - Budget Control Pro'],
            'order_id' => $orderId,
            'purchase_date' => date('F j, Y'),
            'amount' => $this->getProPrice(),
            'email' => $orderData['email']
        ];
        
        // Clear session data
        unset($_SESSION['checkout_order_data']);
        unset($_SESSION['payment_intent_id']);
        
        $view->render($data);
    }
    
    private function getProPrice(): int
    {
        return 99; // €99 for pro license
    }
    
    private function jsonResponse(array $data, int $statusCode = 200): Response
    {
        $response = new \GuzzleHttp\Psr7\Response($statusCode);
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }
}