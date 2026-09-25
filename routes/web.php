<?php

use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\WebsiteSettingController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicPondController;
use App\Http\Controllers\User\CycleController as UserCycleController;
use App\Http\Controllers\User\NotificationController as UserNotificationController;
use App\Http\Controllers\User\PondController as UserPondController;
use App\Http\Controllers\User\ProductController as UserProductController;
use App\Http\Controllers\User\ShopProfileController;
use App\Http\Controllers\UserDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/tentang', [LandingController::class, 'about'])->name('landing.about');
Route::get('/kontak', [LandingController::class, 'contact'])->name('landing.contact');
Route::get('/katalog', [LandingController::class, 'catalog'])->name('landing.catalog');
Route::get('/katalog/produk/{product}', [LandingController::class, 'product'])->name('landing.product');
Route::get('/katalog/toko/{farmer}', [LandingController::class, 'shop'])->name('landing.shop');
Route::get('/artikel', [LandingController::class, 'blog'])->name('landing.blog');
Route::get('/artikel/{article:slug}', [LandingController::class, 'article'])->name('landing.article');
Route::get('/kolam/{token}', [PublicPondController::class, 'show'])->name('ponds.public');

Route::get('/dashboard', [UserDashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->prefix('app')->name('user.')->group(function () {
    Route::get('notifications', [UserNotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/read-all', [UserNotificationController::class, 'markAllRead'])->name('notifications.readAll');
    Route::post('notifications/{id}/read', [UserNotificationController::class, 'markRead'])->name('notifications.read');
});

Route::middleware(['auth', 'verified', 'role:pembudidaya|admin'])->prefix('app')->name('user.')->group(function () {
    Route::get('shop', [ShopProfileController::class, 'edit'])->name('shop.edit');
    Route::put('shop', [ShopProfileController::class, 'update'])->name('shop.update');
    Route::resource('ponds', UserPondController::class);
    Route::post('ponds/{pond}/deactivate', [UserPondController::class, 'deactivate'])->name('ponds.deactivate');
    Route::post('ponds/{pond}/activate', [UserPondController::class, 'activate'])->name('ponds.activate');
    Route::get('cycles', [UserCycleController::class, 'index'])->name('cycles.index');
    Route::get('cycles/create', [UserCycleController::class, 'create'])->name('cycles.create');
    Route::post('cycles', [UserCycleController::class, 'store'])->name('cycles.store');
    Route::get('cycles/{cycle}', [UserCycleController::class, 'show'])->name('cycles.show');
    Route::post('cycles/{cycle}/deactivate', [UserCycleController::class, 'deactivate'])->name('cycles.deactivate');
    Route::delete('cycles/{cycle}', [UserCycleController::class, 'destroy'])->name('cycles.destroy');
    Route::get('cycles/{cycle}/print', [UserCycleController::class, 'print'])->name('cycles.print');
    Route::get('cycles/{cycle}/water', [UserCycleController::class, 'water'])->name('cycles.water');
    Route::get('cycles/{cycle}/feeding', [UserCycleController::class, 'feeding'])->name('cycles.feeding');
    Route::get('cycles/{cycle}/mortality', [UserCycleController::class, 'mortality'])->name('cycles.mortality');
    Route::get('cycles/{cycle}/growth', [UserCycleController::class, 'growth'])->name('cycles.growth');
    Route::get('cycles/{cycle}/costs', [UserCycleController::class, 'costs'])->name('cycles.costs');
    Route::post('cycles/{cycle}/costs', [UserCycleController::class, 'storeCostEntry'])->name('cycles.costs.store');
    Route::delete('cycles/{cycle}/costs/{entry}', [UserCycleController::class, 'destroyCostEntry'])->name('cycles.costs.destroy');
    Route::post('cycles/{cycle}/water-quality', [UserCycleController::class, 'storeWaterQuality'])->name('cycles.water.store');
    Route::post('cycles/{cycle}/feeding', [UserCycleController::class, 'storeFeeding'])->name('cycles.feeding.store');
    Route::post('cycles/{cycle}/schedules', [UserCycleController::class, 'storeSchedule'])->name('cycles.schedules.store');
    Route::put('cycles/{cycle}/schedules/{schedule}', [UserCycleController::class, 'updateSchedule'])->name('cycles.schedules.update');
    Route::delete('cycles/{cycle}/schedules/{schedule}', [UserCycleController::class, 'destroySchedule'])->name('cycles.schedules.destroy');
    Route::post('cycles/{cycle}/mortality', [UserCycleController::class, 'storeMortality'])->name('cycles.mortality.store');
    Route::post('cycles/{cycle}/growth', [UserCycleController::class, 'storeGrowth'])->name('cycles.growth.store');
    Route::put('cycles/{cycle}/costs', [UserCycleController::class, 'updateCosts'])->name('cycles.costs.update');
    Route::get('products', [UserProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [UserProductController::class, 'create'])->name('products.create');
    Route::post('products', [UserProductController::class, 'store'])->name('products.store');
    Route::get('products/{product}/edit', [UserProductController::class, 'edit'])->name('products.edit');
    Route::put('products/{product}', [UserProductController::class, 'update'])->name('products.update');
    Route::delete('products/{product}', [UserProductController::class, 'destroy'])->name('products.destroy');
    Route::post('products/{product}/availability', [UserProductController::class, 'toggleAvailability'])->name('products.availability');
    Route::delete('products/{product}/photos/{photo}', [UserProductController::class, 'destroyPhoto'])->name('products.photos.destroy');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
    Route::get('/farmers', [AdminDashboardController::class, 'farmers'])->name('farmers');
    Route::get('/farmers/{farmer}', [AdminDashboardController::class, 'showFarmer'])->name('farmers.show');
    Route::put('/farmers/{farmer}', [AdminDashboardController::class, 'verifyFarmer'])->name('farmers.verify');
    Route::get('/products', [AdminDashboardController::class, 'products'])->name('products');
    Route::get('/products/{product}', [AdminDashboardController::class, 'showProduct'])->name('products.show');
    Route::put('/products/{product}', [AdminDashboardController::class, 'moderateProduct'])->name('products.moderate');
    Route::get('/ponds', [AdminDashboardController::class, 'ponds'])->name('ponds');
    Route::get('/cycles', [AdminDashboardController::class, 'cycles'])->name('cycles');
    Route::get('/water', [AdminDashboardController::class, 'water'])->name('water');
    Route::get('/species', [AdminDashboardController::class, 'species'])->name('species');
    Route::post('/species', [AdminDashboardController::class, 'storeSpecies'])->name('species.store');
    Route::get('/parameters', [AdminDashboardController::class, 'parameters'])->name('parameters');
    Route::put('/parameters/{parameter}', [AdminDashboardController::class, 'updateParameter'])->name('parameters.update');
    Route::put('/species-parameters/{parameter}', [AdminDashboardController::class, 'updateSpeciesParameter'])->name('species-parameters.update');
    Route::get('/rules', [AdminDashboardController::class, 'rules'])->name('rules');
    Route::post('/rules', [AdminDashboardController::class, 'storeRule'])->name('rules.store');
    Route::get('/settings/website', [WebsiteSettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings/website', [WebsiteSettingController::class, 'update'])->name('settings.update');
    Route::resource('blog', AdminBlogController::class)->except(['show']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
