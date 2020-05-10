<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
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
        return [
            'first_name' => 'bail|required|min:2',
            'last_name' => 'bail|required|min:2',
            'username' => 'bail|nullable|unique:users|min:2',
            'email' => 'bail|required|unique:users|email',
            'password' => 'nullable',
            'phone' => 'nullable',
            'occupation' => 'nullable',
            'headline' => 'nullable|min:20',
            'full_description' => 'nullable|max:2000',
            'image_url' => 'nullable',
            'thumbnail_url' => 'nullable'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(['errors' => $errors
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
