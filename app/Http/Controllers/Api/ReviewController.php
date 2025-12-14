<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Http\Resources\ReviewResource;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function index(){
        $review = Review::get();
        return ReviewResource::collection($review);
    }

    public function show(Request $request ){
        
        $review = Review::where('book_id',$request->book_id)->get();
        return response()->json([
            "Data"=>ReviewResource::collection( $review ),
        ]);
    }

    public function destroy(Review $review){
            
            $review->delete();
            return response()->json([
                'message'=>"review deleted successfully"
            ],201);
    }


}
