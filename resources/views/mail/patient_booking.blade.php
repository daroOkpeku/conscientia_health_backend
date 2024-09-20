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
  <p>firstname: {{ $data['firstname'] }}</p>
  <p>lastname: {{ $data['lastname'] }}</p>
  <p>state: {{ $data['state'] }}</p>
  <p>doctor: {{ $data['doctor'] }}</p>
  <p>email: {{ $data['email'] }}</p>
  <p>phone: {{ $data['phone'] }}</p>
  <p>visit type: {{ $data['visit_type'] }}</p>
  <p> {{ $data['comment'] }} </p>
    </div>
</body>
</html>
