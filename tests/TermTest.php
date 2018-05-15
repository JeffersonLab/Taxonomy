<?php

namespace Jlab\Taxonomy\Tests;

use Jlab\Taxonomy\Term;
use Jlab\Taxonomy\Vocabulary;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;


class TermTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        DB::table('terms')->delete();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_can_save_a_description()
    {
        $term = factory(Term::class)->create();
        $term->description = 'This is the Description';
        $term->save();

        $found = Term::find($term->id);
        $this->assertEquals($term->description, $found->description);
    }

    function test_it_can_retrieve_vocabulary(){

        $term = factory(Term::class)->create();
        $this->assertInstanceOf(Vocabulary::class, $term->vocabulary);
    }

    function test_validator_rules(){

        $vocab = factory(Vocabulary::class)->create();

        // Begin with known good - minimal
        $validator = Validator::make(['name'=>'GoodName', 'vocabulary_id' => $vocab->id], Term::$rules);
        $this->assertTrue($validator->passes());

        // Begin with known good - maximal
        $validator = Validator::make(
            [   'name'=>'GoodName',
                'vocabulary_id' => $vocab->id,
                'url' => 'https://logbooks.jlab.org/entries#foo',
                'Description is good too.'
            ], Term::$rules);
        $this->assertTrue($validator->passes());

        // Missing name
        $validator = Validator::make(['name'=>'', 'vocabulary_id' => $vocab->id], Term::$rules);
        $this->assertTrue($validator->fails());

        // Name Too Long
        $longName = str_pad('X',81);
        $validator = Validator::make([
            'name'=>$longName,
            'vocabulary_id' => $vocab->id], Term::$rules);
        $this->assertTrue($validator->fails());

        // Description Too Long
        $longDesc = str_pad('X',1001);
        $validator = Validator::make(['name'=>'GoodName',
            'vocabulary_id' => $vocab->id,
            'description'=>$longDesc], Term::$rules);
        $this->assertTrue($validator->fails());

        // Url Too Long
        $longurl = str_pad('X',1001);
        $validator = Validator::make(['name'=>'GoodName',
            'vocabulary_id' => $vocab->id,
            'url'=>$longurl], Term::$rules);
        $this->assertTrue($validator->fails());

        // Url not valid
        $invalidUrl = 'htp:NotAUrl';
        $validator = Validator::make(['name'=>'GoodName',
            'vocabulary_id' => $vocab->id,
            'url'=>$invalidUrl], Term::$rules);
        $this->assertTrue($validator->fails());

    }


    function test_it_can_make_terms_from_text(){
        $vocab1 = factory(Vocabulary::class)->create();
        $test1 = "Term1 | Desc Number One \n Term2 | Desc Number Two";
        $created = Term::makeFromText($test1,$vocab1->id);

        $this->assertInstanceOf(Collection::class, $created);
        $this->assertCount(2, $created);
        $this->assertEquals('Term1', $created->first()->name);
        $this->assertEquals('Desc Number One', $created->first()->description);
        $this->assertEquals('Term2', $created->last()->name);
        $this->assertEquals('Desc Number Two', $created->last()->description);
        $vocab1->fresh();
        $this->assertCount(2, $vocab1->terms);
    }

    function test_terms_from_text_descriptions_are_optional(){
        $vocab1 = factory(Vocabulary::class)->create();
        $test1 = "Term1 \n Term2 ";
        $created = Term::makeFromText($test1,$vocab1->id);

        $this->assertInstanceOf(Collection::class, $created);
        $this->assertCount(2, $created);
        $this->assertEquals('Term1', $created->first()->name);
        $this->assertEquals('', $created->first()->description);
        $this->assertEquals('Term2', $created->last()->name);
        $this->assertEquals('', $created->last()->description);
        $vocab1->fresh();
        $this->assertCount(2, $vocab1->terms);
    }


    function test_it_can_make_child_terms_from_text(){
        $parent = factory(Term::class)->create();
        $test1 = "Term1 | Desc Number One \n Term2 | Desc Number Two";
        $created = Term::makeChildrenFromText($test1,$parent);
        $this->assertInstanceOf(Collection::class, $created);
        $this->assertCount(2, $created);
        $this->assertEquals('Term1', $created->first()->name);
        $this->assertEquals('Desc Number One', $created->first()->description);
        $this->assertEquals('Term2', $created->last()->name);
        $this->assertEquals('Desc Number Two', $created->last()->description);

        $parent->fresh();
        $this->assertCount(2, $parent->children);
    }


    function test_children_are_sorted_by_insertion_order(){
        $parent = factory(Term::class)->create();
        $parent->children()->save(new Term(['name'=>'Alpha','weight'=>30,'vocabulary_id'=>$parent->vocabulary_id]));
        $parent->children()->save(new Term(['name'=>'Beta','weight'=>20,'vocabulary_id'=>$parent->vocabulary_id]));
        $parent->children()->save(new Term(['name'=>'Gamma','weight'=>10,'vocabulary_id'=>$parent->vocabulary_id]));
        $parent->fresh();
        $this->assertCount(3, $parent->children);
        $this->assertEquals('Alpha', $parent->children->first()->name);
        $this->assertEquals('Gamma', $parent->children->last()->name);
    }


    /**
     * Exercise rule that term names must be unique within
     * a vocabulary.
     */
    function test_unique_names_in_vocabulary(){

        $vocab1 = factory(Vocabulary::class)->create();
        $vocab2 = factory(Vocabulary::class)->create();

        factory(Term::class)
            ->create(['name'=>'foobar', 'vocabulary_id'=>$vocab1->id]);

        // In a different vocab is ok
        $term2 = factory(Term::class)
            ->make(['name'=>'foobar', 'vocabulary_id'=>$vocab2->id]);
        $this->assertNotFalse($term2->save());

        // The database should block adding a duplicate
        $this->expectException(\Exception::class);
        factory(Term::class)
            ->create(['name'=>'foobar', 'vocabulary_id'=>$vocab1->id]);

    }



}
