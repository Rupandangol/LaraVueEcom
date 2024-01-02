<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
</head>
<body>
    @if(session('test'))
    <h1>{{session('test')}}</h1>
    @endif
    <h1>My Test Blade</h1>
    <h2>{{Auth::guard('web')->user()->email}}</h2>

    <a href="{{route('logout')}}">Logout</a>
    <table id="table">
        <thead>
            <tr>
            <th>name</th>
            <th>age</th>
            <th>address</th>
            <th>phone</th>
        </tr>
        </thead>
        <tbody>
            @foreach($testData as $item)
            <tr>
                <td>{{$item->name}}</td>
                <td>{{$item->age}}</td>
                <td>{{$item->address}}</td>
                <td>{{$item->phone }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>