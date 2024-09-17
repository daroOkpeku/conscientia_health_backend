<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div>
        <p>Hello {{ $data['name'] }} welcome to conscientia health</p>
        <p> please click to verify your email <a href="http://localhost:3000/verify_email/?email={{$data['email']}}&firstname={{$data['firstname']}}">click here</a> </p>
    </div>
</body>
</html>