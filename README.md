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


## Compatibility

| Laravel Version | Taxonomy Version |
|-----------------|------------------|
| 8.43+           | 3.1              |
| 7.x             | 3.x              |
| 5.x             | 2.x              |


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

## Authorization
In order to enable editing actions in the included views, it's necessary to register 
a Taxonomy Policy such as the default `TaxonomyPolicy` class included with the package 
which allows any authenticated user to create or edit taxonomy vocabularies and their terms.

The default policy can be registered by adding the following to the Laravel Application's `AuthServiceProvider`

```php
    protected $policies = [
        \Jlab\Taxonomy\Vocabulary::class => \Jlab\Taxonomy\Policies\TaxonomyPolicy::class,
    ];
```

Naturally, if the application requirements demand a different authorization scheme, an alternate `TaxonomyPolicy` class 
may be mapped instead.

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




