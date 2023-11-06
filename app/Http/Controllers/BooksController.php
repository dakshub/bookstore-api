<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Book;
use App\Http\Resources\BookResource;
use App\Http\Requests\PostBookRequest;

class BooksController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'auth.admin'])->only('store');
    }

    public function index()
    {
        $book = Book::filter(request(['sortColumn', 'sortDirection', 'authors', 'title']));

        return BookResource::collection($book->paginate());
    }

    public function store(PostBookRequest $request)
    {
        $book = new Book();
        $input_data = $request->all();
        $book->title = $input_data['title'];
        $book->isbn = $input_data['isbn'];
        $book->published_year = $input_data['published_year'];
        $book->description = $input_data['description'];
        $book->save();
        $book->authors()->attach($input_data['authors']);

        return new BookResource($book);
    }
}
