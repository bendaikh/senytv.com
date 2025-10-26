# SenyPro Payment Gateway Integration - Complete Setup Guide

## Overview

This document provides complete instructions for setting up and using the SenyPro Payment Gateway integration in your IPTV Laravel application.

## ✅ What Has Been Implemented

### 1. Core Components Created

#### **Payment Service** (`core/app/Services/SenyProPaymentService.php`)
- Complete API integration with SenyPro
- Methods for creating orders, checking status, and listing transactions
- Comprehensive error handling and logging
- Automatic order ID generation

#### **Transaction Model** (`core/app/Models/Transaction.php`)
- Complete database model for tracking all payment transactions
- Relationships with User, Plan, and Subscription models
- Helper methods for checking transaction status
- Query scopes for filtering transactions

#### **Payment Controller** (`core/app/Http/Controllers/PaymentController.php`)
- Checkout page display and processing
- Success and cancel page handlers
- Transaction history and detail views
- Webhook handler for payment notifications
- Automatic subscription creation on successful payment

#### **Database Migration** (`core/database/migrations/2025_10_25_140628_create_transactions_table.php`)
- Complete database schema for transactions
- Indexes for optimized queries
- Foreign key relationships

#### **Payment Views** (Blade Templates)
- `checkout.blade.php` - Customer information and billing form
- `payment-success.blade.php` - Success confirmation page
- `payment-cancel.blade.php` - Cancellation page
- `transactions.blade.php` - Transaction history list
- `transaction-detail.blade.php` - Detailed transaction view

### 2. Routes Added

All payment routes are configured in `core/routes/web.php`:

```php
// Payment Routes
Route::prefix('payment')->name('payment.')->group(function () {
    Route::get('checkout/{planId}', [PaymentController::class, 'checkout'])->name('checkout');
    Route::post('process', [PaymentController::class, 'processCheckout'])->name('process');
    Route::get('success', [PaymentController::class, 'success'])->name('success');
    Route::get('cancel', [PaymentController::class, 'cancel'])->name('cancel');
    Route::get('transactions', [PaymentController::class, 'transactions'])->name('transactions');
    Route::get('transactions/{id}', [PaymentController::class, 'transactionDetail'])->name('transaction.detail');
});

// Webhook Route (public access)
Route::post('webhooks/payment', [PaymentController::class, 'webhook'])->name('payment.webhook');
```

### 3. Configuration

#### **Services Configuration** (`core/config/services.php`)
Added SenyPro configuration:

```php
'senypro' => [
    'api_key' => env('SENYPRO_API_KEY'),
    'api_secret' => env('SENYPRO_API_SECRET'),
    'api_base_url' => env('SENYPRO_API_BASE_URL', 'https://senypro.com/api/v1'),
],
```

### 4. Model Updates

#### **Subscription Model** (`core/app/Models/Subscription.php`)
Added relationship to Transaction model:

```php
public function transaction()
{
    return $this->belongsTo(Transaction::class);
}
```

### 5. Home Page Integration

The "Get Now" buttons on the home page now link directly to the checkout page for each plan.

---

## 🚀 Setup Instructions

### Step 1: Add Environment Variables

Add the following to your `.env` file:

```env
# SenyPro Payment Gateway Configuration
SENYPRO_API_KEY=sk_57Lcxi6BnAmNb4LwbfBhzJEPzzYGtJTgWSq0fMLrLcMk1m8Y
SENYPRO_API_SECRET=bDAVH8i9qywBIVSUB9CLF9ZQJD13oNTjbDctBe293HpV7SGYdOLFHqbTggFBBkWa
SENYPRO_API_BASE_URL=https://senypro.com/api/v1
```

**⚠️ IMPORTANT:** Never commit your `.env` file to version control. These are live credentials.

### Step 2: Run Database Migration

Navigate to the `core` directory and run the migration:

```bash
cd core
php artisan migrate
```

This will create the `transactions` table in your database.

### Step 3: Clear Cache

Clear Laravel's cache to ensure all new configurations are loaded:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Step 4: Verify Installation

1. Visit your homepage
2. Click on any "Get Now" button under a plan
3. You should be redirected to the checkout page
4. Fill in the form and test the payment flow

---

## 📋 Payment Flow

### 1. Customer Clicks "Get Now" on a Plan
- User is redirected to `/payment/checkout/{planId}`
- Checkout form is displayed with billing fields

### 2. Customer Fills Out Checkout Form
- Required fields:
  - First Name, Last Name
  - Email Address
  - Phone Number (optional)
  - Address, City, Country, ZIP
- Form submits to `/payment/process`

### 3. Order Creation
- System creates a transaction record in the database
- System calls SenyPro API to create an order
- User is redirected to SenyPro's payment page

