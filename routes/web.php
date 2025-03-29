<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SupportFormController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use \App\Http\Controllers\FrontController;
use \App\Http\Controllers\ArticleCommentController;
use \App\Http\Controllers\LogController;


// terminale php artisan route:list yapılırsa bütün route lar gittiklere yere kadar gösteriliyor

//middleware ('auth') ile oturum açıkmı onun kontrolü yapıldı
Route::prefix('admin')->middleware('auth','verified')->group(function () {

    Route::middleware('isAdmin')->group(function () {
        Route::group(['prefix' => 'filemanager'], function () {
            \UniSharp\LaravelFilemanager\Lfm::routes();
        });

        Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);
        Route::get('logs-db', [LogController::class, 'index'])->name('dbLogs');
        Route::get('logs-db/{id}', [LogController::class, 'getLog'])->name('dbLogs.getLog')->whereNumber('id');
//        Route::get('logs2', [\Arcanedev\LogViewer\Http\Controllers\LogViewerController::class, 'index']);

        Route::get('/', [\App\Http\Controllers\AdminController::class, "index"])->name("admin.index");

        Route::get('articles', [ArticleController::class, 'index'])->name('articles.index');
        Route::get('articles/create', [ArticleController::class, 'create'])->name('articles.create');
        Route::post('articles/create', [ArticleController::class, 'store']);
        Route::post('articles/change-status', [ArticleController::class, 'changeStatus'])->name('articles.changeStatus');
        Route::get('articles/{id}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
        Route::post('articles/{id}/edit', [ArticleController::class, 'update']);
        Route::delete('articles/delete', [ArticleController::class, 'delete'])->name('articles.delete');

        Route::get('article/pending-approval', [ArticleCommentController::class, 'approvalList'])->name('articles.pending-approval');
        Route::get('article/comment-list', [ArticleCommentController::class, 'list'])->name('articles.comment.list');
        Route::post('article/pending-approval/changeStatus', [ArticleCommentController::class, 'changeStatus'])->name('articles.pending-approval.changeStatus');
        Route::delete('article/pending-approval/delete', [ArticleCommentController::class, 'delete'])->name('articles.pending-approval.delete');
        Route::post('articles/comment-restore', [ArticleCommentController::class, 'restore'])->name('articles.comment.restore');

        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('categories/create', [CategoryController::class, 'store']);
        Route::post('categories/change-status', [CategoryController::class, 'changeStatus'])->name('categories.changeStatus');
        Route::post('categories/change-feature-status', [CategoryController::class, 'changeFeatureStatus'])->name('categories.changeFeatureStatus');
        Route::post('categories/delete', [CategoryController::class, 'delete'])->name('categories.delete');
        Route::get('categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::post('categories/{id}/edit', [CategoryController::class, 'update']);

        Route::get('settings', [SettingsController::class, 'show'])->name('settings');
        Route::post('settings', [SettingsController::class, 'update']);

        Route::get('user', [UserController::class,'index'])->name('user.index');
        Route::get('user/create', [UserController::class, 'create'])->name('user.create');
        Route::post('user/create', [UserController::class, 'store']);
        Route::post('user/change-status', [UserController::class, 'changeStatus'])->name('user.changeStatus');
        Route::post('user/change-is_admin', [UserController::class, 'changeIsAdmin'])->name('user.changeIsAdmin');
        Route::get('user/{user:username}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::post('user/{user:username}/edit', [UserController::class, 'update']);
        Route::delete('user/delete', [UserController::class, 'delete'])->name('user.delete');
        Route::post('user/restore', [UserController::class, 'restore'])->name('user.restore');

        Route::get('email-themes', [EmailController::class, 'themes'])->name('admin.email-themes.index');
        Route::get('email-themes/create', [EmailController::class, 'create'])->name('admin.email-themes.create');
        Route::post('email-themes/create', [EmailController::class, 'store']);
        Route::get('email-themes/edit', [EmailController::class, 'edit'])->name('admin.email-themes.edit');
        Route::post('email-themes/edit', [EmailController::class, 'update']);
        Route::delete('email-themes/delete', [EmailController::class, 'delete'])->name('admin.email-themes.delete');
        Route::post('email-themes/changeStatus', [EmailController::class, 'changeStatus'])->name('admin.email-themes.changeStatus');

        Route::get('email-themes/assign', [EmailController::class, 'assignShow'])->name('admin.email-themes.assign');
        Route::get('email-themes/assign-list', [EmailController::class, 'assignList'])->name('admin.email-themes.assign-list');
        Route::get('email-themes/assign-list/show-email', [EmailController::class, 'showEmail'])->name('admin.email-themes.assign.show.email');
        Route::delete('email-themes/assign-list/delete', [EmailController::class, 'assignDelete'])->name('admin.email-themes.assign.delete');
        Route::post('email-themes/assign', [EmailController::class, 'assign']);
        Route::get('email-themes/assign/get-theme', [EmailController::class, 'assignGetTheme'])->name('admin.email-themes.assign.getTheme');

    });

    Route::post('articles/favorite', [ArticleController::class, 'favorite'])->name('articles.favorite');

    Route::post('articles/comment-favorite', [ArticleCommentController::class, 'favorite'])->name('articles.comment.favorite');
});
Route::get('admin/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('admin/login', [LoginController::class, 'login']);

Route::get('/login', [LoginController::class, 'showLoginUser'])->name('user.login');
Route::post('/login', [LoginController::class, 'loginUser']);
Route::post('/contact', [LoginController::class, ''])->name('contact');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('register', [LoginController::class, 'showRegister'])->name('register');
Route::post('register', [LoginController::class, 'register']);

Route::get('/', [FrontController::class, 'home'])->name('home');
Route::get('makaleler', [FrontController::class, 'articleList'])->name('front.articleList');
Route::get('/kategoriler/{category:slug}', [FrontController::class, 'category'])->name('front.categoryArticles');
Route::get('/yazarlar/{user:username}', [FrontController::class, 'authorArticles'])->name('front.authorArticles');
Route::get('/@{user:username}/{article:slug}', [FrontController::class, 'articleDetail'])->name('front.articleDetail');
Route::post('/{article:id}/makale-yorum', [FrontController::class, 'articleComment'])->name('article.comment');
Route::get('/arama', [FrontController::class, 'search'])->name('front.search');

Route::get('/parola-sıfırla', [LoginController::class, 'showPasswordReset'])->name('passwordReset');
Route::post('/parola-sıfırla', [LoginController::class, 'sendPasswordReset']);
Route::get('/parola-sıfırla/{token}', [LoginController::class, 'showPasswordResetConfirm'])->name('passwordResetToken');
Route::post('/parola-sıfırla/{token}', [LoginController::class, 'passwordReset']);

Route::get('/auth/verify/{token}', [LoginController::class, 'verify'])->name('verify-token');

Route::get('/auth/{driver}/callback', [LoginController::class, 'socialVerify'])->name('socialVerify');
Route::get('/auth/{driver}', [LoginController::class, 'socialLogin'])->name('socialLogin');

