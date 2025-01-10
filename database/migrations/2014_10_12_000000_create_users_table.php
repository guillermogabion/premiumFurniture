<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fullname');
            $table->string('email')->unique();
            $table->string('gender');
            $table->string('contact');
            $table->string('address');
            $table->string('shop_name')->nullable();
            $table->string('type')->nullable();
            $table->string('password');
            $table->string('profile')->nullable();
            $table->enum('role', ['admin', 'moderator', 'vendor', 'client'])->default('client');
            $table->enum('status', ['active', 'disabled', 'rejected'])->default('active');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
