<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use app\Models\BookRequest;

class QuickStart extends Component
{
    public function render(): View|Closure|string
    {
        try{
            $requests = BookRequest::with('latestRequestInfo')
                ->orderBy('created_at', 'desc')
                ->take(7)->get();

            return view('librarian.widgets.quick-start', compact('requests'));
        }
        catch(\Throwable $e){
            return back()->with('error', 'database exception');
        }
    }
}
