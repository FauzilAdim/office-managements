<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Task;
use App\Models\User;
use App\Services\HierarchyService;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', Task::class) ?? false;
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
            'project_id' => ['nullable', 'exists:projects,id'],
            'milestone_id' => ['nullable', 'exists:milestones,id'],
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
            'status' => ['nullable', Rule::in(['pending', 'in_progress', 'review', 'completed'])],
            'progress' => ['nullable', 'integer', 'min:0', 'max:100'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul tugas wajib diisi.',
            'deadline.required' => 'Batas waktu (deadline) wajib ditentukan.',
            'priority.required' => 'Tingkat prioritas wajib dipilih.',
            'assigned_to.required' => 'Penerima tugas wajib dipilih.',
        ];
    }
}
