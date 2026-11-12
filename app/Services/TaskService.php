<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\TaskLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TaskService
{
    /**
     * Create a new task and log the creation event.
     *
     * @param  array{
     *     title: string,
     *     description?: ?string,
     *     deadline: string,
     *     priority?: string|TaskPriority,
     *     assigned_to: int,
     *     status?: string|TaskStatus,
     *     progress?: int,
     * }  $data
     */
    public function createTask(array $data, User $creator): Task
    {
        return DB::transaction(function () use ($data, $creator): Task {
            $status = isset($data['status'])
                ? ($data['status'] instanceof TaskStatus ? $data['status'] : TaskStatus::from((string) $data['status']))
                : TaskStatus::Pending;

            $priority = isset($data['priority'])
                ? ($data['priority'] instanceof TaskPriority ? $data['priority'] : TaskPriority::from((string) $data['priority']))
                : TaskPriority::Medium;

            $task = Task::create([
                'project_id' => isset($data['project_id']) && $data['project_id'] ? (int) $data['project_id'] : null,
                'milestone_id' => isset($data['milestone_id']) && $data['milestone_id'] ? (int) $data['milestone_id'] : null,
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'deadline' => $data['deadline'],
                'priority' => $priority,
                'status' => $status,
                'progress' => $data['progress'] ?? 0,
                'assigned_by' => $creator->id,
                'assigned_to' => (int) $data['assigned_to'],
                'started_at' => $status === TaskStatus::InProgress ? now() : null,
                'completed_at' => $status === TaskStatus::Completed ? now() : null,
            ]);

            if ($task->project_id) {
                $task->project?->recalculateProgress();
            }

            TaskLog::create([
                'task_id' => $task->id,
                'user_id' => $creator->id,
                'from_status' => null,
                'to_status' => $task->status->value,
                'from_progress' => null,
                'to_progress' => $task->progress,
                'note' => 'Tugas berhasil dibuat dan ditugaskan.',
                'created_at' => now(),
            ]);

            return $task;
        });
    }

    /**
     * Update full task details.
     *
     * @param  array{
     *     title: string,
     *     description?: ?string,
     *     deadline: string,
     *     priority: string|TaskPriority,
     *     assigned_to: int,
     * }  $data
     */
    public function updateTask(Task $task, array $data, User $updater): Task
    {
        return DB::transaction(function () use ($task, $data, $updater): Task {
            $oldAssignee = $task->assigned_to;
            $priority = $data['priority'] instanceof TaskPriority
                ? $data['priority']
                : TaskPriority::from((string) $data['priority']);

            $task->update([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'deadline' => $data['deadline'],
                'priority' => $priority,
                'assigned_to' => (int) $data['assigned_to'],
            ]);

            if ($oldAssignee !== (int) $data['assigned_to']) {
                $newAssignee = User::find($data['assigned_to']);
                TaskLog::create([
                    'task_id' => $task->id,
                    'user_id' => $updater->id,
                    'from_status' => $task->status->value,
                    'to_status' => $task->status->value,
                    'from_progress' => $task->progress,
                    'to_progress' => $task->progress,
                    'note' => "Tugas dialihkan ke {$newAssignee?->name}.",
                    'created_at' => now(),
                ]);
            }

            return $task;
        });
    }

    /**
     * Update task status, progress and record audit log.
     */
    public function updateStatus(
        Task $task,
        TaskStatus $newStatus,
        ?int $progress,
        ?string $note,
        User $user
    ): Task {
        return DB::transaction(function () use ($task, $newStatus, $progress, $note, $user): Task {
            $fromStatus = $task->status;
            $fromProgress = $task->progress;

            $toProgress = $progress ?? match ($newStatus) {
                TaskStatus::Pending => 0,
                TaskStatus::InProgress => max(10, $task->progress),
                TaskStatus::Review => max(80, $task->progress),
                TaskStatus::Completed => 100,
            };

            // Clamp progress between 0 and 100
            $toProgress = max(0, min(100, $toProgress));

            $startedAt = $task->started_at;
            if ($newStatus === TaskStatus::InProgress && $startedAt === null) {
                $startedAt = now();
            }

            $completedAt = $task->completed_at;
            if ($newStatus === TaskStatus::Completed) {
                $completedAt = now();
            } elseif ($newStatus !== TaskStatus::Completed && $task->status === TaskStatus::Completed) {
                $completedAt = null;
            }

            $task->update([
                'status' => $newStatus,
                'progress' => $toProgress,
                'started_at' => $startedAt,
                'completed_at' => $completedAt,
            ]);

            $logNote = $note ?: match ($newStatus) {
                TaskStatus::Pending => 'Tugas di-reset ke status Belum Dikerjakan.',
                TaskStatus::InProgress => 'Karyawan mulai mengerjakan tugas ini.',
                TaskStatus::Review => 'Tugas diajukan untuk ditinjau / direview oleh atasan.',
                TaskStatus::Completed => 'Tugas berhasil diselesaikan.',
            };

            TaskLog::create([
                'task_id' => $task->id,
                'user_id' => $user->id,
                'from_status' => $fromStatus->value,
                'to_status' => $newStatus->value,
                'from_progress' => $fromProgress,
                'to_progress' => $toProgress,
                'note' => $logNote,
                'created_at' => now(),
            ]);

            return $task;
        });
    }

    /**
     * Delete a task.
     */
    public function deleteTask(Task $task): bool
    {
        return (bool) $task->delete();
    }
}
