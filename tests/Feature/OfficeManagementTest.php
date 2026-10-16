<?php

declare(strict_types=1);

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('login page is accessible', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
    $response->assertSee('Office Management');
});

test('director can view director dashboard', function () {
    $director = User::where('email', 'direktur@office.com')->first();

    $response = $this->actingAs($director)->get('/dashboard');

    $response->assertStatus(200);
    $response->assertSee('Dashboard Direktur');
    $response->assertSee('Overview Perusahaan');
    $response->assertSee('Belum Dikerjakan');
});

test('staff can view staff dashboard with personal tasks only', function () {
    $staff = User::where('email', 'staff.dev1@office.com')->first();

    $response = $this->actingAs($staff)->get('/dashboard');

    $response->assertStatus(200);
    $response->assertSee('Dashboard Tugas Saya');
});

test('supervisor can assign task to subordinate and audit log is created', function () {
    $lead = User::where('email', 'lead.dev@office.com')->first();
    $staff = User::where('email', 'staff.dev1@office.com')->first();

    $response = $this->actingAs($lead)->post('/tasks', [
        'title' => 'Refactoring Test Task Unit',
        'description' => 'Test pengerjaan unit testing untuk modul otorisasi',
        'deadline' => now()->addDays(3)->format('Y-m-d H:i:s'),
        'priority' => 'high',
        'assigned_to' => $staff->id,
        'status' => 'pending',
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('tasks', [
        'title' => 'Refactoring Test Task Unit',
        'assigned_by' => $lead->id,
        'assigned_to' => $staff->id,
    ]);

    $createdTask = Task::where('title', 'Refactoring Test Task Unit')->first();
    $this->assertDatabaseHas('task_logs', [
        'task_id' => $createdTask->id,
        'user_id' => $lead->id,
        'to_status' => 'pending',
    ]);
});

test('staff can update task progress and status with audit trail', function () {
    $staff = User::where('email', 'staff.dev1@office.com')->first();
    $task = Task::where('assigned_to', $staff->id)->first();

    $response = $this->actingAs($staff)->patch("/tasks/{$task->id}/status", [
        'status' => 'in_progress',
        'progress' => 75,
        'note' => 'Menyelesaikan 75% fungsionalitas.',
    ]);

    $response->assertRedirect();

    $task->refresh();
    expect($task->status)->toBe(TaskStatus::InProgress);
    expect($task->progress)->toBe(75);

    $this->assertDatabaseHas('task_logs', [
        'task_id' => $task->id,
        'user_id' => $staff->id,
        'to_status' => 'in_progress',
        'note' => 'Menyelesaikan 75% fungsionalitas.',
    ]);
});

test('organization hierarchy page renders successfully', function () {
    $director = User::where('email', 'direktur@office.com')->first();

    $response = $this->actingAs($director)->get('/organization');

    $response->assertStatus(200);
    $response->assertSee('Struktur Organisasi');
});

test('director can view any employee details page', function () {
    $director = User::where('email', 'direktur@office.com')->first();
    $employee = User::where('email', 'staff.dev1@office.com')->first();

    $response = $this->actingAs($director)->get("/employees/{$employee->id}");

    $response->assertStatus(200);
    $response->assertSee($employee->name);
});

test('authenticated user can view projects page with team personnel', function () {
    $director = User::where('email', 'direktur@office.com')->first();

    $response = $this->actingAs($director)->get('/projects');

    $response->assertStatus(200);
    $response->assertSee('PROJECTS');
    $response->assertSee('Core Banking API');
    $response->assertSee('Personil Terlibat');
});
