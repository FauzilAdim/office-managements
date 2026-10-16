<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use App\Services\HierarchyService;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $task = $this->route('task');

        return $this->user()?->can('update', $task) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'deadline' => ['required', 'date'],
            'priority' => ['required', Rule::in(['low', 'medium', 'high', 'urgent'])],
            'assigned_to' => [
                'required',
                'exists:users,id',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $targetUser = User::find($value);
                    if (! $targetUser) {
                        $fail('Karyawan yang dipilih tidak ditemukan.');

                        return;
                    }

                    $hierarchyService = app(HierarchyService::class);
                    if (! $hierarchyService->canAssignTo($this->user(), $targetUser)) {
                        $fail('Anda tidak memiliki wewenang untuk menugaskan pekerjaan kepada karyawan ini.');
                    }
                },
            ],
        ];
    }
}
