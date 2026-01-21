@extends('layouts.app')
@section('content')
<main>
    <h1 class="text-center text-2xl font-semibold my-6">Welcome to CVCRAFT</h1>
    <div class="flex justify-center">
        <input
            type="email"
            name="email"
            id="email"
            placeholder="Enter your email"
            class="border border-gray-300 px-4 py-2 rounded-md w-72"
        >
    </div>
    <div class="flex justify-center mt-6">
        <input
            type="password"
            name="password"
            id="password"
            placeholder="Enter your Password"
            class="border border-gray-300 px-4 py-2 rounded-md w-72"
        >
    </div>
    <p class="text-blue-500 text-xs underline text-center mt-6">Forgot your password?</p>
        <p class="text-xs text-center mt-2">Already have an account? 
            <b class="text-blue-500 text-xs underline ">Log in</b>
    </p>
    <div class="flex justify-center mt-6 gap-x-6">
        <button class="border w-32 border-green-500 rounded-2xl py-1 px-3">
            Back
        </button>
        <button class="bg-green-500 w-32 text-white rounded-2xl py-1 px-3">
            Login
        </button>

    </div>

    
</main>
@endsection