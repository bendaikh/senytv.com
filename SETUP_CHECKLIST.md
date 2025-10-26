# SenyPro Payment Gateway - Setup Checklist

## ✅ Pre-Deployment Checklist

Use this checklist to ensure everything is properly configured before going live.

---

## 🔧 Step 1: Environment Configuration

- [ ] **Add API credentials to `.env` file**
  ```env
  SENYPRO_API_KEY=sk_57Lcxi6BnAmNb4LwbfBhzJEPzzYGtJTgWSq0fMLrLcMk1m8Y
  SENYPRO_API_SECRET=bDAVH8i9qywBIVSUB9CLF9ZQJD13oNTjbDctBe293HpV7SGYdOLFHqbTggFBBkWa
  SENYPRO_API_BASE_URL=https://senypro.com/api/v1
  ```

- [ ] **Verify `.env` file is in `.gitignore`**
  - NEVER commit `.env` to version control

- [ ] **Set APP_ENV to production**
  ```env
  APP_ENV=production
  APP_DEBUG=false
  ```

---

## 📦 Step 2: Database Setup

- [ ] **Run migrations**
  ```bash
  cd core
  php artisan migrate
  ```

- [ ] **Verify transactions table exists**
  ```bash
  php artisan tinker
  >>> Schema::hasTable('transactions')
  >>> exit
  ```

- [ ] **Check table structure**
  ```sql
  DESCRIBE transactions;
  ```

---

## 🚀 Step 3: Cache & Configuration

- [ ] **Clear all caches**
  ```bash
  php artisan config:clear
  php artisan cache:clear
  php artisan route:clear
  php artisan view:clear
  ```

- [ ] **Cache configuration for production**
  ```bash
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  ```

- [ ] **Verify autoload is up to date**
  ```bash
  composer dump-autoload
  ```

---

## 🔗 Step 4: Verify Routes

- [ ] **Check payment routes are registered**
  ```bash
  php artisan route:list | grep payment
  ```
  
  Expected routes:
  - `GET payment/checkout/{planId}`
  - `POST payment/process`
  - `GET payment/success`
  - `GET payment/cancel`
  - `GET payment/transactions`
  - `GET payment/transactions/{id}`
  - `POST webhooks/payment`

---

## 🎨 Step 5: Frontend Integration

- [ ] **Verify checkout links on home page**
  - Go to your homepage
  - Check "Get Now" buttons link to `/payment/checkout/{planId}`
  - Test clicking at least one button

- [ ] **Check form validation**
  - Submit empty checkout form
  - Verify error messages appear
  - Verify form retains values on error

- [ ] **Test responsive design**
  - View checkout page on mobile
  - View success page on mobile
  - View transaction history on mobile

---

## 🧪 Step 6: Payment Flow Testing

### Test 1: Complete Payment Flow
- [ ] Navigate to homepage
- [ ] Click "Get Now" on any plan
- [ ] Fill out checkout form with test data
- [ ] Submit form
- [ ] Verify redirect to SenyPro payment page
- [ ] Complete payment (or use test mode if available)
- [ ] Verify redirect to success page
- [ ] Check transaction appears in database

### Test 2: Cancel Payment Flow
- [ ] Start new checkout
- [ ] Get to SenyPro payment page
- [ ] Click cancel or close window
- [ ] Verify redirect to cancel page
- [ ] Check transaction is marked as cancelled

### Test 3: View Transaction History
- [ ] Navigate to `/payment/transactions`
- [ ] Verify transactions are listed
- [ ] Click "View" on a transaction
- [ ] Verify all details are correct

---

## 🔍 Step 7: Database Verification

- [ ] **Check transactions are created**
  ```sql
  SELECT * FROM transactions ORDER BY created_at DESC LIMIT 5;
  ```

- [ ] **Verify subscriptions are created**
  ```sql
  SELECT * FROM subscriptions WHERE transaction_id IS NOT NULL;
  ```

- [ ] **Check relationship between transactions and subscriptions**
  ```sql
  SELECT t.id, t.external_order_id, t.payment_status, s.id as sub_id, s.status
  FROM transactions t
  LEFT JOIN subscriptions s ON t.subscription_id = s.id
  WHERE t.payment_status = 'Paid';
  ```

---

## 🔔 Step 8: Webhook Configuration

- [ ] **Ensure webhook URL is publicly accessible**
  - URL: `https://yourdomain.com/webhooks/payment`
  - Must be HTTPS in production
  - Must not require authentication

- [ ] **Test webhook endpoint is reachable**
  ```bash
  curl -X POST https://yourdomain.com/webhooks/payment \
    -H "Content-Type: application/json" \
    -d '{"test": true}'
  ```
  
  Expected: JSON response (not 404 error)

- [ ] **Monitor webhook logs**
  ```bash
  tail -f storage/logs/laravel.log | grep -i webhook
  ```

---

## 🔒 Step 9: Security Verification

- [ ] **HTTPS is enabled** (production only)
- [ ] **SSL certificate is valid**
- [ ] **API credentials are not hardcoded** (only in .env)
- [ ] **CSRF protection is active**
- [ ] **APP_DEBUG=false** in production
- [ ] **Database credentials are secure**
- [ ] **File permissions are correct**
  ```bash
  chmod -R 755 storage bootstrap/cache
  chmod -R 775 storage
  ```

---

## 📊 Step 10: Monitoring & Logging

- [ ] **Check Laravel logs are writable**
  ```bash
  touch storage/logs/laravel.log
  chmod 664 storage/logs/laravel.log
  ```

- [ ] **Test error logging**
  ```bash
  tail -f storage/logs/laravel.log
  ```

- [ ] **Set up log rotation** (prevent logs from growing too large)

