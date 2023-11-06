<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Book;
use App\BookReview;
use App\Http\Requests\PostBookReviewRequest;
use App\Http\Resources\BookReviewResource;
use Auth;

class BooksReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(int $bookId, PostBookReviewRequest $request)
    {
        $book = Book::where('id', '=', $bookId)->first();

        if ($book === null) {
            return response()->json([
                'description' => "Invalid Book ID provided."
            ], 404);
        }

        $bookReview = new BookReview();
        $requestData = $request->all();
        $bookReview->comment = $requestData['comment'];
        $bookReview->review = $requestData['review'];
        $bookReview->user_id = Auth::id();
        $bookReview->book_id = $bookId;
        $bookReview->save();

        return new BookReviewResource($bookReview);
    }

    public function destroy(int $bookId, int $reviewId)
    {
        $bookReview = BookReview::findOrFail($reviewId);
        $bookIdInDb = $bookReview->book->id == $bookId;

        if ($bookReview && $bookIdInDb) {
            $bookReview->delete();
        } else {
            return response()->json([
                'description' => "Invalid Book ID or Review ID provided."
            ], 404);
        }

        return response()->noContent();
    }
}
