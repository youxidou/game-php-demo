<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <script src="//cdn.bootcss.com/jquery/3.2.1/jquery.js"></script>
    <script src="//gg.yxd17.com/js/sdk.eabdcb45.js"></script>

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > button {
            color: #636b6f;
            padding: 0 5px;
            margin: 0 5px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
            cursor: pointer;
        }

        .m-b-md {
            margin-bottom: 30px;
        }

        .chong {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    @if (Route::has('login'))
        <div class="top-right links">
            @if (Auth::check())
                <a href="{{ url('/home') }}">Home</a>
            @else
                <a href="{{ url('/login') }}">Login</a>
                <a href="{{ url('/register') }}">Register</a>
            @endif
        </div>
    @endif

    <div class="content">
        <div class="title">
            <img src="{{$avatar}}" height="100"/>
        </div>
        <div class="title" style="font-size: 30px">{{$name}}</div>
        <div class="title  m-b-md" style="font-size: 20px">账户余额: {{$money}}元</div>
        <div class="chong"> 充值以下金额</div>
        <div class="links">
            <button money-id="1">0.01元</button>
            <button money-id="2">0.1元</button>
            <button money-id="3">1元</button>
            <button money-id="4">10元</button>
            <button money-id="5">100元</button>
        </div>
    </div>
</div>

<script type="application/javascript">
    $('.links button').click(function () {
        var money_id = $(this).attr('money-id');
        $.get('/order?money_id=' + money_id, function (data) {
//            alert(JSON.stringify(data));
            YXD.pay(data, function (result) {
                if (result.code == 0) {
                    window.location.reload();
                }
            });
        });
    });
</script>
</body>
</html>
