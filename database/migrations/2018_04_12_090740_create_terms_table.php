<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTermsTable extends Migration
{
    /**
     * /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('vocabulary_id');
            $table->string('name', 80);
            $table->string('url', 1000)->nullable();
            $table->string('description', 1000)->nullable();
            $table->integer('weight')->default(0);


            // Column to use when moving data from old
            // single-purpose tables to taxonomy terms
            // it will hold the primary key of the old
            // table.
            $table->unsignedInteger('legacy_id')->nullable();


            //NestedSet package hierarchy columns
            $table->unsignedInteger('left')->default(0);
            $table->unsignedInteger('right')->default(0);
            $table->unsignedInteger('parent_id')->nullable();

            $table->timestamps();

            $table->foreign('vocabulary_id')
                ->references('id')->on('vocabularies')
                ->onDelete('cascade');

            $table->index('name');
            $table->index('vocabulary_id');

            $table->unique(['vocabulary_id', 'name']);

            $table->index(['left', 'right', 'parent_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('terms');
    }

}
