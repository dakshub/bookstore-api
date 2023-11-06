<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Book;
use App\Http\Resources\BookResource;

class BooksController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $book = Book::filter(request(['sortColumn', 'sortDirection', 'authors', 'title']));

        return BookResource::collection($book->paginate());
    }
}
