<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
    * Determine if the user is authorized to make this request.
    */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 'sometimes' → solo valida si el campo está presente
            // Esto permite actualizaciones parciales (PATCH)
            'title' => [
                'sometimes',
                'required',
                'string',
                'min:3',
                'max:200',
                'regex:/^[^<>{}]*$/',
            ],
            'description' => ['sometimes','nullable','string','max:2000'],
            'status'      => ['sometimes','required','string','in:pending,in_progress,completed'],
            'priority'    => ['sometimes','required','string','in:low,medium,high'],
            'due_date'    => ['sometimes','nullable','date','date_format:Y-m-d','after_or_equal:today'],
            'assigned_email' => ['sometimes','nullable','email:rfc,dns','max:150'],
        ];
    }

    public function messages(): array
    {
        // Reutilizamos los mismos mensajes del StoreTaskRequest
        return (new StoreTaskRequest())->messages();
    }

    protected function prepareForValidation(): void
    {
        $fields = [];
        if ($this->has('title'))
            $fields['title'] = trim($this->title);
        if ($this->has('description'))
            $fields['description'] = trim(strip_tags($this->description ?? ''));
        if ($this->has('assigned_email'))
            $fields['assigned_email'] = strtolower(trim($this->assigned_email ?? ''));
        if (!empty($fields)) $this->merge($fields);
    }
}
