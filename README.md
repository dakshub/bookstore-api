## Introduction

A bookstore application with web API methods.\
The project contains 50 functional tests and 100% code coverage.

<img width="572" alt="Screenshot 2023-11-05 at 11 09 51 PM" src="https://github.com/dakshub/bookstore-api/assets/45903885/aa80c3cf-d619-41a0-bf83-430ec7760391">

### Setup

```
composer install
```

### Database seed

```
composer refresh-db
```

### Tests

```
composer test
```

## API Endpoints

**GET /api/books** - a list of Books with pagination, sorting and filtering options.

Available query parameters:\
`page` - page number \
`sortColumn` - one of `title`, `avg_review` or `published_year` \
`sortDirection` - one of `ASC` or `DESC` \
`title` - search by book title \
`authors` - search by author’s ID (comma-separated)

Sample response (HTTP 200)

```
{
   "data":[
      {
         "id":1,
         "isbn":"9077765476",
         "title":"Et hic et mollitia ea nihil culpa.",
         "description":"Possimus voluptatem rerum harum nemo asperiores. Consequuntur tenetur ut nemo ipsam placeat. Sunt eos cum assumenda quasi est. Dolores earum qui quod nihil commodi nisi.",
         "published_year": 2020,
         "authors":[
            {
               "id":1,
               "name":"Dr. Beth Weber PhD",
               "surname":"Jenkins"
            }
         ],
         "review":{
            "avg":4,
            "count":3
         }
      }
   ],
   "links":{
      "first":"http:\/\/localhost\/api\/books?page=1",
      "last":"http:\/\/localhost\/api\/books?page=1",
      "prev":null,
      "next":null
   },
   "meta":{
      "current_page":1,
      "from":1,
      "last_page":1,
      "path":"http:\/\/localhost\/api\/books",
      "per_page":15,
      "to":5,
      "total":5
   }
}
```

Implementation details:

1. Implemented `App\Http\Resources\BookResource::toArray` method.
2. Queried the data from `Book` Eloquent model and responded with `BookResource` collection.
3. Implemented pagination feature (from Eloquent).
4. Allowed sorting by title.
5. Allowed sorting by average review.
6. Allowed searching by title (SQL like query).
7. Allowed searching by author’s ID.

---

**POST /api/books** - creates a new Book resource.

**_Access to this endpoint requires authentication with an API token and admin privileges._**

Required parameters:\
`isbn` - string (13 characters, digits only)\
`title` - string\
`description` - string\
`authors` - int[] - author’s ID\
`published_year` - int (between 1900 and 2020)

Sample response (HTTP 201)

```
{
   "data":{
      "id":1,
      "isbn":"9788328302341",
      "title":"Clean code",
      "description":"Lorem ipsum",
      "published_year": 2020,
      "authors":[
         {
            "id":1,
            "name":"Prof. Darrin Mraz Jr.",
            "surname":"Bins"
         }
      ],
      "review":{
         "avg":0,
         "count":0
      }
   }
}
```

In case of validation errors, the API responds with the default error list from the Laravel framework and the 422 HTTP code.

Implementation details:

1. Validated the required fields.
2. Ensured that the ISBN is unique and author’s ID exist in the DB.
3. Stored Book in the DB.
4. Restricted access only for administrators with `auth.admin` middleware.
5. Responded with `BookResource`.

---

**POST /api/books/{id}/reviews** - creates a new BookReview resource.

**_Access to this endpoint requires authentication with an API token._**

Required parameters:\
`review` - int (1-10)\
`comment` - string

Sample response (HTTP 201)

```
{
   "data":{
      "id":1,
      "review":5,
      "comment":"Lorem ipsum",
      "user":{
         "id":1,
         "name":"Kody Lebsack"
      }
   }
}
```

In case of an invalid Book ID, the API responds with the 404 HTTP code.\
In case of validation errors, the API responds with the default error list from the Laravel framework and the 422 HTTP code.

Implementation details:

1. Validated the required fields.
2. Stored BookReview in the DB.
3. Restricted access only for authenticated users.
4. Responded with `BookReviewResource`.

**DELETE /api/books/{id}/reviews/{id}** - deletes a BookReview resource.

**_Access to this endpoint requires authentication with an API token and admin privileges._**

Sample response (HTTP 204)

```
{}
```

In case of an invalid Book ID, the API responds with the 404 HTTP code.\

Implementation details:

1. Validated if the book exists.
2. Deleted the book review from DB.
3. Returned an empty body and 204 status code.
