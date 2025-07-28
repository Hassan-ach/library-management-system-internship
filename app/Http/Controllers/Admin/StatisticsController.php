<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RequestStatus;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Book;
use App\Models\BookRequest;
use App\Models\Librarian;
use App\Models\Requestinfo;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class StatisticsController extends Controller
{
    //
    public function dashboard(){
        return view('admin.statistics.index');
    }


public function users_stat(Request $request)
{
    try {
        // Get students with their latest book request and related info
        $students = Student::with(['bookRequests' => function($query) {
                $query->with(['latestRequestInfo', 'book'])
                    
                    ->limit(1);
            }])
            ->latest()
            ->paginate(22);

        return view('admin.statistics.users', ['users' => $students]);
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Error loading student data: ' . $e->getMessage());
    }
}

    public function search(Request $request)
{
    $search = $request->input('search');
    $status = $request->input('status');
    $activity = $request->input('activity');

    $users = Student::query()
        ->with(['bookRequests' => function($query) {
            $query->with(['latestRequestInfo', 'book'])
                ->latest()
                ->limit(1);
        }])
        ->when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        })
        ->when($status, function ($query, $status) {
            return $query->where('is_active', $status == 'active');
        })
        ->when($activity, function ($query, $activity) {
            return $query->whereHas('bookRequests.latestRequestInfo', function($q) use ($activity) {
                $q->where('status', RequestStatus::from($activity));
            });
        })
        ->orderBy('id')
        ->paginate(20);

        if(!$users->count()) {
            return redirect()->back()->with('info', 'No users found matching your criteria.');
        }
    return view('admin.statistics.users', compact('users'));
}

    public function search_librarian(Request $request)
{
    try{
        $search = $request->input('search');
        $status = $request->input('status');

        $users = Librarian::query()
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query, $status) {
                return $query->where('is_active', $status == 'active');
            })
            ->orderBy('id')
            ->paginate(5);

            if(!$users->count()) {
                return redirect()->back()->with('info', 'No users found matching your criteria.');
            }
        return view('admin.statistics.librarian', compact('users'));
        }catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error loading librarian data: ' . $e->getMessage());
        }
}

    public function books_stat(Request $request)
    {
        // try {
            $books = Book::latest()->paginate(20);

            return view('admin.statistics.books', compact('books'));

    }

    public function librarian_stat(Request $request)
    {
        try {
            $users = User::where('role','librarian')->paginate(15);

            return view('admin.statistics.librarian', compact('users'));
            }
         catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Unable to load users: '.$e->getMessage());
        }

    }
    
    public function exportUsers(){
        $users = User::all();
        return Excel::download( new UsersExport($users),'users.xlsx');
    }

    public function exportlibrarian(){
        $users = User::all();
        return Excel::download( new UsersExport($users),'users.xlsx');
    }

    public function exportBooks(){
        $users = User::all();
        return Excel::download( new UsersExport($users),'users.xlsx');
    }

    public function user_history(Student $user)
    {
        $requests = BookRequest::with(['book', 'RequestInfo'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(15) // Changed to paginate
            ->through(function ($request) {
                $latestInfo = $request->RequestInfo->sortByDesc('created_at')->first();
                
                return [
                    'id' => $request->id,
                    'created_at' => $request->created_at,
                    'book_title' => $request->book->title ?? 'N/A',
                    'status' => $latestInfo->status,
                    'processed_at' => $latestInfo->processed_at ?? null,
                    'created_diff' => $request->created_at->diffForHumans(),
                    'processed_diff' => $latestInfo->processed_at ? $latestInfo->processed_at->diffForHumans() : null,
                    'is_first' => false // We'll set this for the first item
                ];
            });

        // Mark the first request for special display
        if ($requests->count() > 0) {
            $requests->first()['is_first'] = true;
        }

        return view('admin.statistics.users_history', [
            'user' => $user,
            'requests' => $requests,
            'totalRequests' => $requests->total()
        ]);
    }

public function librarian_history(User $user)
{
    $requests = BookRequest::with(['book', 'requestInfo', 'user'])
        ->where('user_id', $user->id)
            ->latest()
            ->paginate(15)
        ->through(function ($request) {
            $latestInfo = $request->requestInfo->sortByDesc('created_at')->first();
            
            return [
                'id' => $request->id,
                'response_date' => $latestInfo->created_at,
                'book_title' => $request->book->title ?? 'N/A',
                'status' => $latestInfo->status ?? null,
                'requested_at' => $request->created_at,
                'requested_by' => $request->user->name,
                'response_diff' => $latestInfo->created_at->diffForHumans(),
                'requested_diff' => $request->created_at->diffForHumans(),
            ];
        });

    return view('admin.statistics.librarian_history', [
        'user' => $user,
        'requests' => $requests,
        'totalRequests' => $requests->total()
    ]);
}
}
