@extends('layouts.app')

@section('main')
<div class="container">
        <div class="row my-5">
        <div class="col-md-3">
            @include('layouts.sidebar')
            </div>
            <div class="col-md-9">
            @include('layouts.message')
                <div class="card border-0 shadow">
                    <div class="card-header  text-white">
                        Change Password  
                    </div>
                    <div class="card-body pb-3">   
                    <form action="{{route('account.authPass')}}" method="post" >
                        @csrf 
                        <div class="mb-3">
                            <label for="name" class="form-label">Old Password</label>
                            <input type="text" class="form-control" name="oldPass" id="oldPass">
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">New password</label>
                            <input type="text" class="form-control" name="newPass" id="newPass">
                        </div> 
                        <div class="mb-3">
                            <label for="name" class="form-label">Confirm New Password</label>
                            <input type="text" class="form-control" name="confirmPass" id="confirmPass">
                        </div> 
                        <button class="btn btn-primary mt-2">Save</button>     
                    </form>    

                    </div>
                    
                </div>                
            </div>
        </div>       
    </div>


@endsection

