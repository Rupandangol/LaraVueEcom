@extends('header.header')
<body>
    <div class="container mt-5">
    <h1>User details</h1>
    @include('Alerts.sfAlert')

    <table class="table table-bordered" >
        <tr>
            <th>id</th>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <tbody>
            @foreach($users as $item)
            <tr>
                <th>{{$item->id}}</th>
                <th>{{$item->name}}</th>
                <th>{{$item->email}}</th>
                <th>
                    <a href="{{route('user-edit',$item->id)}}" class="btn btn-warning" type="button">Edit</a>
                        <form action="{{route('user-delete',$item->id)}}" method="post">
                            @method('DELETE')
                            @csrf
                            <button class="btn btn-danger" type="submit">Delete</button>
                        </form>
                </th>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

</body>
</html>