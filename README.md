# Taxonomy

This is a laravel package implementing a minimal taxonomy system.  It is patterned loosely after the 
[Drupal Taxonomy System](https://www.drupal.org/docs/7/organizing-content-with-taxonomies/about-taxonomies).
It consists of hierarchical Terms organized into one or more Vocabularies as illustrated in the example 
below taken from the Drupal documentation:

* **Vocabulary = Music**
* **Term = Classical**
  *  Sub-term = Concertos
  *  Sub-term = Sonatas
  *  Sub-term = Symphonies
* **Term = Jazz**
  *  Sub-term = Swing
  *  Sub-term = Fusion 

## Install

Via Composer

``` bash
$ composer require jlab/taxonomy
```

## Usage

### Creating Vocabulary and Terms

``` php
$vocabulary = new Vocabulary([
    'name' => 'Music', 
    'description' => "Musical Generes"
]);
$vocabulary->save();

$parentTerm = new Term(['name'=>'Classical']);
$vocabulary->terms()->save($parentTerm);
$parentTerm->addChild(new Term(['name' => 'Concertos']));
$parentTerm->addChild(new Term(['name' => 'Sonatas']));
```

### Retrieving Terms

``` php
// First and only because vocabulary names must be unique
$vocabulary = Vocabulary::where('name', 'Music')->first();

print $vocabulary->name;                   // Music

$rootNode = $vocabulary->rootTerms()->first();
print $rootNode->name;                     // Classical
print $rootNode->children->first()->name;  // Concertos
print $rootNode->children->last()->name;   // Sonatas

```

### Hierarchy

Becaue Taxonomy Terms inherit from [Laravel-NestedSet](https://github.com/lazychaser/laravel-nestedset), 
they have access to that package's methods as well.




