<?php


namespace App\Http\Requests;

use Atlis\Taxonomy\Term;
use Atlis\Taxonomy\Vocabulary;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rule;
use Kalnoy\Nestedset\Collection;

class TermFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /**
         * @TODO implement authorization
         * see https://laracasts.com/discuss/channels/laravel/authorization-in-form-request-objects?page=1
         */

        return true;
    }


    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {

        //Modify the uniqueness rule on update to exclude the current
        //model's database row from the duplicates check
        $rules = $validator->getRules();
        $rules['name'] = [
            'required',
            'max:80',
        ];

        // Need Validation rule to enforce that name
        // is unique for a given vocabulary_id is unique
        if ($this->method() == 'POST') {
            $rules['name'][] = Rule::unique('terms', 'name')
                ->where('vocabulary_id',$this->input('vocabulary_id'));
        }
        if ($this->method() == 'PUT') {

            $rules['name'][] = Rule::unique('terms', 'name')
                ->where('vocabulary_id',$this->input('vocabulary_id'))
                ->ignore($this->input('id'));
        }

        $validator->setRules($rules);

    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return Term::$rules;
    }
}
