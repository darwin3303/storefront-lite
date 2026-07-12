<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
</head>

<body>
    <center>
        <h3>Customer name : {{ $data->name }}</h3>
        <h3>Customer address : {{ $data->rec_address }}</h3>
        <h3>phone : {{ $data->phone }}</h3>
        <h2>Product title : {{ $data->product->title }}</h2>
        <h2>Price : {{ $data->product->price }}</h2>
        <img src="products/{{$data->product->image}}" style height="250" width="">
        {{-- <img src="{{ asset('product/' . $data->product->image) }}"> --}}
    </center>

</body>

</html>
