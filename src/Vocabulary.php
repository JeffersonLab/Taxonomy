<?php

namespace Jlab\Taxonomy;

use Illuminate\Database\Eloquent\Model;

class Vocabulary extends Model
{

    public $timestamps = false;

    public $fillable = ['name', 'description'];


    /**
     * Validation rules
     *
     * @see http://laravel.com/docs/5.5/validation
     * @var array
     */
    public static $rules = array(
        'name' => 'required|max:80|unique:vocabularies,name,id',
        'description' => 'max:1000',
    );


    /**
     * Return the Terms relationship ordered by Term weight.
     *
     * @return mixed
     */
    public function terms()
    {
        return $this->hasMany(Term::class, 'vocabulary_id')
            ->orderBy('weight')->orderBy('name');
    }


    public function hasTermId($id){
        return $this->terms()->where('id', $id)->exists();
    }

    /**
     * Returns a collection of the vocabulary's root terms.
     *
     * Root terms are top-level terms with no parent.
     *
     * @return \Illuminate\Support\Collection
     */
    public function rootTerms()
    {
        if ($this->terms) {
            return $this->terms->where('parent_id', '')
                ->sortBy(['weight','name']);
        }
        return new \Illuminate\Support\Collection();
    }


    /**
     * Returns the root terms formatted as a string
     *   name | description
     *   name | description
     */
    public function rootTermsFormValue(){
        $string = '';
        foreach ($this->rootTerms() as $term){
            $string .= $term->name .' | '.$term->description."\n";
        }
        return $string;
    }


}
