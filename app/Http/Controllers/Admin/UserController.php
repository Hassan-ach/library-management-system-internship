<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    // <<<<<<<<<<<<<<<<<<<< Create user(s) method

    public function create(Request $request)
    {
        try {
            $validated = $request->validate([
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => ['required', Password::min(8)->mixedCase()->numbers()],
                'is_active' => 'sometimes|boolean',
                'role' => 'required|string|in:student,admin,librarian',
            ]);

            $user = User::create([
                'firstname' => $validated['firstname'],
                'lastname' => $validated['lastname'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'is_active' => $validated['is_active'] ?? true,
                'role' => $validated['role'],
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', 'User created successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating user: '.$e->getMessage());
        }
    }

    // >>>>>>>>>>>>>>>>>>>> Create user(s) method

    // <<<<<<<<<<<< Read user(s) method
    public function index(Request $request)
    {
        try {
            $users = User::latest()->paginate(25);

            return view('admin.users.index', compact('users'));
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Unable to load users: '.$e->getMessage());
        }
    }

    // <<<<<<<<<<<<<<<<<<<< search user

    public function search(Request $request)
    {
        try {
            $search = $request->input('search', '');

            $users = User::where(function ($query) use ($search) {
                $query->where('firstname', 'like', '%'.$search.'%')
                    ->orWhere('lastname', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            })
                ->when($search === 'active' || $search === 'inactive', function ($query) use ($search) {
                    $query->orWhere('is_active', $search === 'active');
                })
                ->orWhereHas('role', function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%');
                })
                ->paginate(25);

            return view('admin.users.index', compact('users', 'search'));

        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Search failed: '.$e->getMessage());
        }
    }

    // Edit Users method
    public function update(Request $request, User $user)
    {

        try {
            $validated = $request->validate([
                'firstname' => 'sometimes|string|max:255',
                'lastname' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:users,email,'.$user->id,
                'password' => ['sometimes', 'nullable', Password::min(8)->mixedCase()->numbers()],
                'is_active' => 'sometimes|boolean',
                'role' => 'sometimes|string|in:student,admin,librarian',
            ]);

            $changesMade = false;

            foreach (['firstname', 'lastname', 'email', 'is_active', 'role'] as $field) {
                if ($request->has($field) && $user->$field != $request->$field) {
                    $user->$field = $request->$field;
                    $changesMade = true;
                }
            }

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
                $changesMade = true;
            }

            if ($changesMade) {
                $user->save();

                return redirect()->route('admin.users.index')
                    ->with('success', 'User updated successfully');
            }

            return redirect()->back()->with('info', 'No changes were made');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating user: '.$e->getMessage());
        }
    }

    // app/Http/Controllers/Admin/AdminUserController.php

    public function delete(User $user)
    {
        // Prevent self-deletion

        if ($user->id === Auth::user()->id) {
            return redirect()->back()
                ->with('error', 'You cannot delete your own account!');
        }

        // // protectection against deleting super admin accounts
        // if ($user->role === UserRole::ADMIN->value) {
        //     return redirect()->back()
        //         ->with('error', 'Super admin accounts cannot be deleted!');
        // }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User with id: {$user->id} deleted successfully');
    }
}
