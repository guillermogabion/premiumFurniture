<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DetailsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PositionsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopTypeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\RoomMessagesController;
use App\Http\Controllers\SupportDocumentController;

Route::post('/register_web', [UsersController::class, 'registers'])->name('register_web');

Route::get('/', function () {
    return Auth::check() ? redirect()->route('home') : redirect()->route('login');
});
Route::get('/reset_password', [UsersController::class, 'resetPassword'])->name('reset');
Route::post('/reset_my_password', [UsersController::class, 'resetMyPassword'])->name('reset_my_password');



Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');


    // users 
    Route::get('/users', [UsersController::class, 'index'])->name('users');
    Route::get('/vendor', [UsersController::class, 'vendor'])->name('vendor');
    Route::get('/client', [UsersController::class, 'client'])->name('client');
    Route::post('/user_add', [UsersController::class, 'store'])->name('user-add');
    Route::post('/user_update', [UsersController::class, 'update'])->name('user-update');
    Route::post('/users/{id}/status', [UsersController::class, 'updateStatus'])->name('vendor.updateStatus');

    // profile 
    Route::get('/details', [DetailsController::class, 'index'])->name('details');
    Route::post('/details-store', [DetailsController::class, 'store'])->name('details-store');


    // organization 
    Route::get('/organizations', [OrganizationController::class, 'index'])->name('organizations');
    Route::post('/organizations_add', [OrganizationController::class, 'store'])->name('organizations-add');
    Route::post('/organizations_update', [OrganizationController::class, 'update'])->name('organizations-update');
    Route::post('/organizations/{id}/status', [OrganizationController::class, 'updateStatus'])->name('organizations.updateStatus');


    // position 
    Route::get('/positions', [PositionsController::class, 'index'])->name('positions');
    Route::post('/position_add', [PositionsController::class, 'store'])->name('position-add');
    Route::post('/positions/{id}/status', [PositionsController::class, 'updateStatus'])->name('positions.updateStatus');


    // candidate 
    Route::get('/candidates', [CandidateController::class, 'index'])->name('candidates');
    Route::post('/candidate_add', [CandidateController::class, 'store'])->name('candidate-add');
    Route::post('/candidates/{id}/status', [CandidateController::class, 'updateStatus'])->name('candidates.updateStatus');
    Route::post('/candidate_update', [CandidateController::class, 'update'])->name('candidate-update');


    // category 
    Route::get('/category', [CategoryController::class, 'index'])->name('category');
    Route::post('/category_add', [CategoryController::class, 'newCategory'])->name('category_add');
    Route::post('/category_edit', [CategoryController::class, 'updateCategory'])->name('category_edit');


    // shoptype 
    Route::get('/shop_type', [ShopTypeController::class, 'index'])->name('shop_type');
    Route::post('/type_add', [ShopTypeController::class, 'newType'])->name('type_add');
    Route::post('/type_edit', [ShopTypeController::class, 'updateType'])->name('type_edit');


    // product 
    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::post('/products_add', [ProductController::class, 'addProduct'])->name('products_add');
    Route::post('/products_update', [ProductController::class, 'update'])->name('products_update');


    // cart 

    Route::post('/cart_add', [CartController::class, 'addCart'])->name('cart_add');


    // order 
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::post('/add_order', [OrderController::class, 'addOrder'])->name('add_order');
    Route::post('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');



    // send message 
    Route::post('/send_message', [RoomMessagesController::class, 'addMessage'])->name('send_message');


    // rate 

    Route::post('/add_rate', [RatingController::class, 'addRate'])->name('add_rate');

    // seller 
    Route::get('/seller/{id}', [UsersController::class, 'sellerShow'])->name('seller');

    Route::post('/support_document', [SupportDocumentController::class, 'uploadDocuments'])->name('support_document');


    Route::get('/messages/{inboxId}/inbox', [RoomMessagesController::class, 'index'])->name('messages');
    Route::post('/send-message', [RoomMessagesController::class, 'sendMessage'])->name('sendMessage');
    Route::get('/fetch-messages/{room_id}', [RoomMessagesController::class, 'fetchMessages'])->name('fetchMessages');


    Route::post('/changePassword', [UsersController::class, 'changePassword'])->name('changePassword');
});
