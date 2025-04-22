<?php

use App\Http\Controllers\ArticleCategoryController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SubscriberController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'getVersion']);

Route::controller(AuthController::class)
    ->group(function () {
        //TODO: add auth endpoints
    });

Route::controller(SubscriberController::class)
    ->group(function () {
        //TODO: add subscriber endpoints
    });

Route::controller(ArticleController::class)
    ->group(function () {
        //TODO: add article endpoints
    });

Route::controller(ArticleCategoryController::class)
    ->group(function () {
        //TODO: add article category endpoints
    });

Route::prefix('api')->group(function () {
    Route::apiResource('articles', ArticleController::class);
    Route::post('articles/{uuid}/publish', [ArticleController::class, 'publish']);
});
