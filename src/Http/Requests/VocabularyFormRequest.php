<?php


namespace Jlab\Taxonomy\Http\Requests;

use Jlab\Taxonomy\Vocabulary;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VocabularyFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
        if ($this->method() == 'PUT') {
            $rules = $validator->getRules();
            $rules['name'] = [
                'required',
                'max:80',
                Rule::unique('vocabularies', 'name')->ignore($this->input('id')),
            ];
            $validator->setRules($rules);
        }
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return Vocabulary::$rules;
    }
}
