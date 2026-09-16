<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

use App\Models\User;
use Spatie\Permission\Models\Role;


class UserController extends Controller
{


    public function index(Request $request)
    {
        $allowedSorts = ['id', 'first_name', 'last_name', 'email', 'status', 'created_at', 'updated_at', 'role'];
        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction') === 'desc' ? 'desc' : 'asc';
       
        $limit = $request->input('limit', 10);

        if ($limit === 'all') {
            $limit = 1000000; // A very large number to effectively disable pagination
        } else {
            $limit = (int) $limit;
        }

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'id';
        }

        $query = User::with('roles')
            ->when(
                ! auth()->user()->hasRole('Admin'),
                fn($q) => $q->where('created_by', auth()->id())
            );

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', fn($q) => $q->where('id', $request->input('role')));
        }

        if ($sort === 'role') {
            $query = $query->select('users.*')
                ->addSelect([
                    'role_name' => DB::table('roles')
                        ->select('roles.name')
                        ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
                        ->whereColumn('model_has_roles.model_id', 'users.id')
                        ->where('model_has_roles.model_type', User::class)
                        ->orderBy('roles.name')
                        ->limit(1),
                ])
                ->orderBy('role_name', $direction);
        } else {
            $query = $query->orderBy($sort, $direction);
        }

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', "%{$request->input('email')}%"
            );
        }

        $users = $query->paginate($limit)->withQueryString();
        $roles = Role::pluck('name', 'id');

        return view('backend.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all()->pluck('name', 'id');
          $users = User::pluck('name', 'id'); // Fetch all users for the "Created By" dropdown
     
        return view('backend.users.create-edit', compact('roles','users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|string|min:6|confirmed',
            'mobile' => 'nullable|string|max:50',
            'gender' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:1000',
            'bio' => 'nullable|string|max:2000',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'social_profiles' => 'nullable|json',
            'email_verified_at' => 'nullable|date',
            'last_ip' => 'nullable|ip',
            'login_count' => 'nullable|integer|min:0',
            'last_login' => 'nullable|date',
            'status' => 'nullable|in:0,1,2',
            'created_by' => 'nullable|integer',
            'updated_by' => 'nullable|integer',
            'deleted_by' => 'nullable|integer',
            'avatar' => 'nullable|image|max:5120',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        if (! $request->filled('password')) {
            unset($validated['password']);
        }

        if ($request->filled('social_profiles')) {
            $validated['social_profiles'] = json_decode($request->input('social_profiles'), true);
        }

      // $roles = $validated['roles'] ?? [];
$roles = Role::whereIn('id', $validated['roles'] ?? [])->pluck('name')->toArray();
        unset($validated['roles']);

        $user = User::create($validated);

        if (!empty($roles)) {
            $user->syncRoles($roles);
        }

        if ($request->hasFile('avatar')) {
            $user->clearMediaCollection('avatars');
            $user->addMedia($request->file('avatar'))->toMediaCollection('avatars');
        }

        return redirect()->route('users.index')->with(['status' => 'success', 'message' => 'User created successfully.']);
    }

    public function show($id)
    {
        // Show user details
    }

    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all()->pluck('name', 'id');
        $users = User::pluck('name', 'id'); // Fetch all users for the "Created By" dropdown
        return view('backend.users.create-edit', compact('user','users', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'username' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'mobile' => 'nullable|string|max:50',
            'gender' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:1000',
            'bio' => 'nullable|string|max:2000',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'social_profiles' => 'nullable|json',
            'email_verified_at' => 'nullable|date',
            'last_ip' => 'nullable|ip',
            'login_count' => 'nullable|integer|min:0',
            'last_login' => 'nullable|date',
            'status' => 'nullable|in:0,1,2',
            'created_by' => 'nullable|integer',
            'updated_by' => 'nullable|integer',
            'deleted_by' => 'nullable|integer',
            'avatar' => 'nullable|image|max:5120',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        if (! $request->filled('password')) {
            unset($validated['password']);
        }

        if ($request->filled('social_profiles')) {
            $validated['social_profiles'] = json_decode($request->input('social_profiles'), true);
        }

        $roles = Role::whereIn('id', $validated['roles'] ?? [])->pluck('name')->toArray();
        unset($validated['roles']);

        $user->update($validated);

        if (!empty($roles)) {
            $user->syncRoles($roles);
        } else {
            $user->syncRoles([]);
        }

        if ($request->hasFile('avatar')) {
            $user->clearMediaCollection('avatars');
            $user->addMedia($request->file('avatar'))->toMediaCollection('avatars');
        }

        return redirect()->route('users.index')->with(['status' => 'success', 'message' => 'User updated successfully.']);
    }

    public function destroy($id)
    {
        // Delete the user
    }

    public function settings()
    {
        $user = auth()->user();

        return view('backend.settings.profile', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'logo_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'sidebar_title' => ['nullable', 'string', 'max:255'],
            'sidebar_background_color' => ['nullable', 'string', 'max:7'],
            'mainbar_background_color' => ['nullable', 'string', 'max:7'],
            'link_color' => ['nullable', 'string', 'max:7'],
            'button_text_color' => ['nullable', 'string', 'max:7'],
            'button_background_color' => ['nullable', 'string', 'max:7'],
            'body_text_color' => ['nullable', 'string', 'max:7'],
        ]);

        if (! Schema::hasColumn('users', 'backend_settings')) {
            Schema::table('users', function (Blueprint $table) {
                $table->json('backend_settings')->nullable()->after('social_profiles');
            });
        }

        if (! Schema::hasColumn('users', 'backend_settings')) {
            Schema::table('users', function (Blueprint $table) {
                $table->json('backend_settings')->nullable()->after('social_profiles');
            });
        }

        $settings = $user->backend_settings ?? [];

        if ($request->hasFile('logo_image')) {
            $user->clearMediaCollection('backend_logos');
            $user->addMedia($request->file('logo_image'))->toMediaCollection('backend_logos');
            $settings['logo_image'] = $user->getFirstMediaUrl('backend_logos');
        }

        foreach (['sidebar_title','sidebar_background_color','mainbar_background_color','link_color','button_text_color','button_background_color','body_text_color'] as $field) {
            if ($request->exists($field)) {
                $settings[$field] = $validated[$field] ?? null;
            }
        }

        $user->backend_settings = $settings;
        $user->save();

        return redirect()->route('settings.index')->with(['status' => 'success', 'message' => 'Backend settings updated successfully.']);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'username' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'mobile' => 'nullable|string|max:50',
            'gender' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:1000',
            'bio' => 'nullable|string|max:2000',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'social_profiles' => 'nullable|json',
            'avatar' => 'nullable|image|max:5120',
        ]);

        if (! $request->filled('password')) {
            unset($validated['password']);
        }

        if ($request->filled('social_profiles')) {
            $validated['social_profiles'] = json_decode($request->input('social_profiles'), true);
        }

        $user->update($validated);

        if ($request->hasFile('avatar')) {
            $user->clearMediaCollection('avatars');
            $user->addMedia($request->file('avatar'))->toMediaCollection('avatars');
        }

        return redirect()->route('profile.edit')->with(['status' => 'success', 'message' => 'Profile updated successfully.']);
    }
}
