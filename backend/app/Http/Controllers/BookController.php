<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Review;
use Gate;

class BookController extends Controller
{
    use ApiResponser;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        if(Gate::denies('manage-users')){
            return $this->UNAUTHORIZED("UNAUTHORIZED! Only Admin can access this page");
        }
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:3|max:100',
            'isbn' => 'required|digits:13',
            'description' => 'required|string|min:5'
        ], [
            'isbn.required' => 'ISBN is required',
            'title.required' => 'Title is required',
            'description.required' => 'Description is required'
        ]);
        if ($validator->fails())
        {
            return $this->validationError($validator->messages()->first());
        }
        $duplicate = $this->CheckDuplicateIsbn($request->isbn);
        if($duplicate == null){
            $book = new Book();
            $book->isbn = $request->isbn;
            $book->title = $request->title;
            $book->description = $request->description;
            if($book->save())
            {
                $book->authors()->sync($request->author_id);
                return $this->createResponse($book);
            }
            else{
                return $this->noResponse('An error occurred!');
            }
        }else{
            return $this->validationError("ISBN Already exist!");
        }
    }

    public function index()
    {
        $books =  Book::with('authors')->paginate(15);
        return $this->successResponse($books);
    }

    public function review(Request $request, $bookId)
    {
        $book = Book::where('id', $bookId)->first();
        if($book != null)
        {
            $validator = Validator::make($request->all(), [
                'review' => 'required|integer|between:1,10',
                'comment' => 'required|min:2|max:300'
            ], [
                'review.required' => 'Review field is required',
                'comment.required' => 'Comment field is required'
            ]);
            if ($validator->fails())
            {
                return $this->validationError($validator->messages()->first());
            }
            $rev = new Review();
            $rev->review = $request->review;
            $rev->comment = $request->comment;
            $rev->book_id = $bookId;
            $rev->user_id = Auth::user()->id;
            if($rev->save()){
                return $this->createResponse($rev);
            }
            else{
                return $this->noResponse('An error occurred!');
            }
        }else{
            return $this->notFound("Invalid book Id");
        }
    }
    public function GetReview()
    {
        if(Gate::denies('manage-users')){
            return $this->UNAUTHORIZED("UNAUTHORIZED! Only Admin can access this page");
        }

        $books =  Review::with('user')->get();
        return $this->successResponse($books);
    }
}
