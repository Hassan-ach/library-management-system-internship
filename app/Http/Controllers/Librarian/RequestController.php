<?php

namespace App\Http\Controllers\Librarian;

use App\Enums\RequestStatus;
use App\Http\Controllers\Controller;
use App\Models\BookRequest;
use App\Models\RequestInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Enum;

class RequestController extends Controller
{
    //
    public function processe(Request $req, $reqId)
    {
        if (! Gate::allows('processe_req')) {
            return back()
                ->with(['error' => 'You\'re not allowed to process this request']);
        }
        try {
            if (! BookRequest::where('id', $reqId)->exists()) {
                return back()->with('error', 'Invalid request ID');
            }

            $status = $req->validate([
                'status' => ['required', new Enum(RequestStatus::class)],
            ]);

            RequestInfo::create([
                'user_id' => Auth::user()->id,
                'request_id' => $reqId,
                'status' => $status['status'],
            ]);

            return back()->with(['message' => 'status updated successfully']);

        } catch (\Throwable $th) {
            return back()
                ->with(['error' => 'Error while updating request']);
        }

    }

    public function index(Request $req)
    {
        //
        try {
            $requests = BookRequest::with('latestRequestInfo')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('librarian.viewAllRequests', compact('requests'));

        } catch (\Throwable $th) {
            return back()->with(['error' => 'Error while fetching requests']);

        }
    }

    public function show(Request $req, $reqId)
    {
        try {
            $request = BookRequest::with('requestInfo.user', 'user', 'book')
                ->findOrFail($reqId);

            return view('librarian.viewSingleRequest', compact('request'));

        } catch (\Throwable $th) {
            return back()->with(['error' => 'Error while fetching the request information']);
        }
    }
}
