<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Book extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'isbn',
        'title',
        'description',
        'published_year'
    ];

    protected $with = ['authors'];

    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['title'])) {
            $query->where('title', 'like', '%' . $filters['title'] . '%');
        }

        if (isset($filters['authors'])) {
            $authors = (strpos($filters['authors'], ',') !== false) ?
                explode(',', $filters['authors']) : [$filters['authors']];
            $query->whereHas('authors', function ($authorsQuery) use ($authors) {
                $authorsQuery->whereIn('author_id', $authors);
            });
        }

        if (isset($filters['sortColumn'])) {
            $sortDirection = (isset($filters['sortDirection']) &&
                strtoupper($filters['sortDirection']) == 'DESC') ? 'DESC' : 'ASC';

            if ($filters['sortColumn'] == 'avg_review') {
                $query->withCount(['reviews as average_review' => function ($reviewQuery) {
                    $reviewQuery->select(DB::raw('avg(review)'));
                }])->orderBy('average_review', $sortDirection);
            } else {
                $query->OrderBy($filters['sortColumn'], $sortDirection);
            }
        }
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'book_author');
    }

    public function reviews()
    {
        return $this->hasMany(BookReview::class);
    }
}
