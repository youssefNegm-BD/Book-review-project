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
                            Books
                        </div>
                    <div class="card-body pb-0">   
                        <div class="d-flex justify-content-between">
                        <a href="{{route('books.create')}}" class="btn btn-primary">Add Book</a> 
                            <form action="" method="get">
                                <div class="d-flex">
                                    <input type="text" class="form-control" value="{{Request::get('search')}}"  name="search" placeholder="search">
                                    <button type="submit" class="btn btn-primary ms-2">Search</button>
                                    <a href="{{route('books.index')}}" class="btn btn-secondary ms-2"> Clear</a>
                                </div>
                            </form>
                        </div>
                        <table class="table  table-striped mt-3">
                            <thead class="table-dark">
                                <tr>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Rating</th>
                                    <th>Status</th>
                                    <th width="150">Action</th>
                                </tr>
                                <tbody>
                                    
                                        @foreach($books as $book)
                                        @php 
                                            if($book->reviews_count > 0){
                                                $avgRate = $book->reviews_sum_rating / $book->reviews_count;
                                            }else{
                                                $avgRate = 0;
                                            }
                                            $avgPer = ($avgRate*100)/5;
                                        @endphp
                                        <tr>
                                            <td>{{$book->title}}</td>
                                            <td>{{$book->author}}</td>
                                            <td> {{number_format($avgRate,2)}}</td>
                                            
                                            <td>
                                                @if($book->status == 1)
                                                <span class="text-success"> Active</span>
                                                @else
                                                <span class="text-danger"> Block</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{route('books.edit', $book->id )}}" class="btn btn-primary btn-sm"><i class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                                <a href="{{ route('books.destroy', $book->id) }}" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                        
                                        
                                    
                                </tbody>
                            </thead>
                        </table>   
                        
                        {{$books->links()}}
                        
                </div>                
            </div>
        </div>                  
    </div>       
</div>


@endsection


