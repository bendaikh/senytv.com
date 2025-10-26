<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\Setting;

class SenyProPaymentService
{
    private string $apiKey;
    private string $apiSecret;
    private string $apiBaseUrl;
    private bool $enabled;

    public function __construct()
    {
        // Read from database settings first, fallback to config/env
        $this->apiKey = Setting::getSetting('senypro_api_key') ?? config('services.senypro.api_key', '');
        $this->apiSecret = Setting::getSetting('senypro_api_secret') ?? config('services.senypro.api_secret', '');
        $this->apiBaseUrl = Setting::getSetting('senypro_api_base_url') ?? config('services.senypro.api_base_url', 'https://senypro.com/api/v1');
        $this->enabled = (bool) Setting::getSetting('senypro_enabled', '1');
    }

    /**
     * Check if payment gateway is enabled
     */
    public function isEnabled(): bool
    {
        return $this->enabled && !empty($this->apiKey) && !empty($this->apiSecret);
    }

    /**
     * Create a new order and get payment URL
     * 
     * @param array $orderData
     * @return array
     */
    public function createOrder(array $orderData): array
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-API-Key' => $this->apiKey,
                'X-API-Secret' => $this->apiSecret,
            ])->post($this->apiBaseUrl . '/orders', $orderData);

            $result = $response->json();
            $httpCode = $response->status();

            // Log the response for debugging
            Log::info('SenyPro Create Order Response', [
                'status' => $httpCode,
                'response' => $result,
                'order_data' => $orderData
            ]);

            if ($httpCode === 201 && $result['success']) {
                return [
                    'success' => true,
                    'request_id' => $result['request_id'],
                    'payment_url' => $result['data']['payment_url'],
                    'transaction_number' => $result['data']['transaction_number'],
                    'transaction_id' => $result['data']['transaction_id'],
                    'order_id' => $result['data']['order_id'],
                    'payment_id' => $result['data']['payment_id'],
                    'amount' => $result['data']['amount'],
                    'currency' => $result['data']['currency'],
                    'status' => $result['data']['status'],
                ];
            }

            // Handle error responses
            $errorMessage = $result['message'] ?? 'Payment failed';
            $errorCode = $result['error_code'] ?? 'UNKNOWN_ERROR';
            
            Log::error('SenyPro Create Order Failed', [
                'error_code' => $errorCode,
                'message' => $errorMessage,
                'errors' => $result['errors'] ?? []
            ]);

            return [
                'success' => false,
                'message' => $errorMessage,
                'error_code' => $errorCode,
                'errors' => $result['errors'] ?? []
            ];
        } catch (\Exception $e) {
            Log::error('SenyPro API Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred while processing your payment. Please try again.',
                'error_code' => 'SYSTEM_ERROR'
            ];
        }
    }

    /**
     * Check order status
     * 
     * @param string $requestId
     * @return array
     */
    public function getOrderStatus(string $requestId): array
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-API-Key' => $this->apiKey,
                'X-API-Secret' => $this->apiSecret,
            ])->get($this->apiBaseUrl . '/orders/' . $requestId);

            $result = $response->json();
            $httpCode = $response->status();

            Log::info('SenyPro Get Order Status Response', [
                'status' => $httpCode,
                'response' => $result,
                'request_id' => $requestId
            ]);

            if ($httpCode === 200 && $result['success']) {
                return [
                    'success' => true,
                    'data' => $result['data']
                ];
            }

            return [
                'success' => false,
                'message' => $result['message'] ?? 'Failed to retrieve order status'
            ];
        } catch (\Exception $e) {
            Log::error('SenyPro Get Order Status Exception', [
                'message' => $e->getMessage(),
                'request_id' => $requestId
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred while checking order status.'
            ];
        }
    }

    /**
     * List transactions with optional filters
     * 
     * @param array $filters
     * @return array
     */
    public function listTransactions(array $filters = []): array
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-API-Key' => $this->apiKey,
                'X-API-Secret' => $this->apiSecret,
            ])->get($this->apiBaseUrl . '/transactions', $filters);

            $result = $response->json();
            $httpCode = $response->status();

            Log::info('SenyPro List Transactions Response', [
                'status' => $httpCode,
                'filters' => $filters
            ]);

            if ($httpCode === 200 && $result['success']) {
                return [
                    'success' => true,
                    'data' => $result['data']
                ];
            }

            return [
                'success' => false,
                'message' => $result['message'] ?? 'Failed to retrieve transactions'
            ];
        } catch (\Exception $e) {
            Log::error('SenyPro List Transactions Exception', [
                'message' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred while retrieving transactions.'
            ];
        }
    }

    /**
     * Generate a unique external order ID
     * 
     * @return string
     */
    public static function generateOrderId(): string
    {
        return 'SENYTV-ORDER-' . time() . '-' . strtoupper(substr(uniqid(), -6));
    }

    /**
     * Prepare order data for SenyPro API
     * 
     * @param array $data
     * @return array
     */
    public function prepareOrderData(array $data): array
    {
        return [
            'external_order_id' => $data['external_order_id'] ?? self::generateOrderId(),
            'amount' => (float) $data['amount'],
            'currency' => $data['currency'] ?? 'USD',
            'customer' => [
                'email' => $data['customer']['email'],
                'first_name' => $data['customer']['first_name'],
                'last_name' => $data['customer']['last_name'],
                'phone' => $data['customer']['phone'] ?? '',
            ],
            'billing_address' => [
                'address1' => $data['billing_address']['address1'],
                'address2' => $data['billing_address']['address2'] ?? '',
                'city' => $data['billing_address']['city'],
                'state' => $data['billing_address']['state'] ?? '',
                'country' => $data['billing_address']['country'],
                'zip' => $data['billing_address']['zip'],
            ],
            'shipping_address' => [
                'address1' => $data['billing_address']['address1'],
                'address2' => $data['billing_address']['address2'] ?? '',
                'city' => $data['billing_address']['city'],
                'state' => $data['billing_address']['state'] ?? '',
                'country' => $data['billing_address']['country'],
                'zip' => $data['billing_address']['zip'],
            ],
            'products' => $data['products'],
            'return_url' => $data['return_url'] ?? route('payment.success'),
            'cancel_url' => $data['cancel_url'] ?? route('payment.cancel'),
            'webhook_url' => $data['webhook_url'] ?? route('payment.webhook'),
        ];
    }
}

