<?php


namespace App\Http\Requests;

use Atlis\Taxonomy\Term;
use Atlis\Taxonomy\Vocabulary;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rule;
use Kalnoy\Nestedset\Collection;

class OwnershipFormRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'pk' => ['required',['regex'=>'/^[\w]+\:(.*)$/']],
            'name' => 'required|in:users,groups'
        ];
    }
}
