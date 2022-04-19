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
            $table->string('name', 100)->nullable();   
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('email', 60)->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('verification_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('profile_image')->nullable();                       
            $table->string('password')->nullable();
            $table->string('token')->nullable(); 
            $table->string('latitude', 20)->nullable();
            $table->string('longitude', 20)->nullable();            
            $table->string('is_active_user')->default('true');            
            $table->string('payment_method_name')->nullable();
            $table->string('name_on_card')->nullable();
            $table->string('card_number')->nullable();
            $table->string('expiry_date')->nullable();
            $table->string('cvv')->nullable(); 
            $table->string('notifications')->default('on');
            $table->enum('is_online', ['0', '1'])->nullable();           
            $table->enum('type', USER_TYPES)->default(1); 
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
