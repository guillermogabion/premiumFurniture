0<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateOrdersTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')
                    ->constrained('users')
                    ->onDelete('cascade');
                $table->json('product_ids');
                $table->string('quantity');
                $table->decimal('downpayment_amount', 10, 2)->nullable();
                $table->decimal('total', 10, 2);
                $table->string('orderId')->unique(); // Ensures unique order IDs
                $table->timestamp('date');
                $table->enum('status', ['to_pay', 'preparing', 'to_ship', 'shipping', 'received', 'cancelled']);
                $table->string('ref_no'); // Reference number field
                $table->string('payment_mode'); // Payment type field
                $table->string('image')->nullable(); // Nullable image field for the QR code or any related image
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::dropIfExists('orders');
        }
    }
