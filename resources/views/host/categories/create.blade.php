@extends('layouts.host')

@section('content')
<div class="main-container" style="padding: 20px; font-family: 'Inter', sans-serif; background: #fbfcfe;">

    <div style="margin-bottom: 25px;">
        <h1 style="font-size: 26px; font-weight: 800; color: #1e293b; margin: 0;">Create Guest Category</h1>
        <p style="color: #64748b; font-size: 14px;">Define a group of ceremonies (e.g., "Close Family" gets Haldi + Wedding)</p>
    </div>

    <div style="background: white; padding: 30px; border-radius: 16px; border: 1px solid #e2e8f0; max-width: 700px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
        
        <form action="{{ route('host.categories.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 25px;">
                <label style="display: block; font-size: 14px; font-weight: 700; color: #475569; margin-bottom: 8px;">
                    Category Name
                </label>
                <input type="text" name="category_name" required 
                    placeholder="e.g. VVIP, Bride Side, Friends"
                    style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 15px;">
                @error('category_name')
                    <span style="color: #ef4444; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display: block; font-size: 14px; font-weight: 700; color: #475569; margin-bottom: 12px;">
                    Select Ceremonies for this Category
                </label>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px; background: #f8fafc; padding: 20px; border-radius: 12px; border: 1px solid #f1f5f9;">
                    @forelse($ceramonies as $ceremony)
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 8px; background: white; border: 1px solid #e2e8f0; border-radius: 8px; transition: border-color 0.2s;">
                            <input type="checkbox" name="ceremony_ids[]" value="{{ $ceremony->id }}" 
                                style="width: 18px; height: 18px; accent-color: #4f46e5; cursor: pointer;">
                            <span style="font-size: 14px; color: #1e293b; font-weight: 500;">{{ $ceremony->ceramony_name }}</span>
                        </label>
                    @empty
                        <p style="color: #94a3b8; font-size: 13px; grid-column: 1 / -1; text-align: center;">
                            No ceremonies found. Please add ceremonies first.
                        </p>
                    @endforelse
                </div>
                @error('ceremony_ids')
                    <span style="color: #ef4444; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; gap: 12px; border-top: 1px solid #f1f5f9; pt-25px; margin-top: 10px; padding-top: 20px;">
                <button type="submit" 
                    style="background: #4f46e5; color: white; border: none; padding: 12px 30px; border-radius: 10px; font-weight: 700; cursor: pointer; font-size: 14px; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);">
                    Save Category
                </button>
                <a href="{{ route('host.categories.index') }}" 
                    style="background: #fff; color: #475569; padding: 12px 30px; border-radius: 10px; text-decoration: none; font-size: 14px; font-weight: 600; border: 1px solid #e2e8f0;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<style>
    label:hover {
        border-color: #4f46e5 !important;
    }
</style>
@endsection