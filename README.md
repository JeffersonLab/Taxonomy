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


## Prerequisites

In order to use the javascript resources included with this package, the laravel package that is including
it must load the following javascript packages:
* jquery
* bootstrap 3
* bootbox
* lodash


## Install

Via Composer

``` bash
$ composer require jlab/taxonomy
```

## Configure

After installation, it is necessary to publish the package's public js and css
resources into your pacakage's public/vendor area.  Assuming that you wish to use
them.  If using only the back-end features (e.g Model) of the package, the steps here
may be skipped.

``` bash
php artisan  vendor:publish --provider='Jlab\Taxonomy\TaxonomyServiceProvider'
```

Add the following to your config/app.php file
```
	...
	
	'providers' => array(
		...
		Lord\Laroute\LarouteServiceProvider::class,
	],
	
	...
```

And then execute

```
php artisan laroute:generate
```


And then execute laroute so that necessary routes will be available in

## Database

The package contains database migrations that will create two tables named
terms and vocabularies.  To instantiate the tables, 
you must run the ```php artisan migraate``` command.
```bash
$ php artisan migrate
Migrating: 2018_04_12_090732_create_vocabularies_table
Migrated:  2018_04_12_090732_create_vocabularies_table
Migrating: 2018_04_12_090740_create_terms_table
Migrated:  2018_04_12_090740_create_terms_table
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