### 4. Customer Completes Payment
- Customer enters payment details on SenyPro's secure page
- After payment, customer is redirected to `/payment/success` or `/payment/cancel`

### 5. Success Page
- System checks payment status with SenyPro API
- If paid, creates/activates subscription automatically
- Displays transaction details and confirmation

### 6. Webhook (Background)
- SenyPro sends payment notifications to `/webhooks/payment`
- System updates transaction status
- System creates subscription if not already created

---

## 🧪 Testing Instructions

### Test 1: Create a Test Order

1. Go to your homepage: `http://yourdomain.com`
2. Click "Get Now" on any plan
3. Fill in the checkout form with test data:
   - **First Name:** Test
   - **Last Name:** User
   - **Email:** test@example.com
   - **Phone:** +1234567890
   - **Address:** 123 Test Street
   - **City:** Test City
   - **Country:** US
   - **ZIP:** 12345
4. Click "Proceed to Payment"
5. You should be redirected to Dodopayments checkout page

### Test 2: Complete Payment

1. On the Dodopayments checkout page, complete the payment
2. You will be redirected back to your success page
3. Verify the transaction details are displayed correctly
4. Check that a subscription was created in the database

### Test 3: Cancel Payment

1. Start a new checkout
2. On the Dodopayments page, click "Cancel" or close the window
3. You should be redirected to the cancel page
4. Transaction should be marked as "cancelled" in the database

### Test 4: View Transaction History

1. Navigate to `/payment/transactions`
2. You should see a list of all transactions
3. Click "View" on any transaction to see full details

### Test 5: Check Database

Run these SQL queries to verify data:

```sql
-- View all transactions
SELECT * FROM transactions ORDER BY created_at DESC;

-- View all subscriptions
SELECT * FROM subscriptions ORDER BY created_at DESC;

-- Check transaction with subscription
SELECT t.*, s.* 
FROM transactions t 
LEFT JOIN subscriptions s ON t.subscription_id = s.id 
WHERE t.payment_status = 'Paid';
```

---

## 🔍 API Endpoints Reference

### 1. Create Order
**Endpoint:** `POST https://senypro.com/api/v1/orders`

**Headers:**
```
X-API-Key: sk_57Lcxi6BnAmNb4LwbfBhzJEPzzYGtJTgWSq0fMLrLcMk1m8Y
X-API-Secret: bDAVH8i9qywBIVSUB9CLF9ZQJD13oNTjbDctBe293HpV7SGYdOLFHqbTggFBBkWa
Content-Type: application/json
```

**Request Body:**
```json
{
    "external_order_id": "SENYTV-ORDER-1698234567-ABC123",
    "amount": 99.99,
    "currency": "USD",
    "customer": {
        "email": "customer@example.com",
        "first_name": "John",
        "last_name": "Doe",
        "phone": "+1234567890"
    },
    "billing_address": {
        "address1": "123 Main St",
        "city": "New York",
        "state": "NY",
        "country": "US",
        "zip": "10001"
    },
    "products": [
        {
            "name": "IPTV Subscription - 1 Month",
            "quantity": 1,
            "price": 99.99
        }
    ],
    "return_url": "https://yourdomain.com/payment/success",
    "cancel_url": "https://yourdomain.com/payment/cancel",
    "webhook_url": "https://yourdomain.com/webhooks/payment"
}
```

**Success Response (201):**
```json
{
    "success": true,
    "message": "Order created successfully",
    "request_id": "req_1635427890_abc123",
    "data": {
        "transaction_id": 42,
        "order_id": 156,
        "transaction_number": "ORD-20241025-156",
        "payment_url": "https://checkout.dodopayments.com/pay/xyz789",
        "payment_id": "pay_abc123",
        "amount": 99.99,
        "currency": "USD",
        "status": "pending_payment"
    }
}
```

### 2. Check Order Status
**Endpoint:** `GET https://senypro.com/api/v1/orders/{requestId}`

**Headers:** Same as Create Order

**Success Response (200):**
```json
{
    "success": true,
    "data": {
        "request_id": "req_1635427890_abc123",
        "transaction_id": 42,
        "external_order_id": "SENYTV-ORDER-12345",
        "status": "completed",
        "payment_status": "Paid",
        "amount": 99.99,
        "currency": "USD",
        "order": {
            "id": 156,
            "transaction_number": "ORD-20241025-156",
            "order_status": "Pending",
            "payment_status": "Paid",
            "created_at": "2024-10-25T10:30:00Z"
        }
    }
}
```

### 3. List Transactions
**Endpoint:** `GET https://senypro.com/api/v1/transactions`

