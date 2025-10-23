<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller {
    protected $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    /**
     * Display a listing of users
     */
    public function index(Request $request) {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');
        $role = $request->get('role');
        $status = $request->get('status');
        $showInactive = $request->get('show_inactive', false);

        // Build filters array
        $filters = [];
        if ($role) {
            $filters['role'] = $role;
        }
        if ($status) {
            $filters['is_active'] = $status;
        }

        // Show all users including inactive if requested
        if ($showInactive) {
            $users = $this->userService->getAllWithInactive($search, $perPage);
        } else {
            // Using new unified getPaginate method from BaseService - only active users
            $users = $this->userService->getPaginate($search, $perPage);
        }

        $perPageOptions = $this->userService->getPerPageOptions();

        return view('users.index', compact('users', 'perPageOptions', 'search', 'perPage', 'role', 'status', 'showInactive'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create() {
        return view('users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:150|unique:users',
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:50|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,operator,viewer,user',
            'is_active' => 'nullable|in:0,1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $this->userService->createUser($request->all());

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user
     */
    public function show($id) {
        // Find user including inactive ones
        $user = $this->userService->findByIdWithInactive($id);

        if (!$user) {
            return redirect()->route('users.index')
                ->with('error', 'User not found.');
        }

        // Calculate days since created
        $createdDate = $user->created_at->startOfDay();
        $currentDate = now()->startOfDay();
        $daysSinceCreated = (int) $createdDate->diffInDays($currentDate);

        return view('users.show', compact('user', 'daysSinceCreated'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit($id) {
        // Find user including inactive ones
        $user = $this->userService->findByIdWithInactive($id);

        if (!$user) {
            return redirect()->route('users.index')
                ->with('error', 'User not found.');
        }

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id) {
        // Find user including inactive ones
        $user = $this->userService->findByIdWithInactive($id);

        if (!$user) {
            return redirect()->route('users.index')
                ->with('error', 'User not found.');
        }

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:150|unique:users,username,' . $id,
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:50|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,operator,viewer,user',
            'is_active' => 'nullable|in:0,1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $this->userService->updateUser($user, $request->all());

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user (soft delete - set is_active to 0)
     */
    public function destroy($id) {
        // Find user including inactive ones
        $user = $this->userService->findByIdWithInactive($id);

        if (!$user) {
            return redirect()->route('users.index')
                ->with('error', 'User not found.');
        }

        if ((int)$id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $this->userService->deleteUser($user);

        return redirect()->route('users.index')
            ->with('success', 'User deactivated successfully.');
    }
}
