
@extends('layouts.app')

@section('title', 'Login | Dish Check')

@section('content')

<section class="container login-container">

    <div class="form-card login-card">

        <h1>Staff & Admin Login</h1>

        <p>Log in to access the Dish Check management system.</p>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf

            <label for="email">Email Address</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="Enter your email"
                required
                autofocus
            >

            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Enter your password"
                required
            >

            <button type="submit" class="btn">
                Login
            </button>
        </form>

        <p class="login-note">
            Only authorised staff and administrators can log in.
        </p>

        <a href="{{ route('home') }}">Back to Homepage</a>

    </div>

</section>

@endsection
