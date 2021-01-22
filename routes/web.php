<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware'=>'language'],function ()
{
    //your translation routes
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');
    Route::post('/', 'Portfolio\MailController@store')->name('mail');
    /*Route::get('/about', function () {
        return view('about');
    });*/
    Route::get('/about', 'Portfolio\AboutController@index')->name('about');
    Route::get('/portfolio', 'Portfolio\ItemController@index')->name('portfolio');

    Route::get('/portfolio/{slug}', 'Portfolio\ItemController@show')->name('portfolio-item');

    Route::get('/search', 'Portfolio\SearchController@index')->name('search');
});

Route::get('setlocale/{locale}',function($lang){
    \Session::put('locale',$lang);
    return redirect()->back();
});
