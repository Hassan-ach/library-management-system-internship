<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BookStatistics extends Component
{
    public function render(): View
    {   
        $total_books = count_total_books();
        $non_available_books = get_non_available_books();
        $available_books = $total_books - $non_available_books;

        return view('librarian.widgets.book-statics',
         compact(['total_books', 'non_available_books', 'available_books'])
        );
    }
}
