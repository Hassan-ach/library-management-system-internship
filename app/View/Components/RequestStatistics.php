<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RequestStatistics extends Component
{
    public function render(): View|Closure|string
    {
        $statics = get_requests_statics();
        
        $returned_books = $statics->returned_books;
        $approved_requests = $statics->approved_requests;
        $rejected_requests = $statics->rejected_requests;
        $overdue_requests = $statics->overdue_requests;

        return view('librarian.widgets.request-statics',  compact(
            ['returned_books',
                       'approved_requests',
                       'rejected_requests',
                       'overdue_requests']
        ));
    }
}
