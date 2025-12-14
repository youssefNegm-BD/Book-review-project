<?php

namespace App\Http\Controllers;



use App\Models\User;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;




class AccountController extends Controller
{

    //method to show register page
    public function register(){
        return view('account.register'); 
    }

    public function processRegister(Request $request){
        $validator = Validator::make($request->all(),[
            'name' =>'required|min:3',
            'email' =>'required|email|unique:users',
            'password' =>'required',
            // 'password_confirmation' =>'required',
        ]);
        if($validator->fails()){
            return redirect()->route('account.register')->withInput()->withErrors($validator);
        }else{
                //register user
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->route('account.login')->with('success','You Have Registerd Successfully.');

        }


    }

    // login Methods..............

    public function login(){
        return view('account.login');
    }

    public function authenticate(Request $request){
        $validator = Validator::make($request->all(),[
            'email' =>'required|email',
            'password'=>'required'
        ]);
        if ($validator->fails()){
                
            return redirect()->route('account.login')->withInput()->withErrors($validator);
        }

        if (Auth::attempt(['email' =>$request->email,'password'=>$request->password])){
            return redirect()->route('account.profile');
        }else{
            return redirect()->route('account.login')->with('error','Email or Password is incorrect');

        }
    }

    //profile Methods.................................

    //Show Profile
    public function profile(){
        $user = User::find(Auth::user()->id);
        return view('account.profile',[
            'user'=>$user
        ]);
        
    }

    //Update Profile
    public function updateProfile(Request $request){


        $rules =[
            'name'=>'required',
            'email'=>'required|email|unique:users,email,'.Auth::user()->id.'id',
            'image'=>'required|mimes:jpg,png,pdf'

        ];
        
        $validator= Validator::make($request->all(),$rules);
        
        if($validator->fails()){
            return redirect()->route('account.profile')->withInput()->withErrors($validator);
        }
        $user = User::find(Auth::user()->id);
        $user->name =$request->name;
        $user->email =$request->email;
        $user->save();


        
        //Delete old images
        File::delete(public_path('uploads/profile/'.$user->image));
        File::delete(public_path('uploads/profile/thumb/'.$user->image));

        //Ipload Image 
        $image = $request->image;
        $ext = $image->getClientOriginalExtension();
        $imageName =time().'.'.$ext;
        $image->move(public_path('uploads/profile'),$imageName);

        $user->image = $imageName;
        $user->save();

        $manager = new ImageManager(Driver::class);
        $img = $manager->read(public_path('uploads/profile/'.$imageName));
        $img->cover(150, 150);
        $img->save(public_path('uploads/profile/thumb/'.$imageName));

        return redirect()->route('account.profile')->with('success','Profile Updated successfully');
    }

    // log out...................................
    public function logout(){
        Auth::logout();
        return redirect()->route('account.login'); 
    }


    //My Reviews Methods.................................

        //Method To view Myreviews From DB
    public function myReview(){
        $reviews = Review::with('book')->where('user_id', Auth::user()->id)->orderBy('created_at','DESC')->paginate(5);
        return view('account.My-Reviews.myReviews',[
            'reviews' =>$reviews
        ]);
    }

    //Method To Edit Myreviews 
    public function editMyReviews($id){
        $review = Review::where([
            'id' => $id,
            'user_id' => Auth::user()->id
        ])->with('book')->first();

        return view('account.My-Reviews.edit-review',[
            'review' =>$review
        ]);
    }

    //Method To Update Myreviews to DB
    public function updateMyReview($id, Request $request){

        $review = Review::findOrFail($id);
        $validator = Validator::make($request->all(),[
            'review' =>'required',
            'rating' =>'required'
        ]);
        
        if($validator->fails()){
            return redirect()->route('account.myReview.editMyReviews',$id)->withInput()->withErrors($validator);
        }
        $review->review = $request->review;
        $review->rating = $request->rating;
        $review->save();

        session()->flash('success', 'Review Updated successfully');
        return redirect()->route('account.myReview');
    }

    //Method To Delete Myreviews From DB
    public function deleteMR(Request $request){
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
    //............................................................
    //Methods change password
    public function changePass(){
        return view('account.changepassword');
    }
    
    public function authPass(Request $request){
        $user = User::find(Auth::user()->id);
            if(trim($request->newPass) == trim($request->confirmPass)){
                if(Hash::check($request->oldPass, $user->password)){
                    $user->password = Hash::make($request->newPass);
                    $user->save();
                    return redirect()->back()->with('success', 'Password changed successfully');
                }else{
                    return redirect()->back()->with('error', 'Old password Not Validate');
                }
            }else{
                return redirect()->back()->with('error', 'Password Not confirmed');
            }
    }















}
