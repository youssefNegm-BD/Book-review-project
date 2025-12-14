<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Book;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;


class ReviewController extends Controller
{
    public function index(Request $request){
        $reviews = Review::with('book','user')->orderBy('created_at','DESC');
        $books = Book::orderBy('created_at','DESC');       
        if(!empty($request->search)){
            $reviews =$reviews->where('review','like','%'.$request->search.'%');
        }
        $reviews = $reviews->paginate(5);
        return view('account.reviews.list',[
            'reviews' =>$reviews
        ]);
    }

    public function edit($id){
        $review = Review::findOrFail($id);
        return view('account.reviews.edit',[
            'review' => $review
        ]);
    }

    public function updateReview($id, Request $request){

        $review = Review::findOrFail($id);
        $validator = Validator::make($request->all(),[
            'review' =>'required',
            'status' =>'required'
        ]);
        
        if($validator->fails()){
            return redirect()->route('account.reviews.edit',$id)->withInput()->withErrors($validator);
        }
        $review->review = $request->review;
        $review->status = $request->status;
        $review->save();

        session()->flash('success', 'Review Updated successfully');
        return redirect()->route('account.reviews');
    }


    public function deleteReview(Request $request){
        $id = $request->id;
        $review = Review::find($id);
        if($review == null){
            session()->flash('error', 'Review Not Found');
            return response()->json([
                'status' =>false
            ]);
        }else{
            $review->delete();
            session()->flash('success', 'Review Deleted successfully');
            return response()->json([
                'status' =>true
            ]);
        }
    }
}
