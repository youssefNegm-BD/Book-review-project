<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post("/login",[AuthController::class,"login"] );
Route::post("/register",[AuthController::class,"register"] );
Route::middleware(["auth:sanctum"])->group(function () {

    Route::post("/logout",[AuthController::class,"logout"] );
    Route::get("/profile",[AuthController::class,"profile"] );

    Route::apiResource("books",BookController::class);
    Route::apiResource("reviews",ReviewController::class);
    Route::get("book_reviews/{book_id}",[ReviewController::class,"show"]);

});





Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




