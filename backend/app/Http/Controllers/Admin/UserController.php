<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\Role;
use App\Enums\UserStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by name or email
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'in:' . implode(',', Role::values())],
            'status' => ['required', 'in:' . implode(',', UserStatus::values())],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', '사용자가 성공적으로 생성되었습니다.');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'in:' . implode(',', Role::values())],
            'status' => ['required', 'in:' . implode(',', UserStatus::values())],
            'password' => ['nullable', Rules\Password::defaults()],
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', '사용자가 성공적으로 수정되었습니다.');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', '자신의 계정은 삭제할 수 없습니다.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', '사용자가 성공적으로 삭제되었습니다.');
    }

    /**
     * Approve a pending user (accommodation manager)
     */
    public function approve(User $user)
    {
        if (!$user->isPending()) {
            return back()->with('error', '승인 대기 중인 사용자만 승인할 수 있습니다.');
        }

        $user->approve(auth()->user());

        return back()->with('success', '사용자가 승인되었습니다.');
    }

    /**
     * Reject a pending user
     */
    public function reject(User $user)
    {
        if (!$user->isPending()) {
            return back()->with('error', '승인 대기 중인 사용자만 거부할 수 있습니다.');
        }

        $user->reject();

        return back()->with('success', '사용자가 거부되었습니다.');
    }

    /**
     * Suspend a user
     */
    public function suspend(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', '자신의 계정은 정지할 수 없습니다.');
        }

        $user->suspend();

        return back()->with('success', '사용자가 정지되었습니다.');
    }
}
