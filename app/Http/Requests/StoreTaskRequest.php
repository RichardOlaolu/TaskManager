<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
//use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Enums\Role;
use App\Enums\Priority;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Any authenticated user can create a task
         return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|string|max:255',
            'due_date' => 'nullable|date_format:Y-m-d',
        ];
    }

    protected function prepareForValidation()
    {
        // Set high priority for admin-created tasks
        $user = $this->user();
if ($user && $user->role === Role::Admin && !$this->has('priority')) {
    $this->merge(['priority' => Priority::HIGH]);
}
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Task title is required',
            'priority.required' => 'Priority is required',
            'due_date.date_format' => 'Due date must be in YYYY-MM-DD format',
        ];
    }
}
