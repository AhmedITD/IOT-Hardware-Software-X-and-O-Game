@extends('layout')
@section('title', 'login')
@section('content')
<div class="container">
  @if(session()->has('error'))
  <div class="alert alert-danger">{{session('error')}}</div>
  @endif
  <h1>Login</h1>
  <form method="POST" action="{{route('login.post')}}">
  @csrf
    <label for="username">Username</label>
    <input type="text" name="name" id="username" required>

    <label for="password">Password</label>
    <input type="password" name="password" id="password" required>

    <div calss="mt-5">
        @if($errors->any())
        <div class="clo-12">
          @foreach($errors->all() as $error)
          <div class="alert alert-danger">{{$error}}</div>
          @endforeach
        </div>    
        @endif
    </div>               
    <button type="submit">Login</button>
  </form>
  <a href="/register">register ?</a>
</div>
@endsection