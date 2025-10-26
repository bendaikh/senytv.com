<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->unsignedBigInteger('subscription_id')->nullable();
            
            // SenyPro API identifiers
            $table->string('external_order_id')->unique(); // SENYTV-ORDER-xxx
            $table->string('request_id')->unique()->nullable(); // req_xxx from API
            $table->string('transaction_number')->nullable(); // ORD-20241025-156 from API
            $table->string('payment_id')->nullable(); // pay_abc123 from API
            $table->unsignedBigInteger('senypro_transaction_id')->nullable(); // 42 from API
            $table->unsignedBigInteger('senypro_order_id')->nullable(); // 156 from API
            
            // Payment details
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('status')->default('pending'); // pending, processing, completed, failed
            $table->string('payment_status')->default('Pending'); // Paid, Unpaid, Pending, Refunded
            
            // Customer information
            $table->string('customer_email');
            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            
            // Additional data
            $table->json('billing_address')->nullable();
            $table->json('products')->nullable();
            $table->text('payment_url')->nullable();
            
            // Timestamps
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('status');
            $table->index('payment_status');
            $table->index('customer_email');
            $table->index('created_at');
            $table->index('user_id');
            $table->index('plan_id');
            $table->index('subscription_id');
            
            // Note: Foreign key constraints are optional
            // Eloquent relationships will work without them
            // Add them manually later if users/plans/subscriptions tables exist
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