- [ ] **Configure error monitoring** (optional)
  - Consider: Sentry, Bugsnag, or Laravel Telescope

---

## 💰 Step 11: Financial Verification

- [ ] **Test with minimum amount** (e.g., $1.00)
- [ ] **Verify amount is correct on payment page**
- [ ] **Check currency is displayed correctly**
- [ ] **Confirm transaction records correct amount in database**
- [ ] **Verify no double-charging occurs**

---

## 📱 Step 12: User Experience

- [ ] **Checkout form is user-friendly**
- [ ] **Error messages are clear**
- [ ] **Success page shows all necessary info**
- [ ] **Cancel page offers retry option**
- [ ] **Loading states are visible**
- [ ] **Page titles are appropriate**

---

## 📧 Step 13: Notifications (Optional)

If you plan to send email notifications:

- [ ] **Configure mail settings in `.env`**
  ```env
  MAIL_MAILER=smtp
  MAIL_HOST=smtp.mailtrap.io
  MAIL_PORT=2525
  MAIL_USERNAME=your_username
  MAIL_PASSWORD=your_password
  MAIL_ENCRYPTION=tls
  MAIL_FROM_ADDRESS=noreply@yourdomain.com
  MAIL_FROM_NAME="${APP_NAME}"
  ```

- [ ] **Test email sending**
  ```bash
  php artisan tinker
  >>> Mail::raw('Test email', function($msg) { $msg->to('test@example.com')->subject('Test'); });
  ```

---

## 🌐 Step 14: Production Deployment

- [ ] **Backup database before deployment**
  ```bash
  mysqldump -u username -p database_name > backup.sql
  ```

- [ ] **Deploy code to production server**

- [ ] **Run migrations on production**
  ```bash
  php artisan migrate --force
  ```

- [ ] **Clear and cache everything**
  ```bash
  php artisan optimize
  ```

- [ ] **Set correct file ownership**
  ```bash
  chown -R www-data:www-data storage bootstrap/cache
  ```

---

## ✅ Step 15: Post-Deployment Testing

- [ ] **Test live payment with real money** (small amount)
- [ ] **Verify payment reaches SenyPro**
- [ ] **Check funds appear in your SenyPro dashboard**
- [ ] **Confirm subscription is activated**
- [ ] **Test webhook receives real notifications**

---

## 📊 Step 16: Create Test Scenarios

Document these scenarios for future testing:

### Scenario 1: Successful Payment
- [ ] User selects plan
- [ ] Fills form correctly
- [ ] Completes payment
- [ ] Gets confirmation
- [ ] Subscription activated

### Scenario 2: Failed Payment
- [ ] User selects plan
- [ ] Payment fails/declined
- [ ] User sees error message
- [ ] Transaction marked as failed
- [ ] User can retry

### Scenario 3: Cancelled Payment
- [ ] User starts checkout
- [ ] Clicks cancel on payment page
- [ ] Redirected to cancel page
- [ ] Can restart checkout

---

## 🎯 Final Verification

### Quick Test Script
```bash
#!/bin/bash
echo "Testing SenyPro Integration..."

# 1. Check environment
echo "✓ Checking .env file..."
grep -q "SENYPRO_API_KEY" core/.env && echo "  API Key found" || echo "  ⚠ API Key missing!"

# 2. Check database
echo "✓ Checking database..."
php core/artisan tinker --execute="echo Schema::hasTable('transactions') ? 'Transactions table exists' : 'Table missing!';"

# 3. Check routes
echo "✓ Checking routes..."
php core/artisan route:list | grep -q "payment.checkout" && echo "  Payment routes registered" || echo "  ⚠ Routes missing!"

# 4. Check service class
echo "✓ Checking service class..."
test -f "core/app/Services/SenyProPaymentService.php" && echo "  Service class exists" || echo "  ⚠ Service missing!"

# 5. Check controller
echo "✓ Checking controller..."
test -f "core/app/Http/Controllers/PaymentController.php" && echo "  Controller exists" || echo "  ⚠ Controller missing!"

# 6. Check views
echo "✓ Checking views..."
test -f "core/resources/views/templates/amber/checkout.blade.php" && echo "  Checkout view exists" || echo "  ⚠ View missing!"

echo ""
echo "✅ Pre-flight check complete!"
```

Save as `test-integration.sh` and run:
```bash
chmod +x test-integration.sh
./test-integration.sh
```

---

## 📞 Support Resources

### If Something Goes Wrong:

1. **Check Laravel Logs**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Check Web Server Logs**
   ```bash
   # Apache
   tail -f /var/log/apache2/error.log
   
   # Nginx
   tail -f /var/log/nginx/error.log
   ```

3. **Check Database Connection**
   ```bash
   php artisan tinker
   >>> DB::connection()->getPdo();
   ```

4. **Verify API Connectivity**
   ```bash
   curl -I https://senypro.com/api/v1
   ```

---

## 🎉 Success Indicators

You're ready to go live when:

- ✅ All checklist items are completed
- ✅ Test payment successful
- ✅ Transaction appears in database
- ✅ Subscription created automatically
- ✅ No errors in logs
- ✅ Webhook receives notifications
- ✅ All pages load correctly
- ✅ Mobile view works properly

---

## 📝 Maintenance Tasks

### Weekly:
- [ ] Check transaction success rate
- [ ] Review failed payments
- [ ] Monitor API response times
- [ ] Check log file sizes

### Monthly:
- [ ] Review total revenue
- [ ] Analyze top-selling plans
- [ ] Check for stuck transactions
- [ ] Update documentation if needed

---

**Ready to Launch?** 🚀

Once all items are checked, you're ready to accept payments through SenyPro!

**Last Updated:** October 25, 2025

