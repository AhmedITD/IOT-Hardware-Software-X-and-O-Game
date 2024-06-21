@extends('layout')
@section('title', 'register')
@section('content')
<div class="container">
    @if(session()->has('error'))
    <div class="alert alert-danger">{{ession('error')}}</div>
    @endif
    <h1>Registration</h1>
    <form method="post" action="{{route('register.post')}}">
    @csrf
    <label for="username">Username</label>
    <input type="text" name="name" id="username" value="" >
      
    <label for="email">Email</label>
    <input type="email" name="email" id="email"  value="" >
      
    <label for="password">Password</label>
    <input type="password" name="password" id="password"  value="" >
      
    <label for="password-confirm">Confirm Password</label>
    <input id="password-confirm" type="password" name="password_confirmation" autocomplete="new-password">
    <div calss="mt-5">
        @if($errors->any())
        <div class="clo-12">
            @foreach($errors->all() as $error)
            <div class="alert alert-danger">{{$error}}</div>
            @endforeach
        </div>    
        @endif
    </div>
    <button type="submit">Rigester</button>
    </form>
    <a href="/">login ?</a>
</div>
@endsection