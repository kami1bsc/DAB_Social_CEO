<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); 
            $table->string('post_type')->nullable();
            $table->string('image')->nullable();
            $table->string('video')->nullable();
            $table->string('post_file')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('network_post_type')->nullable();
            $table->string('who_can_collaborate')->nullable();            
            $table->string('category')->nullable();
            $table->string('price')->nullable();
            $table->string('link_of_product_or_service')->nullable();    
            $table->string('event_date')->nullable();
            $table->string('available_seats')->nullable();
            $table->string('location')->nullable();
            $table->string('tags')->nullable();
            $table->string('service_fee')->nullable();
            $table->string('backers')->nullable();
            $table->string('startup_investment')->nullable();
            $table->string('pledge_goal_amount')->nullable();
            $table->string('startup_end_date')->nullable();
            $table->string('investment_type')->nullable();
            $table->string('next_step_for_startup')->nullable();
            $table->string('feel_about_next_step')->nullable();
            $table->string('startup_option_1')->nullable();
            $table->string('startup_option_2')->nullable();
            $table->string('startup_option_3')->nullable();
            $table->string('how_far_along_startup')->nullable();
            $table->string('money_need_for_startup')->nullable();
            $table->string('planned_time_for_startup')->nullable();
            $table->string('do_you_have_enough_money')->nullable();
            $table->string('access_to_startup_network')->nullable(); 
            $table->text('updates')->nullable();
            $table->string('average_rating')->nullable();           
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
        Schema::dropIfExists('posts');
    }
}
