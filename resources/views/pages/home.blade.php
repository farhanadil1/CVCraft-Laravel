@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 mx-20">
        <div class="text-left items-center">
            <h1 class="relative font-semibold group text-4xl mb-2 inline-block cursor-pointer">
                CVCRAFT
                <span class="absolute left-0 -bottom-1.5 h-0.5 w-0 bg-black transition-all ease-out duration-300 group-hover:w-full"></span>
            </h1>
            <p class="text-gray-700 mt-4">Welcome to my Laravel frontend. This is the sample page for my upcpmming 
                laravel project just wanted it to be fully functional and dynamic as well as 
                responsive
            </p>
        </div>
        <div class="scroll-auto">
            <img src="./gem.png" alt="gem" class="h-96 w-96">

        </div>
    </div>
       

@endsection
