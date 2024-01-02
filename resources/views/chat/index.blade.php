<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Chat</title>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <div class="chat">
    <div class="top">
        <img src="" alt="Avatar">
        <div>
            <p>Rupan dangol</p>
            <small>Online</small>
        </div>
    </div>
    <div class="messages">
       @include('chat.receive',['message'=>'Hello world']) 
    </div>
    <div class="bottom">
        <form>
            <input type="text" id="message" name="message" placeholder="Enter message..." autocomplete="off">
            <button type="submit"></button>
        </form>
    </div>
</div>
</body>
<script>
    const pusher= new Pusher('{{config('broadcasting.connection.pusher.key')}}',{cluster:'ap2'});
    const channel= pusher.subscribe('public');

    //Receive message
    channel.bind('chat',function(data){
        $.post("/chat/receive",{
            _token:'{{csrf_token()}}',
            message: data.message,
        }).done(function(res){
            $(".messages>.message").last().after(res);
            $(document).scrollTop($(document).height());
        })
    });

    //Broadcast messages
    $("form").submit(function(event){
        event.preventDefault();
        $.ajax({
            url:"/chat/broadcast",
            method:'POST',
            headers:{
                'X-Socket-Id': pusher.connection.socket_id
            },
            data:{
                _token:'{{csrf_token()}}',
                message:$("form #message").val(),
            }
        }).done(function(res){
            $(".messages>.message").last().after(res);
            $("form #message").val('');
            $(document).scrollTop($(document).height());
        });
    });

</script>
</html>