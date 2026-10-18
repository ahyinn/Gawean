<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    // Beranda: navigasi kategori (statis) + hasil pencarian/filter
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();

        $query = Service::with(['user', 'category']);

        // Cari berdasarkan judul jasa, ATAU nama freelancer, ATAU nama kategori
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhereHas('user', function ($u) use ($keyword) {
                      $u->where('name', 'like', "%{$keyword}%");
                  })
                  ->orWhereHas('category', function ($c) use ($keyword) {
                      $c->where('name', 'like', "%{$keyword}%");
                  });
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $services = $query->latest()->paginate(12)->withQueryString();

        $topSellers = User::topSellers(5);

        return view('services.index', compact('categories', 'services', 'topSellers'));
    }

    // Daftar jasa milik saya (freelancer yang sedang login)
    public function mine()
    {
        $services = auth()->user()->services()->with('category')->latest()->get();
        return view('services.mine', compact('services'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('services.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validateService($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        auth()->user()->services()->create($data);

        return redirect()->route('services.mine')->with('success', 'Jasa berhasil ditambahkan.');
    }

    public function edit(Service $service)
    {
        abort_unless($service->user_id === auth()->id(), 403);

        $categories = Category::orderBy('name')->get();
        return view('services.edit', compact('service', 'categories'));
    }

    public function update(Request $request, Service $service)
    {
        abort_unless($service->user_id === auth()->id(), 403);

        $data = $this->validateService($request);

        if ($request->hasFile('image')) {
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $service->update($data);

        return redirect()->route('services.mine')->with('success', 'Jasa berhasil diperbarui.');
    }

    public function destroy(Service $service)
    {
        abort_unless($service->user_id === auth()->id(), 403);

        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }

        $service->delete();

        return redirect()->route('services.mine')->with('success', 'Jasa berhasil dihapus.');
    }

    public function show(Service $service)
    {
        $service->load(['user', 'category']);
        return view('services.show', compact('service'));
    }

    private function validateService(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'integer', 'min:0'],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);
    }
}