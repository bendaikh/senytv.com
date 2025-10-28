# Payment Flow - Visual Diagram

## Complete Payment Flow

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           CUSTOMER FLOW                                     │
└─────────────────────────────────────────────────────────────────────────────┘

1. Customer initiates payment on senytv.com
                    │
                    │ Fill form:
                    │ - First Name: Test
                    │ - Last Name: User
                    │ - Email: test@example.com
                    │ - Phone: +1234567890
                    │ - Country: US
                    │ - Address: 123 Main St
                    │
                    ▼

2. Click "Continue" → PaymentController::processCheckout()
                    │
                    ├─ ✅ FIXED: Create/find user with:
                    │   - email ✓
                    │   - full_name ✓ (NOW CORRECT)
                    │   - phone_number ✓ (NOW INCLUDED)
                    │   - country ✓ (NOW INCLUDED)
                    │
                    ▼

3. Call Senypro API → /api/v1/orders
                    │
                    ├─ Request includes:
                    │  - customer.email ✓
                    │  - customer.first_name ✓
                    │  - customer.last_name ✓
                    │  - billing address ✓
                    │
                    ▼

4. Senypro processes order
                    │
                    ├─ ⚠️ ISSUE HERE: Tries to create user
                    │   INSERT INTO users (email, updated_at, created_at)
                    │   ❌ Missing: full_name (required, no default)
                    │
                    ├─ Solution: Modify database OR
                    │   Include full_name in insert
                    │
                    ▼

5. Senypro calls Dodopayments API
                    │
                    ├─ Create payment ✓
                    │  - Payment ID: pay_PBthfF0Yt2UbsiITIzdqp
                    │
                    ▼

6. Return payment link to customer
                    │
                    ├─ Redirect to Dodopayments checkout
                    │  - https://checkout.dodopayments.com/WQh7aEUP
                    │
                    ▼

7. Customer completes payment on Dodopayments
                    │
                    ▼

8. Webhook callback to senytv.com
                    │
                    ├─ Update transaction status
                    │
                    ├─ Create subscription if paid
                    │
                    ▼

9. Subscription activated
```

## Architecture Diagram

```
┌────────────────────────────────────────────────────────────────────────────┐
│                         SENYTV.COM INSTANCE                                │
├────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌──────────────┐        ┌─────────────────────────────────────────────┐  │
│  │   Customer   │        │   PaymentController::processCheckout()      │  │
│  │   Frontend   │───────>│                                             │  │
│  │  (Checkout)  │        │   ✅ FIXED:                                 │  │
│  └──────────────┘        │   - Create user with full_name             │  │
│                          │   - Include phone_number                   │  │
│                          │   - Include country                        │  │
│                          │                                             │  │
│                          └────────┬────────────────────────────────────┘  │
│                                   │                                        │
│                    HTTP POST /api/v1/orders                                │
│                                   │                                        │
└───────────────────────────────────┼────────────────────────────────────────┘
                                    │
                                    ▼
┌────────────────────────────────────────────────────────────────────────────┐
│                         SENYPRO INSTANCE                                   │
├────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌────────────────────────────────────────────────────────────────────┐   │
│  │  API::payment()                                                    │   │
│  │                                                                    │   │
│  │  ┌─────────────────────────────────────────────────────────────┐  │   │
│  │  │ Try to create user:                                         │  │   │
│  │  │                                                             │  │   │
│  │  │ User::create([                                             │  │   │
│  │  │     'email' => $customerEmail,     ✓ PROVIDED            │  │   │
│  │  │     'full_name' => ???             ❌ MISSING             │  │   │
│  │  │     'phone_number' => ???          ❌ MISSING             │  │   │
│  │  │     'country' => ???               ❌ MISSING             │  │   │
│  │  │ ])                                                         │  │   │
│  │  │                                                             │  │   │
│  │  │ Database Error:                                            │  │   │
│  │  │ ❌ full_name doesn't have a default value                 │  │   │
│  │  │                                                             │  │   │
│  │  │ ⚠️  SOLUTION: Either                                        │  │   │
│  │  │   • ALTER TABLE users MODIFY full_name DEFAULT ''         │  │   │
│  │  │   • OR include full_name in user creation                 │  │   │
│  │  └─────────────────────────────────────────────────────────────┘  │   │
│  │                                                                    │   │
│  └────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
└────────────────────────────────────────────────────────────────────────────┘
```

## Data Flow - Before and After Fix

### BEFORE (❌ BROKEN)

```
Customer Payment Form
    │
    ├─ first_name: "Test"
    ├─ last_name: "User"
    ├─ email: "test@example.com"
    ├─ phone: "+1234567890"
    └─ country: "US"
         │
         ▼
    PaymentController
         │
         ├─ ❌ Creates user with:
         │   - name: "Test User"      (WRONG FIELD)
         │   - password: "hashed"     (UNNECESSARY)
         │   - Missing: phone_number
         │   - Missing: country
         │
         ├─ User model rejects (expects full_name)
         │
         └─ ❌ Exception: field 'name' doesn't exist
```

### AFTER (✅ FIXED)

```
Customer Payment Form
    │
    ├─ first_name: "Test"
    ├─ last_name: "User"
    ├─ email: "test@example.com"
    ├─ phone: "+1234567890"
    └─ country: "US"
         │
         ▼
    PaymentController
         │
         ├─ ✅ Creates user with:
         │   - full_name: "Test User"        (CORRECT)
         │   - phone_number: "+1234567890"   (INCLUDED)
         │   - country: "US"                 (INCLUDED)
         │
         ├─ User model accepts all fields ✓
         │
         ├─ User created successfully ✓
         │
         └─ ✅ Proceeds to Senypro API call
```

## Status Summary

```
┌─────────────────────────────────────┬─────────────┬──────────────────┐
│           Component                 │   Status    │    Solution      │
├─────────────────────────────────────┼─────────────┼──────────────────┤
│ Senytv.com PaymentController        │ ✅ FIXED    │ Code updated     │
│ User field mapping                  │ ✅ FIXED    │ name→full_name   │
│ Phone & Country fields              │ ✅ FIXED    │ Added            │
│                                     │             │                  │
│ Senypro user creation logic         │ ⚠️  PENDING │ Needs DB fix OR  │
│ full_name field requirement         │ ⚠️  PENDING │ code update      │
│                                     │             │                  │
│ Overall payment flow                │ ⏳ BLOCKED  │ Awaiting Senypro │
│                                     │             │ database fix     │
└─────────────────────────────────────┴─────────────┴──────────────────┘
```

## Test Verification Flow

```
After Senypro database fix applied:

1. Clear caches
   └─> php artisan cache:clear

2. Navigate to senytv.com/payment/checkout/{planId}

3. Fill checkout form
   ├─ First Name: Test
   ├─ Last Name: User
   ├─ Email: test@example.com
   ├─ Phone: +1234567890
   ├─ Address: 123 Main St
   ├─ City: Boston
   ├─ Country: US
   └─ Zip: 02101

4. Click "Continue"

5. Expected outcome:
   ├─ ✓ User created in senytv.com database
   ├─ ✓ User created in Senypro database (after fix)
   ├─ ✓ Order created in Senypro
   ├─ ✓ Payment created in Dodopayments
   └─ ✓ Redirect to Dodopayments checkout page

6. Success indicators:
   ├─ Senypro logs: "API Payment Processed Successfully"
   ├─ Senytv logs: "Redirecting customer to payment URL"
   └─ Browser: Dodopayments checkout page visible
```
