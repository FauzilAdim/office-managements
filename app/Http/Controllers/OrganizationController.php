<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\User;
use App\Services\HierarchyService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrganizationController extends Controller
{
    public function __construct(
        private readonly HierarchyService $hierarchyService
    ) {}

    /**
     * Display organizational structure hierarchy tree.
     */
    public function index(Request $request): View
    {
        $tree = $this->hierarchyService->buildOrganizationTree();

        // Also get flattened list grouped by division and role for tabs/view options
        $divisions = Division::with(['users' => function ($query) {
            $query->where('is_active', true)->with(['roles', 'supervisor']);
        }])->get();

        $directors = User::role('Direktur')->with(['roles'])->get();

        return view('organization.index', [
            'tree' => $tree,
            'divisions' => $divisions,
            'directors' => $directors,
        ]);
    }
}
