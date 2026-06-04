@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="w-full">
    <div class="bg-white shadow-sm rounded-lg border border-gray-100">
        <div class="text-center p-8 sm:p-12">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Welcome back, Admin!</h2>
            <p class="text-gray-500 mt-2">You are now logged into the secure administrative area.</p>
            
            {{-- Grid container replacing bootstrap layout columns --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                
                {{-- Card 1: Total Users --}}
                <div class="p-6 bg-gray-50 rounded-xl transition hover:bg-gray-100/70">
                    <h4 class="text-3xl font-extrabold text-gray-800 tracking-tight">1,240</h4>
                    <span class="block text-sm font-medium text-gray-500 mt-1">Total Users</span>
                </div>

                {{-- Card 2: Revenue --}}
                <div class="p-6 bg-gray-50 rounded-xl transition hover:bg-gray-100/70">
                    <h4 class="text-3xl font-extrabold text-gray-800 tracking-tight">$4,500</h4>
                    <span class="block text-sm font-medium text-gray-500 mt-1">Revenue</span>
                </div>

                {{-- Card 3: Pending Tasks --}}
                <div class="p-6 bg-gray-50 rounded-xl transition hover:bg-gray-100/70">
                    <h4 class="text-3xl font-extrabold text-gray-800 tracking-tight">12</h4>
                    <span class="block text-sm font-medium text-gray-500 mt-1">Pending Tasks</span>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection