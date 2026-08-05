@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-6 w-full antialiased">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Channel Add-ons</h2>
            <p class="text-xs text-gray-500 mt-0.5">Manage WhatsApp, SMS & Email credit packs that hosts can purchase.</p>
        </div>
        <a href="{{ route('admin.addons.create') }}"
            class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 border border-transparent rounded-md text-sm font-semibold text-white shadow-sm transition-colors duration-150">
            + Create Add-on
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 text-sm rounded-md flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Cards grouped by type -->
    @php
        $grouped = $addons->groupBy('type');
        $typeConfig = [
            'whatsapp' => ['label' => 'WhatsApp', 'icon' => 'bi-whatsapp', 'color' => 'green'],
            'sms'      => ['label' => 'SMS',       'icon' => 'bi-chat-dots-fill', 'color' => 'yellow'],
            'email'    => ['label' => 'Email',     'icon' => 'bi-envelope-fill', 'color' => 'blue'],
        ];
    @endphp

    @foreach(['whatsapp','sms','email'] as $type)
        @php $cfg = $typeConfig[$type]; @endphp
        <div class="mb-8">
            <h3 class="text-sm font-bold text-gray-600 uppercase tracking-wider mb-3 flex items-center gap-2">
                <i class="bi {{ $cfg['icon'] }} text-{{ $cfg['color'] }}-500"></i>
                {{ $cfg['label'] }} Add-ons
            </h3>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <table class="w-full table-fixed divide-y divide-gray-100 text-sm text-left">
                    <thead class="bg-gray-50 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <tr>
                            <th class="px-5 py-3 w-[35%]">Name</th>
                            <th class="px-5 py-3 w-[15%]">Credits</th>
                            <th class="px-5 py-3 w-[15%]">Price (₹)</th>
                            <th class="px-5 py-3 w-[15%]">Status</th>
                            <th class="px-5 py-3 w-[20%] text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($grouped->get($type, collect()) as $addon)
                            <tr class="hover:bg-gray-50/60 transition-colors">
                                <td class="px-5 py-3 font-medium text-gray-800">{{ $addon->name }}</td>
                                <td class="px-5 py-3 text-gray-600 font-bold">+{{ number_format($addon->count) }}</td>
                                <td class="px-5 py-3 text-gray-800 font-bold">₹{{ number_format($addon->price) }}</td>
                                <td class="px-5 py-3">
                                    @if($addon->is_active)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-green-50 text-green-700 border border-green-200">Active</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-500 border border-gray-200">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-right space-x-1 whitespace-nowrap">
                                    <a href="{{ route('admin.addons.edit', $addon->id) }}"
                                        class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-semibold rounded text-gray-700 bg-white hover:bg-gray-50 shadow-sm transition-colors">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.addons.destroy', $addon->id) }}" method="POST" class="inline-block">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 border border-red-200 text-xs font-semibold rounded text-red-700 bg-white hover:bg-red-50 shadow-sm transition-colors"
                                            onclick="return confirm('Delete this add-on?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-6 text-center text-sm text-gray-400 italic">
                                    No {{ $cfg['label'] }} add-ons yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

</div>
@endsection
