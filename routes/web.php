<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group([ 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'CheckUserRole']], function() {
    //define routes here for admin
    Route::get('/', 'Admin\NavigationController@dashboard')->name('dashboard');	
	Route::resource('/all_users', 'Admin\AllUsersController');  
	Route::get('/verified_users', 'Admin\AllUsersController@verified_users')->name('all_users.verified_users');
	Route::get('/verify_user/{user_id}', 'Admin\AllUsersController@verify_user')->name('all_users.verify_user');  
});

Route::group([ 'prefix' => 'user', 'as' => 'user.', 'middleware' => ['auth', 'CheckUserRole']], function() {
    Route::get('/', 'User\NavigationController@dashboard')->name('dashboard');
	//define routes here for user
});

// Route::get('stripe', 'StripePaymentController@stripe');
// Route::post('stripe', 'StripePaymentController@stripePost')->name('stripe.post');

Route::get('stripe', 'PaymentController@stripe');
Route::post('stripe', 'PaymentController@stripePost')->name('stripe.post');

Route::prefix('dev')->group(function(){
	Route::get('storage-link', function(){
		try{
			\Artisan::call('storage:link');
			echo "Media Storage Linked Successfully";
		} catch( \Exception $e) {
			dd($e->getMessage());
		}
	});
});

Route::prefix('dev')->group(function(){
	Route::get('config-clear', function(){
		try{
			\Artisan::call('config:clear');
			echo "Configuration cache cleared!";
		} catch( \Exception $e) {
			dd($e->getMessage());
		}
	});
});

Route::prefix('dev')->group(function(){
	Route::get('route-clear', function(){
		try{
			\Artisan::call('route:clear');
			echo "Route cache cleared!";
		} catch( \Exception $e) {
			dd($e->getMessage());
		}
	});
});

Route::prefix('dev')->group(function(){
	Route::get('view-clear', function(){
		try{
			\Artisan::call('view:clear');
			echo "View cache cleared!";
		} catch( \Exception $e) {
			dd($e->getMessage());
		}
	});
});

Route::prefix('dev')->group(function(){
	Route::get('config-cache', function(){
		try{
			\Artisan::call('config:cache');
			echo "Configuration cache cleared!";
			echo "Configuration cached successfully!";
		} catch( \Exception $e) {
			dd($e->getMessage());
		}
	});
});

Route::prefix('dev')->group(function(){
	Route::get('route-cache', function(){
		try{
			\Artisan::call('route:cache');
			echo "Route cache cleared!";
			echo "Route cached successfully!";
		} catch( \Exception $e) {
			dd($e->getMessage());
		}
	});
});

Route::prefix('dev')->group(function(){
	Route::get('view-cache', function(){
		try{
			\Artisan::call('view:cache');
			echo "View cache cleared!";
			echo "View cached successfully!";
		} catch( \Exception $e) {
			dd($e->getMessage());
		}
	});
});




