<?php

Route::get('/categories', function () {




    return \App\Models\Category::where('level', 1)->get();



});
/*
 * =====Temporary
 */


Route::get('/form', 'TemporarController@index');
/*
 * =====End temporary
 */
//form action="{{ rouite('add.page.image', $page->id') }}" />
//Route::post('/page/{id}/images', function () {
//    $images = request('images');
//
//    foreach ()
//})->name('add.page.image');

Route::get('/auth/login', 'Auth\CustomAuthController@login');
Route::post('/auth/login', 'Auth\CustomAuthController@checkLogin');
Route::get('/auth/register', 'Auth\CustomAuthController@register');
Route::post('/auth/register', 'Auth\CustomAuthController@checkRegister');
Route::get('/auth/logout', 'Auth\CustomAuthController@logout');


Route::group(['prefix' => 'back', 'middleware' => 'auth'], function () {

    Route::get('/set-language/{lang}', 'LanguagesController@set')->name('set.language');

    Route::get('/', 'Admin\DefaultController@index');


    Route::resource('/pages', 'Admin\PagesController');
    Route::patch('/pages/{id}/change-status', 'Admin\PagesController@status')->name('pages.change.status');

    Route::resource('/modules', 'Admin\ModulesController');
    Route::resource('/forms', 'Admin\FormsController');

    Route::resource('/categories', 'Admin\CategoriesController');
    Route::post('/categories/change', 'Admin\CategoriesController@change')->name('categories.change');
    Route::post('/categories/part', 'Admin\CategoriesController@partialSave')->name('categories.partial.save');

    Route::resource('/tags', 'Admin\TagsController');

    Route::resource('/posts', 'Admin\PostsController');

    Route::group(['prefix' => 'settings'], function () {

        Route::resource('/languages', 'Admin\LanguagesController');
        Route::patch('/languages/set-default/{id}', 'Admin\LanguagesController@default')->name('languages.default');

    });


});


//
//Route::group([
//    'prefix' => '{lang?}',
//], function () {
//    Route::group([
//        'prefix' => 'back',
//    ], function () {
//
//        Route::get('/platform/pages', 'Admin\PlatformController@index');
//        Route::get('/platform/update', 'Admin\PlatformController@update');
//        Route::any('/upload', 'FileController@upload');
//        Route::get('/', 'Admin\DefaultController@index');
////        Route::any('/{module}/{submenu?}/{action?}/{id?}/{lang_id?}', ['uses' => 'RoleManager@routeResponder']);
//
//    });
//
//
//    Route::group([], function () {
//
//        // all pages
//        Route::get('/', 'Front\PagesController@index');
//
//        Route::group(['middleware' => 'auth_front'], function () {
//            // for users
//        });
//
//        // temp Route
//        // Route::get('/register', 'Front\UserController@register');
//        // Route::post('/registerPost', 'Front\UserController@postRegister');
//
//    });
//
//});
