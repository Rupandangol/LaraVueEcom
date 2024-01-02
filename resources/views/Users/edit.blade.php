@extends('header.header')
<body>
    <div class="container mt-5">
    <h1>User Edit</h1>
    @foreach($errors->all() as $error)
        <div class="alert alert-danger">
                {{$error}}
        </div>
    @endforeach


    <form action="{{route('user-update',$user->id)}}" method="POST">
        @method('PUT')
        @csrf
        <div class='mb-3'>
            <label class="form-label" for="name">Name</label>
            <input class="form-control" value="{{$user->name??old('name')}}"  name="name" type="text">
        </div>

        <div class='mb-3'>
            <label class="form-label" for="email">Email</label>
            <input class="form-control" value="{{$user->email?? old('email')}}" name="email" type="email">
        </div>
        <button class="btn btn-success" type="submit">Update</button>
        <a class="btn btn-info" href="{{route('users')}}">Back</a>
    </form>
</div>
</body>
</html>