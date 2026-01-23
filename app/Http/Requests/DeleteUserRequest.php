<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $userToDelete = $this->route('user') ?? $this->route('id');

        // If the route parameter is an ID, fetch the user
        if (is_numeric($userToDelete)) {
            $userToDelete = \App\Models\User::findOrFail($userToDelete);
        }

        return $this->user()->can('delete', $userToDelete);
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
            'Only administrators can delete users'
        );
    }
}
