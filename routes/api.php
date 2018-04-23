<?php

// Note that earlier routes get a preference.

Route::get('taxonomy/terms', [
'as' => 'api.terms',
'uses' => 'Jlab\Taxonomy\Http\Controllers\ApiController@terms'
]);

Route::post('taxonomy/terms', [
'as' => 'api.terms.create',
'uses' => 'Jlab\Taxonomy\Http\Controllers\ApiController@termsCreate'
]);

Route::post('taxonomy/terms/sort', [
'as' => 'api.terms.sort',
'uses' => 'Jlab\Taxonomy\Http\Controllers\ApiController@termsSort'
]);


Route::put('taxonomy/terms/{id}', [
'as' => 'api.terms.update',
'uses' => 'Jlab\Taxonomy\Http\Controllers\ApiController@termsUpdate'
]);

Route::put('taxonomy/terms/{id}/users', [
'as' => 'api.terms.users',
'uses' => 'Jlab\Taxonomy\Http\Controllers\ApiController@termUsers'
]);

Route::get('taxonomy/terms/{id}', [
'as' => 'api.terms.item',
'uses' => 'Jlab\Taxonomy\Http\Controllers\ApiController@termsItem'
]);

Route::get('taxonomy/forms', [
    'as' => 'api.forms',
    'uses' => 'Jlab\Taxonomy\Http\Controllers\ApiController@forms'
]);