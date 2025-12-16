<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use App\Models\Property;   // ✅ create this model/table OR change to your existing one

class FrontendController extends Controller
{
    public function index()
    {
        return view('frontend.index');
    }

    public function about()
    {
        return view('frontend.about');
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    // ✅ Properties listing page
    // public function properties(Request $request)
    // {
    //     $q = Property::query()->where('status', 'published'); // adjust field names

    //     // Optional simple filters
    //     if ($request->filled('type')) {
    //         $q->where('type', $request->type);
    //     }
    //     if ($request->filled('district')) {
    //         $q->where('district', $request->district);
    //     }

    //     $properties = $q->latest()->paginate(12)->withQueryString();

    //     return view('frontend.properties.index', compact('properties'));
    // }

    // ✅ Property details page
    // public function propertyShow($slug)
    // {
    //     $property = Property::where('slug', $slug)
    //         ->where('status', 'published')
    //         ->firstOrFail();

    //     return view('frontend.properties.show', compact('property'));
    // }

    // ✅ Contact form submit
    public function contactSubmit(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:120',
            'email'   => 'required|email|max:160',
            'phone'   => 'nullable|string|max:30',
            'message' => 'required|string|max:2000',
        ]);

        // Option A: just flash success (no email). Replace with Mail later.
        // Option B: store in DB (ContactMessage model) if you want.

        return back()->with('success', 'Thanks! We received your message. We will contact you soon.');
    }
}
