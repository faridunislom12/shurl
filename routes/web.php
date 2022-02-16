<?php

use App\Http\Controllers\AuthController;
use App\Models\Url;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your url. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/{short}', [UrlController::class, 'open'])->name('urls.open');


Route::middleware('auth')->group(function () {
    Route::get('/', [UrlController::class, 'index'])->name('index');

    Route::group(['prefix' => 'admin'], function () {

        Route::get('logout', function () {
            Auth::logout();
            return redirect(route('auth.login'));
        })->name('logout');



        $permission_actions = array(
            ["create", ['create', 'store']],
            ["read", ['index', 'show']],
            ["update", ['edit', 'update']],
            ["delete", ['destroy']]);

        foreach ($permission_actions as $permission_action) {
            Route::resource('/urls', 'App\Http\Controllers\UrlController', [
                'names' => ['Url', 'urls'],
                'only' => $permission_action[1],
                'middleware' => ['permission:urls-' . $permission_action[0]]
            ]);
        }

        Route::resource('/users', 'App\Http\Controllers\UserController', ['middleware' => ['role:administrator']]);


    });
});


// Auth
Route::group(['prefix' => 'auth'], function () {
    Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/check-login', [AuthController::class, 'checkLogin'])->name('auth.check-login');
    Route::post('/sign-in', [AuthController::class, 'signIn'])->name('auth.sign-in');
    Route::get('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/register', [AuthController::class, 'store'])->name('auth.register.store');
    Route::get('/reset-password', [AuthController::class, 'resetPassword'])->name('auth.reset-password');
    Route::post('/confirm-password/{type}', [AuthController::class, 'confirmPassword'])->name('auth.confirm.password');
});




