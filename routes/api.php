<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Auth Routes
Route::post('/signup', 'Api\AuthController@register');
Route::post('/login', 'Api\AuthController@login');
Route::post('/login_with_facebook', 'Api\AuthController@login_with_facebook');
Route::post('/forgot_password', 'Api\AuthController@forgot_password');
Route::post('/verify_code', 'Api\AuthController@verify_code');
Route::post('/reset_password', 'Api\AuthController@reset_password');
Route::post('/verify_password', 'Api\AuthController@verify_password');
Route::post('/change_password', 'Api\AuthController@change_password');
Route::post('/remove_payment_method', 'Api\AuthController@remove_payment_method');
Route::post('/update_payment_method', 'Api\AuthController@update_payment_method');
Route::post('/update_profile_image', 'Api\AuthController@update_profile_image');
Route::post('/update_profile', 'Api\AuthController@update_profile');
Route::post('/complete_profile', 'Api\AuthController@complete_profile');
Route::post('/create_post', 'Api\MainController@create_post');
Route::post('/get_connect_posts', 'Api\MainController@get_connect_posts');
Route::post('/get_network_posts', 'Api\MainController@get_network_posts');
Route::post('/get_buy_and_sell_posts', 'Api\MainController@get_buy_and_sell_posts');
Route::post('/get_events_posts', 'Api\MainController@get_events_posts');
Route::post('/get_podcast_posts', 'Api\MainController@get_podcast_posts');
Route::post('/get_startup_posts', 'Api\MainController@get_startup_posts');
Route::post('/like_post', 'Api\MainController@like_post');
Route::post('/comment_on_post', 'Api\MainController@comment_on_post');
Route::post('/save_post', 'Api\MainController@save_post');
Route::post('/get_saved_connect_posts', 'Api\MainController@get_saved_connect_posts');
Route::post('/get_saved_network_posts', 'Api\MainController@get_saved_network_posts');
Route::post('/get_saved_buy_and_sell_posts', 'Api\MainController@get_saved_buy_and_sell_posts');
Route::post('/get_saved_events_posts', 'Api\MainController@get_saved_events_posts');
Route::post('/get_saved_podcast_posts', 'Api\MainController@get_saved_podcast_posts');
Route::post('/get_saved_startups_posts', 'Api\MainController@get_saved_startups_posts');
Route::post('/user_details', 'Api\MainController@user_details');
Route::post('/nearby_members', 'Api\MainController@nearby_members');
Route::post('/active_members', 'Api\MainController@active_members');
Route::get('/upcoming_events', 'Api\MainController@upcoming_events');
Route::post('/get_all_notifications', 'Api\MainController@get_all_notifications');
Route::post('/get_buy_and_sell_notifications', 'Api\MainController@get_buy_and_sell_notifications');
Route::post('/get_events_notifications', 'Api\MainController@get_events_notifications');
Route::post('/get_startups_notifications', 'Api\MainController@get_startups_notifications');
Route::post('/post_comments', 'Api\MainController@post_comments');
Route::post('/post_details', 'Api\MainController@post_details');
Route::post('/buy_and_sell_filter', 'Api\MainController@buy_and_sell_filter');
Route::post('/event_posts_filters', 'Api\MainController@event_posts_filters');
Route::post('/startup_posts_filters', 'Api\MainController@startup_posts_filters');
Route::post('/reserve_event_seat', 'Api\MainController@reserve_event_seat');
Route::post('/get_my_followers', 'Api\MainController@get_my_followers');
Route::post('search_suggestions', 'Api\MainController@search_suggestions');
