<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BlogsController;
use App\Http\Controllers\BranchesController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FlutterPaymentController;
use App\Http\Controllers\MembersController;
use App\Http\Controllers\MembershipCategoryController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PlansController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubscriptionsController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\UserManagerController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\Auth\VerificationController;
use App\Mail\welcomeNewMemberEmail;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/portal', function () {
    return view('welcome');
});

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Auth::routes(['verify' => true]);

// web pages - Guest pages 
Route::get('/', [App\Http\Controllers\WebPagesController::class, 'index'])->name('home.page');

Route::get('/blogs', [App\Http\Controllers\BlogsController::class, 'index'])->name('blogs.index');
// Route::get('blog/{slug}/{id}', [BlogsController::class, 'show'])->name('blog.show');
Route::get('/blog/{id}', [BlogsController::class, 'show'])->name('blog.show');

Route::get('/pay-form', [App\Http\Controllers\FlutterPaymentController::class, 'submitForm'])->name('pay-form.flutter');
Route::post('/pay-wave', [App\Http\Controllers\FlutterPaymentController::class, 'submitPayment'])->name('pay-wave.flutter');
Route::post('/pay/uganda-mobile-money', [App\Http\Controllers\FlutterPaymentController::class, 'chargeUgandaMobileMoney']);
Route::post('/flutterwave/webhook', [App\Http\Controllers\FlutterPaymentController::class, 'handleWebhook'])->name('flutterwave.webhook');
Route::get('/payments', [App\Http\Controllers\FlutterPaymentController::class, 'index'])->name('payments.index');



// Payment Routes
Route::get('/payments', function () {
    return view('payments.form');
})->name('payments.form');

Route::post('/payments/initialize', [FlutterPaymentController::class, 'initializePayment'])->name('payments.initialize');
Route::get('/payments/callback', [FlutterPaymentController::class, 'handleCallback'])->name('flutterwave.callback');
Route::get('/payments/verify/{reference}', [FlutterPaymentController::class, 'verifyTransaction'])->name('payments.verify');

// Payment Status Pages
Route::get('/payments/success', function () {
    return view('payments.success');
})->name('payments.success');

Route::get('/payments/failed', function () {
    return view('payments.failed');
})->name('payments.failed');

Route::get('/payments/error', function () {
    return view('payments.error');
})->name('payments.error');



Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/home'); // or any other route
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::get('email/verify', function () {
    return view('auth.verify');
})->middleware(['auth'])->name('verification.notice');

// ==============================================================================================================

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // post, categories, branches 
    Route::get('/all_posts', [App\Http\Controllers\BlogsController::class, 'allPosts'])->name('posts.all');
    Route::get('/posts/create', [App\Http\Controllers\BlogsController::class, 'create'])->name('post.create');
    Route::post('/posts/store', [App\Http\Controllers\BlogsController::class, 'store'])->name('post.store');
    Route::get('/posts/{blog}/show', [App\Http\Controllers\BlogsController::class, 'create'])->name('post.show');
    Route::get('/posts/{blog}/edit', [App\Http\Controllers\BlogsController::class, 'edit'])->name('post.edit');
    Route::put('/posts/{blog}', [App\Http\Controllers\BlogsController::class, 'update'])->name('post.update');

    Route::delete('/posts/{blog}', [App\Http\Controllers\BlogsController::class, 'destroy'])->name('post.destroy');

    Route::resource('categories', CategoryController::class);
    Route::resource('branches', BranchesController::class);

    // profile
    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [UserProfileController::class, 'update'])->name('profile.update');

    // plans
    Route::resource('plans', PlansController::class);
    Route::put('/plans/{id}/restore', [PlansController::class, 'restore'])->name('plans.restore');
    Route::get('plans/{plan}/subscribe', [PlansController::class, 'subscribe'])->name('plans.subscribe');

    // membership-categories
    Route::resource('membership-categories', MembershipCategoryController::class);
    // dealing with soft deleted records
    Route::put('/membership-categories/{id}/restore', [MembershipCategoryController::class, 'restore'])->name('membership-categories.restore');
    Route::delete('/membership-categories/{id}', [MembershipCategoryController::class, 'destroy'])->name('membership-categories.destroy');
    Route::delete('/membership-categories/{id}', [MembershipCategoryController::class, 'forcedDelete'])->name('membership-categories.forcedDelete');

    // subscriptions
    Route::resource('subscriptions', SubscriptionsController::class);

    // payment
    Route::get('payment/{plan}', [PaymentsController::class, 'showPaymentPage'])->name('payment.page');
    Route::post('payment/{order}/process', [PaymentsController::class, 'processPayment'])->name('payment.process');
    Route::get('payment/success/{order}', [PaymentsController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('payment/failed/{order}', [PaymentsController::class, 'paymentFailed'])->name('payment.failed');

    Route::post('/payment', [PaymentsController::class, 'subscriptionPayment'])->name('payment.subscription');
    Route::get('/callback', [PaymentsController::class, 'handleCallback'])->name('payment.callback');
    Route::get('/handleIPN', [PaymentsController::class, 'handleIPN'])->name('payment.handleIPN');

    // upgrade plan
    Route::get('/plans/upgrade/{plan_id}', [PlansController::class, 'upgrade'])->name('plans.upgrade');

    // Order
    Route::get('/orders', [OrdersController::class, 'show'])->name('orders.show');

    // transactions
    Route::resource('/transactions', TransactionsController::class);

    // members
    Route::resource('/members', MembersController::class);

    // manage user
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);

    Route::resource('permissions', PermissionController::class)->except(['show']);
    Route::get('permissions/{permission}/assign', [PermissionController::class, 'assignForm'])->name('permissions.assign.form');
    Route::post('permissions/{permission}/assign', [PermissionController::class, 'assignToRole'])->name('permissions.assign');

    Route::get('users', [UserManagerController::class, 'index'])->name('users.index');
    Route::get('users/{user}/edit', [UserManagerController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [UserManagerController::class, 'update'])->name('users.update');
});
