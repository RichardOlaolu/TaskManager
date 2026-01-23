<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ViewUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $userToView = $this->route('user') ?? $this->route('id');

        // If the route parameter is an ID, fetch the user
        if (is_numeric($userToView)) {
            $userToView = \App\Models\User::findOrFail($userToView);
        }

        return $this->user()->can('view', $userToView);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Handle a failed authorization attempt.
     */
    protected function failedAuthorization()
    {
        throw new \Illuminate\Auth\Access\AuthorizationException(
            'You can only view your own profile'
        );
    }
}
