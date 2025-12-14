<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;



class bookController extends Controller
{
    public function index(Request $request){
        $books = Book::orderBy('created_at','ASC');
        if(!empty($request->search)){
            $books->where('title','like','%'.$request->search .'%');
        }

        $books = $books->withCount('reviews')->withSum('reviews','rating')->paginate(5);
        return view('Books.list',[
            'books' =>$books
        ]);
    }

    public function create(){
        return view('Books.create');
    }


    public function store(Request $request){

        $rules =[
            'title'=>'required|min:5',
            'author'=>'required|min:3',
            'status'=>'required'
        ];

        if(!empty($request->image)){
            $rules['image'] = 'image';
        }
        
        $validator = Validator::make($request->all(),$rules);   

        if($validator->fails()){
            return redirect()->route('books.create')->withInput()->withErrors($validator);
        }

        //save book in DB
        $book = new Book();
        $book->title = $request->title;
        $book->author = $request->author;
        $book->description = $request->description;
        $book->status = $request->status;
        $book->save();

        //upload book image
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName =time().'.'.$ext;
            $image->move(public_path('uploads/book'),$imageName);

            $book->image = $imageName;
            $book->save();

            $manager = new ImageManager(Driver::class);
            $img = $manager->read(public_path('uploads/book/'.$imageName));
            $img->resize(990);
            $img->save(public_path('uploads/book/thumb/'.$imageName));
    

        return redirect()->route('books.index')->with('success','The Book Has Created');


    }

    public function edit($id){
        $book =Book::findOrFail($id);

        return view('Books.edit',[
            'book' => $book
        ]);

    }

    public function update($id, Request $request){

        $book =Book::findOrFail($id);

        $rules =[
            'title'=>'required|min:5',
            'author'=>'required|min:3',
            'status'=>'required'
        ];

        if(!empty($request->image)){
            $rules['image'] = 'image';
        }
        
        $validator = Validator::make($request->all(),$rules);   

        if($validator->fails()){
            return redirect()->route('books.edit',$book->id)->withInput()->withErrors($validator);
        }

        //save Updates in DB

        $book->title = $request->title;
        $book->author = $request->author;
        $book->description = $request->description;
        $book->status = $request->status;
        $book->save();

        //Delete Last Image
        File::delete(public_path('uploads/book/'.$book->image));
        File::delete(public_path('uploads/book/thumb/'.$book->image));
    

        //Update book image
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName =time().'.'.$ext;
            $image->move(public_path('uploads/book'),$imageName);

            $book->image = $imageName;
            $book->save();

            $manager = new ImageManager(Driver::class);
            $img = $manager->read(public_path('uploads/book/'.$imageName));
            $img->resize(990);
            $img->save(public_path('uploads/book/thumb/'.$imageName));
    

        return redirect()->route('books.index')->with('success','The Book Has Updated');

    }

public function destroy(Request $request, $id){
    $book = Book::find($id);
    
    if(!$book){
        session()->flash('error', 'Book not found');
        return redirect()->route('books.index'); 
    }
    
    if($book->image){
        File::delete(public_path('uploads/book/'.$book->image));
    }
    
    if($book->thumb){
        File::delete(public_path('uploads/book/thumb/'.$book->thumb));
    }
    
    $book->delete();
    
    session()->flash('success', 'Book Deleted Successfully');
    return redirect()->route('books.index'); 
}
}
