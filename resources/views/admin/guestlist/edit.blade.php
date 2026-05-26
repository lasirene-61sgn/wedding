@extends('layouts.admin')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <div class="mb-6">
        <a href="{{ route('admin.guestlist.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">← Cancel Configuration</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Modify Guest Information</h1>
    </div>

    <form action="{{ route('admin.guestlist.update', $guest->id) }}" method="POST" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 max-w-4xl space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Core Form Parameters -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Guest Full Name</label>
                <input type="text" name="guest_name" value="{{ old('guest_name', $guest->guest_name) }}" required class="w-full border border-gray-200 rounded-lg p-2 text-sm focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Primary Mobile Number</label>
                <input type="text" name="guest_number" value="{{ old('guest_number', $guest->guest_number) }}" required class="w-full border border-gray-200 rounded-lg p-2 text-sm focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Coordinates</label>
                <input type="email" name="guest_email" value="{{ old('guest_email', $guest->guest_email) }}" class="w-full border border-gray-200 rounded-lg p-2 text-sm focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Target Ceremony Alignment</label>
                <select name="ceramony_id" class="w-full border border-gray-200 rounded-lg p-2 text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="">-- No Direct Event Assignment --</option>
                    @foreach($ceramonies as $c)
                        <option value="{{ $c->id }}" {{ old('ceramony_id', $guest->ceramony_id) == $c->id ? 'selected' : '' }}>
                            {{ $c->ceramony_name }} ({{ $c->ceramony_date }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="border-t pt-4 flex justify-end gap-2">
            <a href="{{ route('admin.guestlist.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium text-sm py-2 px-4 rounded-lg transition-colors">
                Cancel
            </a>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-5 rounded-lg transition-colors shadow-sm">
                Save Target Updates
            </button>
        </div>
    </form>
</div>
@endsection