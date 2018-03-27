<?php

Route::get('/auth/login', 'Auth\CustomAuthController@login');
Route::post('/auth/login', 'Auth\CustomAuthController@checkLogin');
Route::get('/auth/register', 'Auth\CustomAuthController@register');
Route::post('/auth/register', 'Auth\CustomAuthController@checkRegister');
Route::get('/auth/logout', 'Auth\CustomAuthController@logout');


Route::group(['prefix' => 'back', 'middleware' => 'auth'], function () {

    Route::get('/set-language/{lang}', 'LanguagesController@set')->name('set.language');

    Route::get('/', function() {
        return view('admin.welcome');
    });

    Route::get('/users', 'Admin\AdminUserController@index');


    Route::resource('/pages', 'Admin\PagesController');
    Route::patch('/pages/{id}/change-status', 'Admin\PagesController@status')->name('pages.change.status');

    Route::resource('/modules', 'Admin\ModulesController');

    Route::resource('submodules', 'Admin\SubModulesController');

    Route::resource('/forms', 'Admin\FormsController');

    Route::resource('/categories', 'Admin\CategoriesController');
    Route::post('/categories/change', 'Admin\CategoriesController@change')->name('categories.change');
    Route::post('/categories/part', 'Admin\CategoriesController@partialSave')->name('categories.partial.save');

    Route::resource('/tags', 'Admin\TagsController');

    Route::resource('/posts', 'Admin\PostsController');

    Route::group(['prefix' => 'settings'], function () {

        Route::resource('/languages', 'Admin\LanguagesController');
        Route::patch('/languages/set-default/{id}', 'Admin\LanguagesController@setDefault')->name('languages.default');

        Route::get('/reviews', 'Admin\PostsRatingController@index')->name('reviews.index');
        Route::patch('/reviews', 'Admin\PostsRatingController@update')->name('reviews.update');

    });


});
