<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::group(['middleware' => 'auth', 'namespace' => 'App\Http\Controllers'], function () {
    /* Main */
        Route::get('/', 'MainController@index')->name('main.index');
        Route::get('/products', 'ProductController@index')->name('product.index');

    /* Products */
        /* Positions */
        Route::post('/products/position/store', 'ProductController@storePosition')->name('product.position.store');
        Route::delete('/products/position/delete', 'ProductController@deletePosition')->name('product.position.delete');
        /**/

        /* Categories */
        Route::get('/products/category/{query}', 'ProductController@searchCategory')->name('product.category.search');
    /* ... */

    /* Dishes */
        Route::get('/dishes', 'DishController@index')->name('dish.index');
        Route::post('/dishes/store', 'DishController@store')->name('dish.store');
        Route::delete('/dishes/delete', 'DishController@delete')->name('dish.delete');
        Route::get('/dishes/search/product', 'DishController@searchProduct')->name('dish.search.product');

    /* Schedule */
        Route::get('/schedule', 'ScheduleController@index')->name('schedule.index');
        Route::post('/schedule/store', 'ScheduleController@store')->name('schedule.store');
        Route::delete('/schedule/delete', 'ScheduleController@delete')->name('schedule.delete');
});

require __DIR__.'/auth.php';
