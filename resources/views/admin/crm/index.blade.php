@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">CRM Dashboard</h1>
        <p class="text-gray-500 text-sm mt-1">Manage and track host registration attempts</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <h2 class="text-lg font-semibold text-gray-800">Registration Attempts</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-6 py-4 font-semibold text-gray-600 text-sm">Date</th>
                    <th class="px-6 py-4 font-semibold text-gray-600 text-sm">Name</th>
                    <th class="px-6 py-4 font-semibold text-gray-600 text-sm">Email</th>
                    <th class="px-6 py-4 font-semibold text-gray-600 text-sm">Mobile</th>
                    <th class="px-6 py-4 font-semibold text-gray-600 text-sm text-center">Attempts</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($crms as $crm)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            {{ $crm->created_at->format('M d, Y h:i A') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-800">{{ $crm->name }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $crm->email }}
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-700">
                            {{ $crm->mobile }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-xs font-medium {{ $crm->attempts_count > 1 ? 'bg-amber-100 text-amber-800' : 'bg-green-100 text-green-800' }}">
                                {{ $crm->attempts_count }} {{ Str::plural('Attempt', $crm->attempts_count) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <i class="bi bi-inbox text-4xl mb-3 block text-gray-300"></i>
                            <p>No registration attempts found yet.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
