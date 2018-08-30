<?php

namespace App\Http\Requests\Frontend\User;

use Illuminate\Validation\Rule;
use App\Helpers\Frontend\Auth\Socialite;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateProfileRequest.
 */
class UpdateProfileRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        clean($_POST['first_name']);
        clean($_POST['last_name']);
        if(isset($_POST['email'])){
            clean($_POST['email']);
        }
        clean($_POST['blog']);
        if(isset($_POST['avatar_location'])){
            clean($_POST['avatar_location']);
        }
        return [
            'first_name'  => 'required|max:191',
            'last_name'  => 'max:191',
            'email' => 'sometimes|required|email|max:191',
            'blog' => 'max:191',
            'avatar_type' => ['required', 'max:191', Rule::in(array_merge(['gravatar', 'storage'], (new Socialite)->getAcceptedProviders()))],
            'avatar_location' => 'sometimes|image|max:100|dimensions:max_width=200,max_height=200',
        ];
    }
}
