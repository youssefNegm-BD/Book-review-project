<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request){

        $books = Book::withCount('reviews')->withSum('reviews','rating')->orderBy('created_at','DESC');
        if(!empty($request->search)){
            $books->where('title','like','%'.$request->search.'%');
        } 

        $books = $books->where('status',1)->paginate(8);
        return view('home',[
            'books' =>$books
        ]);

    }

    public function detail($id){

        $book = Book::with(['reviews.user','reviews' => function($query){
            $query->where('status',1);
        }])->withCount('reviews')->withSum('reviews','rating')->findOrFail($id);
        
        if($book->status == 0){
            abort(404);
        }

        $relatedBooks = Book::where('status',1)
                        ->withCount('reviews')
                        ->withSum('reviews','rating')
                        ->take(3)->where('id' ,'!=' ,$id)->inRandomOrder()->get();

        return view('book-detail',[
            'book' => $book,
            'relatedBooks' =>$relatedBooks
        ]); 
    }

    public function saveReview(Request $request, $id){

        // $validator = Validator::make($request->all(),[
        //     'review' =>'required|min:10',
        //     'rating' =>'required'
        // ]);
        // if($validator->fails()){
        //     return response()->json([
        //         'status' => false,
        //         'errors' =>$validator->errors()
        //     ]);
        // }
        
        $review = new Review();
        $review->review = $request->review;
        $review->rating = $request->rating;
        $review->user_id = Auth::user()->id;
        $review->book_id = $id;
        $review->save();
        
        session()->flash('success','Review Submitted Successfully');
        return redirect('/');
        // return response()->json([
        //     'status' => true,
            
        // ]);
    }
}