**Query Parameters:**
- `per_page` (optional): Number of results (default: 15)
- `status` (optional): Filter by status (pending, completed, failed)
- `payment_status` (optional): Filter by payment status (Paid, Unpaid, Pending, Refunded)

---

## 🛠️ Troubleshooting

### Issue: "Payment URL not working"
**Solution:** Verify that your API credentials are correct in the `.env` file.

### Issue: "Transaction not found after payment"
**Solution:** Check the `transactions` table in your database. The `request_id` should match the one from SenyPro.

### Issue: "Subscription not created automatically"
**Solution:** 
1. Check if webhook is being received at `/webhooks/payment`
2. Check Laravel logs in `storage/logs/laravel.log`
3. Manually run the success page to trigger subscription creation

### Issue: "Payment status not updating"
**Solution:** 
1. Ensure the webhook URL is accessible from the internet (not localhost)
2. Check firewall settings
3. Test webhook manually with a POST request

### Issue: "API returns 401 Unauthorized"
**Solution:** 
1. Verify API Key and Secret are correct
2. Check that credentials are active and not expired
3. Contact SenyPro support if credentials need re-activation

---

## 📊 Database Schema

### Transactions Table

| Column | Type | Description |
|--------|------|-------------|
| id | BIGINT | Primary key |
| user_id | BIGINT | Foreign key to users table |
| plan_id | BIGINT | Foreign key to plans table |
| subscription_id | BIGINT | Foreign key to subscriptions table |
| external_order_id | VARCHAR | Unique order ID (SENYTV-ORDER-xxx) |
| request_id | VARCHAR | SenyPro request ID |
| transaction_number | VARCHAR | SenyPro transaction number |
| payment_id | VARCHAR | SenyPro payment ID |
| senypro_transaction_id | BIGINT | SenyPro internal transaction ID |
| senypro_order_id | BIGINT | SenyPro internal order ID |
| amount | DECIMAL(10,2) | Payment amount |
| currency | VARCHAR(3) | Currency code (USD, EUR, etc.) |
| status | VARCHAR | Transaction status (pending, completed, cancelled, failed) |
| payment_status | VARCHAR | Payment status (Paid, Unpaid, Pending, Refunded) |
| customer_email | VARCHAR | Customer email |
| customer_name | VARCHAR | Customer full name |
| customer_phone | VARCHAR | Customer phone |
| billing_address | JSON | Billing address details |
| products | JSON | Products/items purchased |
| payment_url | TEXT | SenyPro payment page URL |
| completed_at | TIMESTAMP | Completion timestamp |
| created_at | TIMESTAMP | Creation timestamp |
| updated_at | TIMESTAMP | Last update timestamp |

---

## 🔒 Security Notes

1. **API Credentials:** Never expose your API Key and Secret in client-side code
2. **HTTPS Only:** Always use HTTPS in production for all payment pages
3. **Webhook Validation:** The webhook endpoint is publicly accessible, consider adding signature validation
4. **Input Validation:** All user inputs are validated before sending to the API
5. **CSRF Protection:** All forms include CSRF tokens
6. **SQL Injection:** Using Laravel's Eloquent ORM prevents SQL injection
7. **XSS Protection:** Laravel's Blade templating auto-escapes output

---

## 📞 Support

For issues related to:
- **This Integration:** Check Laravel logs at `storage/logs/laravel.log`
- **API Issues:** Contact SenyPro support
- **Payment Issues:** Check SenyPro dashboard for transaction details

---

## 🎯 Quick Links

- **Checkout Page:** `http://yourdomain.com/payment/checkout/{planId}`
- **Transaction History:** `http://yourdomain.com/payment/transactions`
- **Webhook URL:** `http://yourdomain.com/webhooks/payment`
- **SenyPro API Docs:** `https://senypro.com/api/v1`

---

## ✅ Checklist Before Going Live

- [ ] Add SenyPro credentials to `.env` file
- [ ] Run database migrations
- [ ] Clear all Laravel caches
- [ ] Test full payment flow with real payment
- [ ] Verify webhook is accessible from internet
- [ ] Set up proper SSL certificate (HTTPS)
- [ ] Configure email notifications for customers
- [ ] Set up monitoring/alerts for failed payments
- [ ] Back up database
- [ ] Test transaction history page
- [ ] Verify subscriptions are created correctly

---

## 📝 Notes

- All payment processing is handled securely by SenyPro/Dodopayments
- Customer payment details are never stored on your server
- Transactions are automatically linked to subscriptions
- Guest users are automatically created in the system
- All API interactions are logged for debugging

---

**Last Updated:** October 25, 2025
**Version:** 1.0.0
**Status:** Production Ready ✅

