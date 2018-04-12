<?php

namespace Tests;

use JLab\Taxonomy\Term;
use Jlab\Taxonomy\Vocabulary;
use Illuminate\Support\Facades\Validator;


class VocabularyTest extends TestCase
{


    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_can_save_a_description()
    {
        $vocab = factory(\Jlab\Taxonomy\Vocabulary::class)->create();
        $vocab->description = 'This is the Description';
        $vocab->save();

        $found = Vocabulary::find($vocab->id);
        $this->assertEquals($vocab->description, $found->description);
    }


    public function test_it_can_add_and_fetch_terms(){
        $terms = factory(\Jlab\Taxonomy\Term::class, 3)->make();
        $vocab = factory(\Jlab\Taxonomy\Vocabulary::class)->create();
        $saved =$vocab->terms()->saveMany($terms);
        $this->assertEquals($vocab->id, $saved->first()->vocabulary_id);

        $found = Vocabulary::with('terms')->find($vocab->id);
        $this->assertCount(3, $found->terms);

    }

    public function test_readme_example(){
        // part 1
        $vocabulary = new Vocabulary(['name' => 'Music', 'description' => "Musical Generes"]);
        $vocabulary->save();
        $parentTerm = new Term(['name'=>'Classical']);
        $vocabulary->terms()->save($parentTerm);
        $parentTerm->addChild(new Term(['name' => 'Concertos']));
        $parentTerm->addChild(new Term(['name' => 'Sonatas']));
        $this->assertCount(2, $parentTerm->children);

        // part 2
        $vocabulary = Vocabulary::where('name', 'Music')->first();
        $this->assertEquals('Music', $vocabulary->name);
        $rootNode = $vocabulary->rootTerms()->first();
        $this->assertEquals('Classical', $rootNode->name);
        $this->assertEquals('Concertos', $rootNode->children->first()->name);
        $this->assertEquals('Sonatas', $rootNode->children->last()->name);
    }


    function test_validator_rules(){

        // Begin with known good - minimal
        $validator = Validator::make(['name'=>'GoodName'], Vocabulary::$rules);
        $this->assertTrue($validator->passes());

        // Begin with known good - maximal
        $validator = Validator::make(['name'=>'GoodName', 'Description is good too.'], Vocabulary::$rules);
        $this->assertTrue($validator->passes());

        // Missing name
        $validator = Validator::make(['name'=>''], Vocabulary::$rules);
        $this->assertTrue($validator->fails());

        // Name Too Long
        $longName = str_pad('X',81);
        $validator = Validator::make(['name'=>$longName], Vocabulary::$rules);
        $this->assertTrue($validator->fails());

        // Description Too Long
        $longDesc = str_pad('X',1001);
        $validator = Validator::make(['name'=>$longDesc], Vocabulary::$rules);
        $this->assertTrue($validator->fails());

    }

    function test_unique_names(){
        factory(\Jlab\Taxonomy\Vocabulary::class)->create(['name'=>'foobar']);

        // The database should block adding a duplicate by throwing an exception.
        // The specific exception class may vary between Oracle, MySQL, SQLite, etc.
        $this->expectException(\Exception::class);
        factory(\Jlab\Taxonomy\Vocabulary::class)->create(['name'=>'foobar']);

    }

}
