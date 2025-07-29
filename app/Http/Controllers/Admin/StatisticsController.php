<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RequestStatus;
use App\Enums\UserRole;
use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookRequest;
use App\Models\Librarian;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StatisticsController extends Controller
{
    //
    public function dashboard()
    {
        return view('admin.statistics.index');
    }

    public function users_stat(Request $request)
    {
        try {
            // Get students with their latest book request and related info
            $students = Student::with(['bookRequests' => function ($query) {
                $query->with(['latestRequestInfo', 'book'])

                    ->limit(1);
            }])
                ->latest()
                ->paginate(22);

            return view('admin.statistics.users', ['users' => $students]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error loading student data: '.$e->getMessage());
        }
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $activity = $request->input('activity');

        $users = Student::query()
            ->with(['bookRequests' => function ($query) {
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
                return $query->whereHas('bookRequests.latestRequestInfo', function ($q) use ($activity) {
                    $q->where('status', RequestStatus::from($activity));
                });
            })
            ->orderBy('id')
            ->paginate(20);

        if (! $users->count()) {
            return redirect()->back()->with('info', 'No users found matching your criteria.');
        }

        return view('admin.statistics.users', compact('users'));
    }

    public function search_librarian(Request $request)
    {
        try {
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

            if (! $users->count()) {
                return redirect()->back()->with('info', 'No users found matching your criteria.');
            }

            return view('admin.statistics.librarian', compact('users'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error loading librarian data: '.$e->getMessage());
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
            $users = User::where('role', 'librarian')->paginate(15);

            return view('admin.statistics.librarian', compact('users'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Unable to load users: '.$e->getMessage());
        }

    }

    public function exportUsers()
    {
        $users = User::all();

        return Excel::download(new UsersExport($users), 'users.xlsx');
    }

    public function exportlibrarian()
    {
        $users = User::all();

        return Excel::download(new UsersExport($users), 'users.xlsx');
    }

    public function exportBooks()
    {
        $users = User::all();

        return Excel::download(new UsersExport($users), 'users.xlsx');
    }

    public function user_history(Student $user)
    {
        $requests = BookRequest::with(['book', 'RequestInfo'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(5) // Changed to paginate
            ->through(function ($request) {
                $latestInfo = $request->RequestInfo->sortByDesc('created_at')->first();

                $librarian = 'En attente de traitement';
                $isPending = $latestInfo?->status === 'pending';
                $processed_at = null;

                if (! $isPending && $latestInfo?->user && $latestInfo->user->role->value === UserRole::LIBRARIAN->value) {
                    $librarian = $latestInfo->user->first_name.' '.$latestInfo->user->last_name;
                    $processed_at = $latestInfo?->created_at ?? null;
                }

                return [
                    'id' => $request->id,
                    'created_at' => $request->created_at,
                    'book_title' => $request->book->title ?? 'N/A',
                    'status' => $latestInfo->status,
                    'processed_at' => $processed_at,
                    'processed_by' => $librarian,
                    'created_diff' => $request->created_at->diffForHumans(),
                    'processed_diff' => $latestInfo->processed_at ? $latestInfo->processed_at->diffForHumans() : null,
                    'is_first' => false, // We'll set this for the first item
                ];
            });

        // Mark the first request for special display
        if ($requests->count() > 0) {
            $requests->first()['is_first'] = true;
        }

        // if($requests[])

        return view('admin.statistics.users_history', [
            'user' => $user,
            'requests' => $requests,
            'totalRequests' => $requests->total(),
        ]);
    }

    public function librarian_history(Librarian $user)
    {
        $requests = BookRequest::with(['book', 'RequestInfo.user', 'user'])
            ->whereHas('requestInfo', function ($q) use ($user) {
                $q->where('user_id', $user->id); // Filter by librarian's actions
            })
            ->latest()
            ->paginate(15)
            ->through(function ($request) {
                $latestInfo = $request->RequestInfo->sortByDesc('created_at')->first();

                // Gestion du bibliothécaire
                $librarian = 'Pending processing';
                $responseDate = null;

                if ($latestInfo && $latestInfo->status !== 'pending' && $latestInfo->user && $latestInfo->user->role === UserRole::LIBRARIAN->value) {
                    $librarian = $latestInfo->user->first_name.' '.$latestInfo->user->last_name;
                    $responseDate = $latestInfo?->created_at ?? null;
                }

                return [
                    'id' => $request->id,
                    'created_at' => $request->created_at,
                    'created_diff' => $latestInfo?->created_at->format('d/m/Y H:i'),
                    'response_date' => $responseDate,
                    'book_title' => $request->book->title ?? 'N/A',
                    'status' => $latestInfo?->status ?? 'pending',
                    'requested_at' => $request->created_at->format('d/m/Y H:i'),
                    'requested_by' => $request->user->first_name.' '.$request->user->last_name,
                    'librarian' => $librarian,
                ];
            });

        // Mark the first request for special display
        if ($requests->count() > 0) {
            $requests->first()['is_first'] = true;
        }

        // if($requests[])

        return view('admin.statistics.librarian_history', [
            'user' => $user,
            'requests' => $requests,
            'totalRequests' => $requests->total(),
        ]);
    }

    // app/Http/Controllers/BookController.php
    public function history(Book $book)
    {
        $borrowings = BookRequest::with(['user', 'requestInfo.user'])
            ->where('book_id', $book->id)
            ->whereHas('requestInfo', function ($query) {
                $query->where('status', 'borrowed');
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->through(function ($request) {
                $borrowInfo = $request->requestInfo
                    ->where('status', 'borrowed')
                    ->sortByDesc('created_at')
                    ->first();

                $returnInfo = $request->requestInfo
                    ->where('status', 'returned')
                    ->sortByDesc('created_at')
                    ->first();

                return [
                    'user_name' => $request->user->full_name,
                    'borrow_date' => $borrowInfo->created_at->format('Y-m-d H:i'),
                    'librarian_borrowed' => $borrowInfo->user->full_name ?? 'System',
                    'return_date' => $returnInfo ? $returnInfo->created_at->format('Y-m-d H:i') : null,
                    'librarian_returned' => $returnInfo->user->full_name ?? null,
                    'is_returned' => ! is_null($returnInfo),
                    'duration' => $returnInfo
                        ? $borrowInfo->created_at->diffForHumans($returnInfo->created_at, true)
                        : $borrowInfo->created_at->diffForHumans(now(), true),
                ];
            });

        return view('admin.statistics.book_history', [
            'book' => $book,
            'borrowings' => $borrowings,
        ]);
    }
}
