<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Cualquier usuario autenticado puede crear tareas.
     * En guías posteriores se implementará autorización por roles.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para crear una tarea.
     */
    public function rules(): array
    {
        return [
            // Título: obligatorio, solo texto, entre 3 y 200 caracteres
            // regex: no permite etiquetas HTML ni caracteres de script
            'title' => [
                'required',
                'string',
                'min:3',
                'max:200',
                'regex:/^[^<>{}]*$/',
            ],

            // Descripción: opcional, texto, máximo 2000 chars
            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],

            // Estado: debe ser exactamente uno de los valores permitidos
            'status' => [
                'required',
                'string',
                'in:pending,in_progress,completed',
            ],

            // Prioridad: valores controlados por enum
            'priority' => [
                'required',
                'string',
                'in:low,medium,high',
            ],

            // Fecha límite: opcional, formato de fecha, no puede ser pasada
            'due_date' => [
                'nullable',
                'date',
                'date_format:Y-m-d',
                'after_or_equal:today',
            ],

            // Email: opcional, formato válido, máximo 150 chars
            'assigned_email' => [
                'nullable',
                'email:rfc,dns',
                'max:150',
            ],
        ];
    }

    /**
    * Mensajes de error personalizados en español.
    */
    public function messages(): array
    {
        return [
            'title.required'     => 'El título de la tarea es obligatorio.',
            'title.min'          => 'El título debe tener al menos :min caracteres.',
            'title.max'          => 'El título no puede exceder :max caracteres.',
            'title.regex'        => 'El título contiene caracteres no permitidos (< > { }).',

            'description.max'   => 'La descripción no puede exceder :max caracteres.',

            'status.required'   => 'El estado de la tarea es obligatorio.',
            'status.in'         => 'El estado debe ser: pendiente, en progreso o completada.',

            'priority.required' => 'La prioridad de la tarea es obligatoria.',
            'priority.in'       => 'La prioridad debe ser: baja, media o alta.',

            'due_date.date'           => 'La fecha límite debe ser una fecha válida.',
            'due_date.date_format'    => 'La fecha debe tener el formato AAAA-MM-DD.',
            'due_date.after_or_equal' => 'La fecha límite no puede ser una fecha pasada.',

            'assigned_email.email'   => 'El correo asignado no tiene un formato válido.',
            'assigned_email.max'     => 'El correo no puede exceder :max caracteres.',
        ];
    }

    /**
    * Sanitizar los datos ANTES de que las reglas se apliquen.
    * prepareForValidation() es el hook correcto para esto en Laravel.
    */
    protected function prepareForValidation(): void
    {
        $this->merge([
            // trim() elimina espacios al inicio y final
            'title'          => trim($this->title ?? ''),

            // strip_tags() elimina etiquetas HTML de la descripción
            // trim() limpia espacios sobrantes
            'description'    => trim(strip_tags($this->description ?? '')),

            // strtolower() normaliza el email a minúsculas
            'assigned_email' => strtolower(trim($this->assigned_email ?? '')),
        ]);
    }

}
