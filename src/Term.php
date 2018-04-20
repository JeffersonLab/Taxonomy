<?php
/**
 * Taxonomy Terms that will be used to "tag" ATLis tasks.
 *
 */

namespace Jlab\Taxonomy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Kalnoy\Nestedset\NodeTrait;         // https://github.com/lazychaser/laravel-nestedset



class Term extends Model
{
    use NodeTrait;


    public $fillable = ['name', 'description', 'url', 'vocabulary_id',
        'parent_id', 'legacy_id', 'weight'];

    public static $rules = [
        'name' => 'required|max:80',
        'description' => 'nullable|max:1000',
        'url' => 'nullable|url|max:1000',
        'vocabulary_id' => 'required|integer',
        'weight' => 'integer',
    ];



    /**
     * Answer whether the description attribute is a valid url.
     *
     * @return bool
     */
    function hasUrl()
    {
        return ($this->url
            && (filter_var($this->url, FILTER_VALIDATE_URL) !== false)
        );
    }

    /**
     * Converts a string of formatted text into an array of term attribute arrays
     *
     * @example
     *   term | description
     *   term | description
     *  becomes
     *   [
     *      ['name'=>term,'description'=>description],
     *      ['name'=>term,'description'=>description],
     *   ]
     * @param string $text
     * @return array
     */
    protected static function parseText($text)
    {
        $terms = [];
        // Need to consider possibility of line endings having Windows
        // style CR as well as unix newlines.
        $lines = preg_split("/(\r\n|\n|\r)/", $text);

        foreach ($lines as $line) {
            $parts = explode('|', $line, 2);
            $terms[] = [
                'name' => trim($parts[0]),
                'description' => isset($parts[1]) ? trim($parts[1]) : '',
            ];
        }
        return $terms;
    }


    /**
     * Creates terms from a string of text as children of the provided
     * Term object and returns them.
     *
     *   term | description
     *   term | description
     *
     * @param string $text - the string containing term info
     * @param Term   $parent
     * @return Collection
     * @throws \Throwable
     */
    public static function makeChildrenFromText($text, Term $parent)
    {

        $weight = 1;
        $terms = new Collection();

        foreach (self::parseText($text) as $attributes) {
            $term = new Term($attributes);
            $term->vocabulary_id = $parent->vocabulary_id;
            $term->parent_id = $parent->id;
            $term->weight = $weight;
            $term->saveOrFail();
            $terms->push($term);
        }

        return $terms;

    }

    public function addChild(Term $term){
        $this->vocabulary->terms()->save($term);   // to make sure vocabulary id matches
        $this->appendNode($term);
    }


    /**
     * Creates a collection of terms from a string of text containing one term per line
     *
     * @example
     *   term | description
     *   term | description
     *
     * @param string  $text - the string containing term info
     * @param integer $vocabulary_id
     * @return Collection
     * @throws
     */
    public static function makeFromText($text, $vocabulary_id)
    {

        $weight = 1;
        $terms = new Collection();

        foreach (self::parseText($text) as $attributes) {
            $term = new Term($attributes);
            $term->vocabulary_id = $vocabulary_id;
            $term->weight = $weight;
            $term->saveOrFail();
            $terms->push($term);
        }
        return $terms;
    }

    public function vocabulary()
    {
        return $this->belongsTo(Vocabulary::class, 'vocabulary_id');
    }

    public function getLftName()
    {
        return 'left';
    }

    public function getRgtName()
    {
        return 'right';
    }

    public function getParentIdName()
    {
        return 'parent_id';
    }

    /**
     * Set the value of model's parent id key.
     *
     * @throws \Exception If parent node doesn't exists
     */
    public function setParentAttribute($value)
    {
        $this->setParentIdAttribute($value);
    }
}


