@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manage Hosts</h1>
            <p class="text-sm text-gray-500 mt-1">Overview and management of your registered wedding platform hosts.</p>
        </div>
        <div>
            <a href="{{ route('admin.host.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 text-white font-semibold text-sm rounded-xl shadow-xs hover:bg-blue-700 transition-colors cursor-pointer">
                <i class="bi bi-plus-lg mr-2"></i> Add New Host
            </a>
        </div>
    </div>

    <!-- Table Card Container -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead class="bg-gray-50/70 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500">Host Info</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500">Package</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500">Created By</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($hosts as $host)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <!-- Host Info -->
                        <td class="px-6 py-4.5">
                            <div class="flex flex-col">
                                <span class="font-semibold text-gray-800">{{ $host->name }}</span>
                                <span class="text-sm text-gray-400 mt-0.5">{{ $host->email }}</span>
                            </div>
                        </td>
                        
                        <!-- Package Badge -->
                        <td class="px-6 py-4.5">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-cyan-50 text-cyan-700 border border-cyan-100">
                                <i class="bi bi-box-seam-fill mr-1.5 text-[10px]"></i>
                                {{ $host->package->package_name ?? 'No Package' }}
                            </span>
                        </td>
                        
                        <!-- Creator Info -->
                        <td class="px-6 py-4.5">
                            <span class="text-sm font-medium text-gray-600">
                                {{ $host->creator->name ?? 'System' }}
                            </span>
                        </td>
                        
                        <!-- Status Badge -->
                        <td class="px-6 py-4.5">
                            @if($host->status == 'active')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                                    {{ ucfirst($host->status) }}
                                </span>
                            @endif
                        </td>
                        
                        <!-- Actions Row -->
                        <td class="px-6 py-4.5 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.host.edit', $host->id) }}" class="inline-flex items-center px-3 py-1.5 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-semibold rounded-lg hover:bg-amber-600 hover:text-white hover:border-amber-600 transition-colors cursor-pointer">
                                    <i class="bi bi-pencil-square mr-1"></i> Edit
                                </a>
                                
                                <form action="{{ route('admin.host.destroy', $host->id) }}" method="POST" class="inline">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-semibold rounded-lg hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-colors cursor-pointer" onclick="return confirm('Are you sure you want to delete this host?')">
                                        <i class="bi bi-trash3-fill mr-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection