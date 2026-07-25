<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserIndexRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(private readonly ActivityLogService $activityLog)
    {
    }

    public function index(UserIndexRequest $request): View
    {
        Gate::authorize('viewAny', User::class);

        $filters = $request->validated();

        $users = User::query()
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        Gate::authorize('create', User::class);

        return view('admin.users.create', ['user' => new User(['role' => User::ROLE_AUTHOR])]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        Gate::authorize('create', User::class);

        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $createdUser = User::create($data);

        $this->recordUserActivity('create', 'membuat', $createdUser);

        return redirect()->route('admin.users.index')->with('success', 'Admin berhasil dibuat.');
    }

    public function edit(User $user): View
    {
        Gate::authorize('update', $user);

        return view('admin.users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        Gate::authorize('update', $user);

        $data = $request->validated();

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        $this->recordUserActivity('update', 'mengedit', $user);

        return redirect()->route('admin.users.index')->with('success', 'Admin berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        Gate::authorize('delete', $user);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Akun yang sedang digunakan tidak dapat dihapus.');
        }

        $user->delete();

        $this->recordUserActivity('delete', 'menghapus', $user);

        return redirect()->route('admin.users.index')->with('success', 'Admin berhasil dihapus.');
    }

    private function recordUserActivity(string $action, string $verb, User $targetUser): void
    {
        $user = request()->user();

        $this->activityLog->record(
            user: $user,
            action: $action,
            module: 'user',
            description: "{$user->name} {$verb} user {$targetUser->name}.",
        );
    }
}
