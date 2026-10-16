<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\Division;
use App\Models\User;
use App\Services\HierarchyService;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
        private readonly HierarchyService $hierarchyService
    ) {}

    /**
     * Display a listing of visible employees.
     */
    public function index(Request $request): View
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        $employees = $this->hierarchyService->getVisibleUsers($currentUser);

        // Filter by division if requested
        if ($request->filled('division_id')) {
            $employees = $employees->where('division_id', (int) $request->division_id);
        }

        // Filter by search query
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $employees = $employees->filter(function (User $emp) use ($search) {
                return str_contains(strtolower($emp->name), $search)
                    || str_contains(strtolower($emp->email), $search)
                    || str_contains(strtolower($emp->position ?? ''), $search);
            });
        }

        $divisions = Division::all();

        return view('employees.index', [
            'employees' => $employees,
            'divisions' => $divisions,
        ]);
    }

    /**
     * Display detailed employee profile & task history.
     */
    public function show(User $employee): View
    {
        $this->authorize('view', $employee);

        $data = $this->userService->getEmployeeDetails($employee);

        return view('employees.show', $data);
    }

    /**
     * Show form for editing employee profile.
     */
    public function edit(User $employee): View
    {
        $this->authorize('update', $employee);

        return view('employees.edit', [
            'employee' => $employee,
        ]);
    }

    /**
     * Update employee profile.
     */
    public function update(UpdateProfileRequest $request, User $employee): RedirectResponse
    {
        $this->authorize('update', $employee);

        $this->userService->updateProfile(
            $employee,
            $request->validated(),
            $request->file('avatar')
        );

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Profil karyawan berhasil diperbarui.');
    }
}
