<?php

namespace App\Http\Controllers\Api;
use App\Models\Book;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(){
        $books = Book::get();
        return BookResource::collection($books);
    }



    public function store(Request $request){
        $validate = Validator::make($request->all(),[
            "title"=> "required|string|max:255",
            "author"=> "required|string|min:3",
            "description"=> "required",
        ]);
        if($validate->fails()){
            return response()->json([

                'message'=>'All Fields Required',
                'erorr'=>$validate->messages(),
            ],402);
        }
        $books= Book::create([
            "title"=> $request->title,
            "author"=> $request->author,
            "description"=> $request->description,
        ]);
        return response()->json([
            'message'=>'Book Created Successfully',
            'data'=>new BookResource($books),
        ],200);

    }



    public function show(Book $book){
        $books = Book::findOrFail( $book->id);
        return new BookResource($books);
    }

    

    public function update(Request $request, Book $book){
        $books = Book::findOrFail( $book->id);
        $validate = Validator::make($request->all(),[
            "title"=> "required|string|max:255",
            "author"=> "required|string|min:3",
            "description"=> "required",
        ]);
        if($validate->fails()){
            return response()->json([

                'message'=>'All Fields Required',
                'erorr'=>$validate->messages(),
            ],402);
        }

        $books->update([
            "title"=> $request->title,
            "author"=> $request->author,
            "description"=> $request->description,
        ]);
        return response()->json([
            'message'=>'Book Updated Successfully',
            'data'=>new BookResource($books),
        ],200);

    }
    public function destroy(Book $book){
        $book->delete();
        return response()->json([
            'message'=>'Book Deleted Successfully',
        ],200);
    }
}
