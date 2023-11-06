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
        $requestData = $request->all();
        $book->title = $requestData['title'];
        $book->isbn = $requestData['isbn'];
        $book->published_year = $requestData['published_year'];
        $book->description = $requestData['description'];
        $book->save();
        $book->authors()->attach($requestData['authors']);

        return new BookResource($book);
    }
}
