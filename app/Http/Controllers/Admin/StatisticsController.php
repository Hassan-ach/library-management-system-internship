<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RequestStatus;
use App\Enums\UserRole;
use App\Exports\BooksExport;
use App\Exports\LibrariansExport;
use App\Exports\StudentsExport;

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
                ->with('error', 'Erreur lors du chargement des données des étudiants : ' . $e->getMessage());
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
                return redirect()->back()->with('info', 'Aucun utilisateur trouvé correspondant à vos critères.');
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
                    return redirect()->back()->with('info', 'Aucun utilisateur trouvé correspondant à vos critères.');
                }
            return view('admin.statistics.librarian', compact('users'));
            }catch (\Exception $e) {
                return redirect()->back()
                    ->with('error', 'Erreur lors du chargement des données des bibliothécaires: ' . $e->getMessage());
            }
}

    public function books_stat(Request $request)
    {
        // try {
        $books = Book::latest()->paginate(20);

        return view('admin.statistics.books', compact('books'));

    }

    public function search_book(Request $request)
    {
        try {
            $query = $request->input('search');

            $books = Book::where('title', 'like', "%{$query}%")
                ->paginate(20);

            if(!$books->count()) {
                return redirect()->back()->with('info', 'Aucun livre trouvé correspondant à vos critères.');
            }
            return view('admin.statistics.books')->with('books', $books);

        } catch (\Exception $e) {
             return redirect()->back()
                ->with('error', 'Erreur lors du chargement des données des livres: ' . $e->getMessage());

        }
    }

    public function librarian_stat(Request $request)
    {
        try {
            $users = User::where('role', 'librarian')->paginate(15);

            return view('admin.statistics.librarian', compact('users'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Impossible de charger les utilisateurs: '.$e->getMessage());
        }

    }
    
    public function exportStudents()
    {
        $studentsQuery = User::where('role', UserRole::STUDENT->value)
                    ->orderBy('last_name')
                    ->orderBy('first_name');
    
        return Excel::download(new StudentsExport($studentsQuery), 'students_' . now()->format('Y-m-d') . '.xlsx');
    }

    public function exportLibrarians()
    {
        $query = User::where('role', UserRole::LIBRARIAN->value)
                ->orderBy('last_name')
                ->orderBy('first_name');
        
        return Excel::download(
            new LibrariansExport($query),
            'librarians_' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function exportBooks()
    {
        $query = Book::query()
            ->orderBy('title')
            ->select(['title', 'isbn', 'number_of_pages', 'total_copies']);

        return Excel::download(
            new BooksExport($query),
            'books_export_' . now()->format('Y-m-d') . '.xlsx'
        );
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
                    'created_diff' => "il y'a " . str_replace([' hours ago', 'hour ago'], 'h', $latestInfo?->created_at->diffForHumans()),
                    'processed_diff' => $latestInfo->processed_at ? $latestInfo->processed_at->diffForHumans() : null,
                    'is_first' => false, // We'll set this for the first item
                ];
            });

        // Mark the first request for special display
        if ($requests->count() > 0) {
            $requests->first()['is_first'] = true;
        }

        return view('admin.statistics.users_history', [
            'user' => $user,
            'requests' => $requests,
            'totalRequests' => $requests->total(),
        ]);
    }

    public function librarian_history(Librarian $user)
    {
        $requests = BookRequest::with(['book', 'RequestInfo.user', 'user'])
                      ->whereHas('requestInfo', function($q) use ($user) {
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
                            $librarian = $latestInfo->user->first_name . ' ' . $latestInfo->user->last_name;
                            $responseDate = $latestInfo?->created_at ?? null;
                        }

                        return [
                            'id' => $request->id,
                            'created_at' => $latestInfo->created_at,
                            'created_diff' => "il y'a " . str_replace([' hours ago', 'hour ago',' day ago'], ['h','h','j'], $latestInfo?->created_at->diffForHumans()),
                            'response_date' => $responseDate,
                            'book_title' => $request->book->title ?? 'N/A',
                            'status' => $latestInfo?->status ?? 'pending',
                            'requested_at' => $request->created_at->format('d/m/Y H:i'),
                            'requested_by' => $request->user->first_name . ' ' . $request->user->last_name,
                            'librarian' => $librarian,
                        ];
                    });

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
            ->paginate(2)
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
                    'user_name' => $request->user->first_name . ' ' . $request->user->last_name,
                    'borrow_date' => $borrowInfo->created_at->format('Y-m-d H:i'),
                    'librarian_borrowed' => $borrowInfo->user->first_name . ' ' . $borrowInfo->user->first_name ?? "n'est pas emprunté",
                    'return_date' => $returnInfo ? $returnInfo->created_at->format('Y-m-d H:i') : null,
                    'librarian_returned' => $returnInfo->user->first_name . ' ' . $returnInfo->user->lastname ?? 'non retourner',
                    'is_returned' => !is_null($returnInfo),
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
