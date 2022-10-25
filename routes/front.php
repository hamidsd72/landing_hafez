<?php

use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\Front\ProductController;
use App\Http\Controllers\Front\ContactController;
use App\Http\Controllers\Front\EventController;
use App\Http\Controllers\Front\ArticleController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix'=>'','namespace'=>'Front'],function () {


    Route::get('/sss',function () {
        return dirname(dirname(__FILE__ )).'/app/Components/helpers.php';
        return assets('/');
    });

    Route::get('/',[FrontController::class,'index'])->name('front-index');

    Route::post('checkphone',[FrontController::class,'checkphone'])->name('front-checkphone');

    Route::get('getcode/{id}/{phone}',[FrontController::class,'getcode'])->name('front-getcode');

    Route::post('CodeCheck',[FrontController::class,'checkCode'])->name('front-code-check');

});
