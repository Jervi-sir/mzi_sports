<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

    <form action="{{ route('tag.save') }}" method="POST">
        @csrf
        <input type="text" name="tag" placeholder="type tag">
        <button type="submit">save</button>
    </form>

    @foreach ($tags as $tag)
        <span>{{$tag->id}}, {{$tag->name}}</span>
        <br>
    @endforeach

</body>
</html>
