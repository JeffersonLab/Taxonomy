<?php

// Note that earlier routes get a preference.

Route::get('taxonomy/terms', [
'as' => 'jlab.taxonomy.api.terms',
'uses' => 'Jlab\Taxonomy\Http\Controllers\ApiController@terms'
]);

Route::post('taxonomy/terms', [
'as' => 'jlab.taxonomy.api.terms.create',
'uses' => 'Jlab\Taxonomy\Http\Controllers\ApiController@termsCreate'
]);

Route::post('taxonomy/terms/sort', [
'as' => 'jlab.taxonomy.api.terms.sort',
'uses' => 'Jlab\Taxonomy\Http\Controllers\ApiController@termsSort'
]);


Route::put('taxonomy/terms/{id}', [
'as' => 'jlab.taxonomy.api.terms.update',
'uses' => 'Jlab\Taxonomy\Http\Controllers\ApiController@termsUpdate'
]);

Route::delete('taxonomy/terms/{id}', [
    'as' => 'jlab.taxonomy.api.terms.delete',
    'uses' => 'Jlab\Taxonomy\Http\Controllers\ApiController@termsDelete'
]);

Route::put('taxonomy/terms/{id}/users', [
'as' => 'jlab.taxonomy.api.terms.users',
'uses' => 'Jlab\Taxonomy\Http\Controllers\ApiController@termUsers'
]);

Route::get('taxonomy/terms/{id}', [
'as' => 'jlab.taxonomy.api.terms.item',
'uses' => 'Jlab\Taxonomy\Http\Controllers\ApiController@termsItem'
]);

Route::get('taxonomy/forms', [
    'as' => 'jlab.taxonomy.api.forms',
    'uses' => 'Jlab\Taxonomy\Http\Controllers\ApiController@forms'
]);

