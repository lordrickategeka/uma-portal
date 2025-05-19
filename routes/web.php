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
use App\Http\Controllers\EventController;
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\NewsNotificationController;
use App\Http\Controllers\SubaccountController;
use App\Http\Controllers\SubscribersController;
use App\Models\Blog;
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
Route::get('/contact', [App\Http\Controllers\WebPagesController::class, 'contactPage'])->name('contact.page');
Route::post('/contact', [App\Http\Controllers\WebPagesController::class, 'submit'])->name('contact.submit');

Route::get('/Continuing-Professional-Development-cpd', function (Blog $blog) {
    return view('web.pages.cpd_page', compact('blog'));
})->name('cpd.page');

Route::get('/blogs', [App\Http\Controllers\BlogsController::class, 'index'])->name('blogs.index');
// Route::get('blog/{slug}/{id}', [BlogsController::class, 'show'])->name('blog.show');
Route::get('/blog/{id}/{slug}', [BlogsController::class, 'show'])->name('blog.show');

Route::get('/pay-form', [App\Http\Controllers\FlutterPaymentController::class, 'submitForm'])->name('pay-form.flutter');
Route::post('/pay-wave', [App\Http\Controllers\FlutterPaymentController::class, 'submitPayment'])->name('pay-wave.flutter');
Route::post('/pay/uganda-mobile-money', [App\Http\Controllers\FlutterPaymentController::class, 'chargeUgandaMobileMoney']);
Route::post('/flutterwave/webhook', [App\Http\Controllers\FlutterPaymentController::class, 'handleWebhook'])->name('flutterwave.webhook');
Route::get('/payments', [App\Http\Controllers\FlutterPaymentController::class, 'index'])->name('payments.index');

// news letter subscriber
Route::post('/subscribe', [SubscribersController::class, 'subscribe'])->name('subscription.submit');
Route::get('/unsubscribe/{token}', [SubscribersController::class, 'unsubscribe'])->name('subscription.unsubscribe');

// Payment Routes
Route::get('/payments', function () {
    return view('payments.form');
})->name('payments.form');

// Payment Status Pages
Route::get('/payments/success/{reference}', function () {
    return view('payments.success');
})->name('payments.success');

Route::get('/payments/failed/{reference}', function () {
    return view('payments.failed');
})->name('payments.failed');

Route::get('/payments/error', function () {
    return view('payments.error');
})->name('payments.error');

// flutterwave other routes
Route::get('/payments/callback', [FlutterPaymentController::class, 'handleCallback'])->name('flutterwave.callback');
Route::get('/payments/verify/{reference}', [FlutterPaymentController::class, 'verifyTransaction'])->name('payments.verify');
Route::post('/flutterwave/webhook', [FlutterPaymentController::class, 'handleWebhook']);
Route::get('/payments/cancelled/{reference}', [FlutterPaymentController::class, 'showCancelledPayment'])->name('payments.cancelled');


Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/home'); // or any other route
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::get('email/verify', function () {
    return view('auth.verify');
})->middleware(['auth'])->name('verification.notice');

