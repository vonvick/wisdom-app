<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'first_name' => 'bail|min:2',
            'last_name' => 'bail|min:2',
            'username' => 'bail|nullable|unique:users|min:2',
            'email' => '|unique:users|email',
            'phone' => 'nullable',
            'occupation' => 'nullable',
            'headline' => 'nullable|min:20',
            'full_description' => 'nullable|max:2000',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(['errors' => $errors
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
