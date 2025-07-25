<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    // <<<<<<<<<<<< Read user(s) method
    public function index(Request $request)
    {
        // try {
            $users = User::latest()->paginate(25);

            return view('/admin/users/index', compact('users'));
        // }
        //  catch (\Exception $e) {
        //     return redirect()->route('admin.users.all')
        //         ->with('error', 'Unable to load users: '.$e->getMessage());
        // }
    }

    // <<<<<<<<<<<<<<<<<<<< search user

    public function search(Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');
        $status = $request->input('status');

        $users = User::query()
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
                });
            })
            ->when($role, function ($query, $role) {
                return $query->where('role', $role);
            })
            ->when($status, function ($query, $status) {
                return $query->where('is_active', $status == 'active');
            })
            ->orderBy('id')
            ->paginate(25);

        return view('admin.users.index', compact('users'));
    }
    // >>>>>>>>>>>>>>>>>>>> search user

    // <<<<<<<<<<<<<<<<<<<< Create user(s) method

    public function create_page(){
        return view("admin.users.create");
    }
    public function create(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => ['required', Password::min(8)->mixedCase()->numbers()],
                'is_active' => 'sometimes|boolean',
                'role' => 'required|string|in:student,admin,librarian',
            ]);

            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'is_active' => $validated['is_active'] ?? true,
                'role' => $validated['role'],
            ]);

            return redirect()->route('admin.users.all')
                ->with('success', 'User created successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating user: '.$e->getMessage());
        }
    }

    // >>>>>>>>>>>>>>>>>>>> Create user(s) method


    public function update_page($id){
        try {
            $user = User::findOrFail($id); // This fetches the user
            return view('admin.users.update', compact('user')); // Pass user to view
        } catch (\Exception $e) {
            return redirect()->route('admin.users.all')
                ->with('error', 'User not found: '.$e->getMessage());
        }
    }
    // Edit Users method

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        try {
            $validated = $request->validate([
                'first_name' => 'sometimes|string|max:255',
                'last_name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:users,email,'.$user->id,
                'password' => ['sometimes', 'nullable', Password::min(8)->mixedCase()->numbers()],
                'is_active' => 'sometimes|boolean',
                'role' => 'sometimes|string|in:student,admin,librarian',
            ]);

            // Update only if the field exists in request and is different
            $updatableFields = ['first_name', 'last_name', 'is_active'];
            $changesMade = false;
            
            foreach ($updatableFields as $field) {
                if ($request->has($field)) {
                    $user->$field = $request->$field;
                    $changesMade = true;
                }
            }


            if ($changesMade) {
                $user->save();
                return redirect()->route('admin.users.all')
                    ->with('success', 'User updated successfully');
            }

            return redirect()->back()->with('info', 'No changes were made');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating user: '.$e->getMessage())
                ->withInput();
        }
    }


    public function delete($id) // Change parameter to $id for consistency with route
{
    $authUser = Auth::user();
    $userToDelete = User::findOrFail($id);

    // Prevent self-deletion
    if ($userToDelete->id === $authUser->id) {
        return redirect()->back()
            ->with('error', 'You cannot delete your own account!');
    }

    // Protection against deleting admin accounts
    if ($userToDelete->role === UserRole::ADMIN->value) {
        return redirect()->back()
            ->with('error', 'Admin accounts cannot be deleted!');
    }

    try {
        $userToDelete->delete();
        
        return redirect()->route('admin.users.all')
            ->with('success', "User #{$id} deleted successfully");
            
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Error deleting user: '.$e->getMessage());
    }
}

    public function exportExcel(){
        $users = User::all();
        return Excel::download( new UsersExport($users),'users.xlsx');
    }
}
