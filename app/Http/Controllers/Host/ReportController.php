<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\Ceramonies;
use App\Models\GuestList;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Increase memory to handle larger paginated sets
        ini_set('memory_limit', '512M');
        
        $hostId = Auth::id();

        // Efficient counting (doesn't load objects into memory)
        $stats = [
            'total'    => GuestList::where('host_id', $hostId)->count(),
            'accepted' => GuestList::where('host_id', $hostId)->where('status', 'accepted')->count(),
            'rejected' => GuestList::where('host_id', $hostId)->where('status', 'rejected')->count(),
            'pending'  => GuestList::where('host_id', $hostId)->where('status', 'pending')->count(),
            'ceremonies' => Ceramonies::where('host_id', $hostId)->count(),
        ];

        // Ceremony-wise guest count
        $ceremony_stats = Ceramonies::where('host_id', $hostId)->get()->map(function($ceremony) use ($hostId) {
            $ceremony->guest_count = GuestList::where('host_id', $hostId)
                ->where('assigned_ceremonies', 'LIKE', '%' . $ceremony->ceramony_name . '%')
                ->count();
            return $ceremony;
        });

        // Eager load 'ceramony' to avoid N+1 memory issues
        $query = GuestList::with('ceramony')->where('host_id', $hostId);

        // Search Filter
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('guest_name', 'like', '%' . $request->search . '%')
                  ->orWhere('guest_number', 'like', '%' . $request->search . '%');
            });
        }

        // Status & Via Filters
        if ($request->filled('via')) {
            $query->where('via', $request->via);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Fixed Pagination Logic
        $perPage = $request->input('per_page', 15);
        if ($perPage > 100) $perPage = 100;

        $guests = $query->latest()->paginate($perPage)->withQueryString();

        return view('host.reports.index', compact('stats', 'guests', 'ceremony_stats'));
    }
}