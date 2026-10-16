<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var Task $task */
        $task = $this->route('task');

        return $this->user()?->can('updateStatus', $task) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(['pending', 'in_progress', 'review', 'completed'])],
            'progress' => ['nullable', 'integer', 'min:0', 'max:100'],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
