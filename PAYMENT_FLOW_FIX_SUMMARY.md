# Payment Processing Error - Full_Name Field Issue

## Problem Summary

When attempting to process a payment from senytv.com through Senypro gateway on October 28, 2025:

1. **Senytv.com request**: Successfully sent payment order to Senypro API ✓
2. **Senypro processing**: Successfully created payment with Dodopayments ✓  
3. **Senypro user creation**: FAILED ❌

```
Error: SQLSTATE[HY000]: General error: 1364 Field 'full_name' doesn't have a default value
Location: INSERT INTO users (email, updated_at, created_at) VALUES (...)
```

## Root Causes

### Issue #1: Senytv.com PaymentController (FIXED ✓)
**Location**: `core/app/Http/Controllers/PaymentController.php` line 147-153

**Problem**: When creating a guest user after payment, the code used the wrong field name:
```php
// BEFORE (WRONG)
$user = User::firstOrCreate(
    ['email' => $request->email],
    [
        'name' => $request->first_name . ' ' . $request->last_name,  // ❌ Field doesn't exist
        'password' => bcrypt(uniqid()),
    ]
);
```

**Solution Applied**: 
```php
// AFTER (CORRECT)
$user = User::firstOrCreate(
    ['email' => $request->email],
    [
        'full_name' => $request->first_name . ' ' . $request->last_name,  // ✓ Correct field
        'phone_number' => $request->phone,                                 // ✓ Added
        'country' => $request->country,                                    // ✓ Added
    ]
);
```

### Issue #2: Senypro Database Schema (NEEDS YOUR ACTION)
**Location**: Senypro application's `users` table

**Problem**: The `users` table has `full_name` as a NOT NULL field with no default value:
```sql
CREATE TABLE users (
  ...
  `full_name` varchar(255) NOT NULL,  -- ❌ No default value, required
  `email` varchar(191) NOT NULL,
  ...
);
```

When Senypro tries to create a user, it only provides `email` (and timestamps), causing the database to reject the insert.

**Solution Required**: Choose ONE of these options:

#### Option A: Make full_name Optional (Recommended)
```sql
-- Run this in your Senypro database:
ALTER TABLE users MODIFY COLUMN full_name varchar(255) DEFAULT '';
```

#### Option B: Create Default Value
```sql
ALTER TABLE users MODIFY COLUMN full_name varchar(255) NOT NULL DEFAULT 'Guest User';
```

#### Option C: Fix Senypro Code (Best Long-term)
In Senypro's user creation logic, ensure you're providing full_name:
```php
$user = User::create([
    'email' => $customerEmail,
    'full_name' => $firstName . ' ' . $lastName,  // ← Add this
    'phone_number' => $phone,
    'country' => $country,
]);
```

## Test Checklist

After applying these fixes:

- [ ] Senytv.com PaymentController updated (✓ Done)
- [ ] Senypro database schema modified (SELECT ONE OPTION ABOVE)
- [ ] Test payment flow end-to-end
- [ ] Verify user is created with all required fields
- [ ] Check both senytv.com and Senypro logs for success

## Files Modified

- `core/app/Http/Controllers/PaymentController.php` - Fixed user creation logic

## Next Steps

1. **For senytv.com**: No further action needed - fix has been applied
2. **For Senypro**: Apply one of the database schema fixes listed in "Issue #2"
3. **Test**: Process a test payment to verify the complete flow works

## Prevention

To prevent similar issues in the future:

1. Always ensure required database fields are provided when creating records
2. Use meaningful field names that match your models
3. Add database constraints and defaults during schema design
4. Test the complete payment flow in a staging environment first
