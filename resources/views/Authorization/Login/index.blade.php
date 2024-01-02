<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <style>
        
        body {font-family: Arial, Helvetica, sans-serif;}
        form {border: 3px solid #f1f1f1;}

        input[type=text], input[type=password],input[type=email] {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        box-sizing: border-box;
        }

        button {
        background-color: #04AA6D;
        color: white;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        cursor: pointer;
        width: 100%;
        }

        button:hover {
        opacity: 0.8;
        }

        .cancelbtn {
        width: auto;
        padding: 10px 18px;
        background-color: #f44336;
        }

        .imgcontainer {
        text-align: center;
        margin: 24px 0 12px 0;
        }

        img.avatar {
        width: 40%;
        border-radius: 50%;
        }

        .container {
        padding: 16px;
        }

        span.psw {
        float: right;
        padding-top: 16px;
        }
    </style>
</head>

<body>
    <h1>Login Page</h1>

    @if(session('fail'))
      <code style="color: red"><b>Email or password incorrect</b></code>
    @endif
    <form action="{{route('login')}}" method="post">
        @csrf
        <div class="container">
          <label for="email"><b>Email</b></label>
          <input type="email" value="{{old('email')}}" placeholder="Enter Email" name="email" >
          @if($errors->has('email'))
          <code>{{$errors->first('email')}}</code><br>
          @endif
          <label for="password"><b>Password</b></label>
          <input type="password" placeholder="Enter Password" name="password" required>
          @if($errors->has('password'))
          <code>{{$errors->first('password')}}</code>
          @endif
          <button type="submit">Login</button>
          {{-- <label>
            <input type="checkbox" checked="checked" name="remember"> Remember me
          </label> --}}
        </div>
      
        {{-- <div class="container" style="background-color:#f1f1f1">
          <button type="button" class="cancelbtn">Cancel</button>
          <span class="psw">Forgot <a href="#">password?</a></span>
        </div> --}}
      </form>
</body>
</html>