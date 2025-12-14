<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\bookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use App\Http\Middleware\CheckAdmin;




Route::get('/',[HomeController::class,'index'])->name('home');
// Route::post('/save-review/{id}',[HomeController::class,'saveReview'])->name('book.saveReview2');
Route::get('/book/{id}',[HomeController::class,'detail'])->name('book.detail');

Route::post('/save-review/{book_id}',[HomeController::class,'saveReview'])->name('book.saveReview');





Route::group(['prefix'=>'account'],function(){
    Route::group(['middleware'=>'guest'],function(){
        Route::get('register',[AccountController::class,'register'])->name('account.register');
        Route::post('register',[AccountController::class,'processRegister'])->name('account.processRegister');
        Route::get('login',[AccountController::class,'login'])->name('account.login');
        Route::post('login',[AccountController::class,'authenticate'])->name('account.authenticate');


    });
    Route::group(['middleware'=>'auth'],function(){
        Route::get('profile',[AccountController::class,'profile'])->name('account.profile');
        Route::get('logout',[AccountController::class,'logout'])->name('account.logout');
        Route::post('updateProfile',[AccountController::class,'updateProfile'])->name('account.updateProfile');

        Route::group(['middleware'=>'check-admin'],function(){
            Route::get('books',[bookController::class,'index'])->name('books.index');
            Route::get('books/create',[bookController::class,'create'])->name('books.create');
            Route::post('books/store',[bookController::class,'store'])->name('books.store');
            Route::get('books/edit/{id}',[bookController::class,'edit'])->name('books.edit');
            Route::post('books/edit/{id}',[bookController::class,'update'])->name('books.update');
            Route::get('books/delete/{id}',[bookController::class,'destroy'])->name('books.destroy');
    
            Route::get('reviews',[ReviewController::class,'index'])->name('account.reviews');
            Route::get('reviews/{id}',[ReviewController::class,'edit'])->name('account.reviews.edit');
            Route::post('reviews/{id}',[ReviewController::class,'updateReview'])->name('account.reviews.updateReview');
            Route::post('delete-review',[ReviewController::class,'deleteReview'])->name('account.reviews.deleteReview');    
        });

        Route::get('myReviews',[AccountController::class,'myReview'])->name('account.myReview');
        Route::get('my-reviews/{id}',[AccountController::class,'editMyReviews'])->name('account.myReview.editMyReviews');
        Route::post('my-reviews/{id}',[AccountController::class,'updateMyReview'])->name('account.myReview.updateMyReview');
        Route::post('delete-Myreview',[AccountController::class,'deleteMR'])->name('account.reviews.deleteMR');


        Route::get('change',[AccountController::class,'changePass'])->name('account.changePass');
        Route::post('auth-pass',[AccountController::class,'authPass'])->name('account.authPass');

    });
});
