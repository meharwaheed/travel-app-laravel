<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>One Time Login Link</title>
    <style>
        .login-link{
            color: white;
            background: orange;
            text-decoration: none;
            padding: 10px 20px 10px 20px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <p>One time login link has generated successfully!<p>
    <a class="login-link" href="{{env('FRONT_END_APP_LINK')}}/#/auth/validate-token/{{$data->plainTextToken}}">
    Click Here to Login
    </a>
    <p> The Link will be expired after {{env('LINK_EXPIRE_AFTER')}} minutes</p>

</body>
</html>
