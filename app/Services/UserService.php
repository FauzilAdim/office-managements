<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserService
{
    /**
     * Update user profile data.
     *
     * @param  array{
     *     name: string,
     *     email?: string,
     *     phone?: ?string,
     *     telegram_username?: ?string,
     *     address?: ?string,
     * }  $data
     */
    public function updateProfile(User $user, array $data, ?UploadedFile $avatarFile = null): User
    {
        $payload = [
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
            'telegram_username' => isset($data['telegram_username']) ? ltrim($data['telegram_username'], '@') : null,
            'address' => $data['address'] ?? null,
        ];

        if (isset($data['email']) && $data['email'] !== $user->email) {
            $payload['email'] = $data['email'];
        }

        if ($avatarFile !== null) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $avatarFile->store('avatars', 'public');
            $payload['avatar'] = $path;
        }

        $user->update($payload);

        return $user;
    }

    /**
     * Get comprehensive employee details including task history.
     *
     * @return array<string, mixed>
     */
    public function getEmployeeDetails(User $employee): array
    {
        $employee->load([
            'division',
            'supervisor',
            'roles',
            'subordinates' => fn ($q) => $q->with('roles')->where('is_active', true),
        ]);

        $taskHistory = $employee->assignedTasks()
            ->with(['creator', 'logs.user'])
            ->latest('updated_at')
            ->get();

        $activeTask = $employee->currentActiveTask();

        $tasksCount = [
            'total' => $taskHistory->count(),
            'completed' => $taskHistory->where('status.value', 'completed')->count(),
            'in_progress' => $taskHistory->where('status.value', 'in_progress')->count(),
            'review' => $taskHistory->where('status.value', 'review')->count(),
            'pending' => $taskHistory->where('status.value', 'pending')->count(),
        ];

        return [
            'employee' => $employee,
            'task_history' => $taskHistory,
            'active_task' => $activeTask,
            'stats' => $tasksCount,
        ];
    }
}