// Events
    Route::get('/events', [EventController::class,'webAllEvents'])->name('events.webAllEvents');
    Route::get('/events/all', [EventController::class, 'getAllPostsWithTypeEvent'])->name('events.all');
    Route::get('/events/upcoming', [EventController::class,'upcoming'])->name('events.upcoming');
    Route::get('/events/past', [EventController::class,'past'])->name('events.past');
    Route::get('/events/calendar', [EventController::class,' calendar'])->name('events.calendar');
    Route::get('/events/{slug}', [EventController::class, 'showEvent'])->name('events.showEvent');


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
    Route::post('/posts/bulk-delete', [App\Http\Controllers\BlogsController::class, 'bulkDelete'])->name('post.bulkDelete');


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
    Route::match(['get', 'post'], '/plans/{plan_id}/subscribe', [PlansController::class, 'subscribe'])->name('plans.subscribe');

    // membership-categories
    Route::resource('membership-categories', MembershipCategoryController::class);
    // dealing with soft deleted records
    Route::put('/membership-categories/{id}/restore', [MembershipCategoryController::class, 'restore'])->name('membership-categories.restore');
    Route::delete('/membership-categories/{id}', [MembershipCategoryController::class, 'destroy'])->name('membership-categories.destroy');
    Route::delete('/membership-categories/{id}', [MembershipCategoryController::class, 'forcedDelete'])->name('membership-categories.forcedDelete');

    // members
    // Route::resource('/members', MembersController::class);
    // all memmbers /users with active plans
    Route::get('/members', [MembersController::class, 'index'])->name('members.index');
    Route::get('/members/active-members', [MembersController::class, 'activeMembers'])->name('members.active');
    Route::get('/members/pending-members', [MembersController::class, 'inactiveMembers'])->name('members.inactive');

    Route::get('/admin/members/create', [RegisterController::class, 'showCreateMemberForm'])->name('admin.members.create');
    Route::post('/admin/members/create', [RegisterController::class, 'createMember'])->name('admin.members.store');

    Route::get('/members/{user}', [MembersController::class, 'show'])->name('members.show');
    Route::get('/members/{user}/edit', [MembersController::class, 'edit'])->name('members.edit');
    Route::delete('/members/{user}', [MembersController::class, 'destroy'])->name('members.destroy');
    Route::get('/members/{user}/payments', [MembersController::class, 'payments'])->name('members.payments');


    // subscriptions
    Route::resource('subscriptions', SubscriptionsController::class);

    // payment
    Route::match(['get', 'post'], 'payment/{plan}', [PaymentsController::class, 'showPaymentPage'])->name('payment.page');
    Route::post('payment/{order}/process', [PaymentsController::class, 'processPayment'])->name('payment.process');
    Route::get('payment/success/{order}', [PaymentsController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('payment/failed/{order}', [PaymentsController::class, 'paymentFailed'])->name('payment.failed');

    // pesapal payment redirect to form
    Route::post('/payment', [PaymentsController::class, 'subscriptionPayment'])->name('payment.subscription');
    Route::get('/callback', [PaymentsController::class, 'handleCallback'])->name('payment.callback');
    Route::get('/handleIPN', [PaymentsController::class, 'handleIPN'])->name('payment.handleIPN');

    // installment payment
    Route::get('installments', [InstallmentController::class, 'index'])->name('installments.index');
    Route::get('/installments/{id}', [InstallmentController::class, 'show'])->name('installments.show');
    Route::get('/installment/make-next-payment/{id}', [InstallmentController::class, 'makeNextPayment'])->name('installment.make-next-payment');

    // flutterwave payment redirect to form
    Route::match(['get', 'post'], '/payments/initialize', [FlutterPaymentController::class, 'initializePaymentFlutter'])->name('payments.initialize');
    Route::get('/payments/continue/{transaction_id}', [FlutterPaymentController::class, 'continuePayment'])
        ->name('payments.continue');
    Route::get('/payments/retry/{transaction_id}', [FlutterPaymentController::class, 'retryPayment'])->name('payments.retry');
    

    // creating sub-accounts on flutterwave
    Route::post('/subaccounts/create', [SubaccountController::class, 'createSubaccount']);
    Route::get('/subaccounts/create', [SubaccountController::class, 'create'])->name('subaccounts.create');
    Route::post('/subaccounts/store', [SubaccountController::class, 'store'])->name('subaccounts.store');
    Route::get('/subaccounts/{id}', [SubAccountController::class, 'edit'])->name('subaccounts.edit');
    Route::put('/subaccounts/{id}', [SubAccountController::class, 'update'])->name('subaccounts.update');


    // upgrade plan
    Route::get('/plans/upgrade/{plan_id}', [PlansController::class, 'upgrade'])->name('plans.upgrade');

    // Order
    Route::get('/orders', [OrdersController::class, 'show'])->name('orders.show');

    // transactions
    Route::get('/transactions', [TransactionsController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [TransactionsController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/export', [TransactionsController::class, 'export'])->name('transactions.export');

    // manage user -roles, -permissions
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);

    Route::resource('permissions', PermissionController::class)->except(['show']);
    Route::get('permissions/{permission}/assign', [PermissionController::class, 'assignForm'])->name('permissions.assign.form');
    Route::post('permissions/{permission}/assign', [PermissionController::class, 'assignToRole'])->name('permissions.assign');

    Route::get('users', [UserManagerController::class, 'index'])->name('users.index');
    Route::get('users/{user}/edit', [UserManagerController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [UserManagerController::class, 'update'])->name('users.update');

    // import users
    Route::get('/users/import', [UserManagerController::class, 'showImportForm'])->name('users.import-form');
    Route::post('/users/import', [UserManagerController::class, 'import'])->name('users.import');
    Route::get('/users/download-template', [UserManagerController::class, 'downloadTemplate'])->name('users.download-template');


    // dashboard events management
    Route::get('/all_events', [EventController::class,'allEvents'])->name('events.allEvents');
    Route::get('/show_events', [EventController::class,'show'])->name('events.show');
    Route::get('/edit_events/{id}', [EventController::class,'edit'])->name('events.edit');
    Route::put('/update_events/{event}', [EventController::class,'update'])->name('events.update');
    Route::delete('/delete_events/{id}', [EventController::class,'show'])->name('event.destroy');
    

    // Add a custom route for publishing an event
    Route::post('events/{event}/publish', [EventController::class, 'publish'])->name('events.publish');

    // news Notifications
    Route::get('/notifications', [NewsNotificationController::class, 'index'])
        ->name('notifications.index');

    Route::get('/notifications/post/{blogId}', [NewsNotificationController::class, 'sendPostNotification'])
        ->name('notifications.send-post');

    Route::get('/notifications/event/{blogId}', [NewsNotificationController::class, 'sendEventNotification'])
        ->name('notifications.send-event');

    Route::get('/notifications/send-all', [NewsNotificationController::class, 'sendAllNotifications'])
        ->name('notifications.send-all');

    // subscribers
    Route::get('/subscribers', [SubscribersController::class, 'index'])->name('subscribers.index');
    Route::get('/unsubscribe/{token}', [SubscribersController::class, 'unsubscribe'])->name('subscriber.unsubscribe');
    Route::delete('/subscribers/{subscriber}', [SubscribersController::class, 'destroy'])->name('subscribers.destroy');
});
