<?php

namespace App\Http\Controllers\Student;

use App\Enums\RequestStatus;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookRequest;
use App\Models\RequestInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Request;

class BookRequestController extends Controller
{
    //
    public function add(Request $req, $bookId)
    {
        $user = Auth::user();
        $book = Book::findOrFail($bookId);

        if (! Gate::allows('borrow_books', $book)) {
            return back()->with(['error' => 'You\'re not allowed to borrow this book']);

        }

        DB::beginTransaction();

        try {
            $bookReq = BookRequest::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
            ]);

            RequestInfo::create([
                'user_id' => $user->id,
                'request_id' => $bookReq->id,
                'status' => RequestStatus::PENDING,
            ]);

            DB::commit();

            return back()->with(['success' => 'Request submitted successfully']);

        } catch (\Throwable $th) {
            DB::rollBack();

            return back()
                ->with(['error' => 'Error while submitting request'])
                ->setStatusCode(422);
        }
    }

    public function cancel(Request $req, $reqId)
    {
        //
        $bookReq = BookRequest::findOrFail($reqId);
        $user = Auth::user();

        if (! Gate::allows('cancel_req', $bookReq)) {
            return back()->with(['error' => 'You\'re not allowed to cancel this book request']);
        }

        try {
            RequestInfo::create([
                'user_id' => $user->id,
                'request_id' => $bookReq->id,
                'status' => RequestStatus::CANCELED,
            ]);

            return back()->with(['success' => 'Request canceled successfully']);

        } catch (\Throwable $th) {
            // throw $th;

            return back()
                ->with(['error' => 'Error while canceling request'])
                ->setStatusCode(422);
        }
    }

    public function show(Request $req, $reqId)
    {
        //
    }
}
