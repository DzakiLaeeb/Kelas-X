<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = DB::table('banners')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'link' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'title' => $request->title,
            'link' => $request->link,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'order' => $request->order ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/banners', $imageName);
            $data['image_url'] = 'banners/' . $imageName;
        }

        DB::table('banners')->insert($data);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner berhasil ditambahkan');
    }

    public function edit($id)
    {
        $banner = DB::table('banners')->where('id', $id)->first();
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'link' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'title' => $request->title,
            'link' => $request->link,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'order' => $request->order ?? 0,
            'updated_at' => now(),
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            $oldBanner = DB::table('banners')->where('id', $id)->first();
            if ($oldBanner && $oldBanner->image_url) {
                Storage::delete('public/' . $oldBanner->image_url);
            }

            // Upload new image
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/banners', $imageName);
            $data['image_url'] = 'banners/' . $imageName;
        }

        DB::table('banners')->where('id', $id)->update($data);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner berhasil diperbarui');
    }

    public function destroy($id)
    {
        // Delete image
        $banner = DB::table('banners')->where('id', $id)->first();
        if ($banner && $banner->image_url) {
            Storage::delete('public/' . $banner->image_url);
        }

        // Delete banner
        DB::table('banners')->where('id', $id)->delete();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner berhasil dihapus');
    }
}
