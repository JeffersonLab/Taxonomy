<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVocabulariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('vocabularies', function(Blueprint $table) {
            $table->increments('id');

            $table->string('name',80);
            $table->string('description',1000)->nullable();

            // We will allow the vocabulary owner to tweak the number
            // of columns used when the vocabulary is presented in a
            // form as checkboxes.
            $table->string('column_class')->nullable();

            $table->unique('name');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('vocabularies');
    }
}
