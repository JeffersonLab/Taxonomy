<?php

Route::resource('taxonomy/vocabularies','Jlab\Taxonomy\Http\Controllers\VocabularyController')
    ->names([
        'index' => 'jlab.taxonomy.vocabulary.index',
        'store' => 'jlab.taxonomy.vocabulary.store',
        'create' => 'jlab.taxonomy.vocabulary.create',
        'show' => 'jlab.taxonomy.vocabulary.show',
        'destroy' => 'jlab.taxonomy.vocabulary.destroy',
        'update' => 'jlab.taxonomy.vocabulary.update',
        'edit' => 'jlab.taxonomy.vocabulary.edit',

    ]);;
