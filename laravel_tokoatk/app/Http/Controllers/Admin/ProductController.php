<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = DB::table('products')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.name as category_name')
            ->orderBy('products.created_at', 'desc')
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = DB::table('categories')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validationRules = [
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image_type' => 'required|in:file,url',
        ];

        // Add conditional validation rules based on image_type
        if ($request->image_type === 'file') {
            $validationRules['gambar'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
        } else {
            $validationRules['gambar_url'] = 'nullable|url';
        }

        $request->validate($validationRules);

        $data = $request->except(['gambar', 'gambar_url', 'description', 'image_type']);

        // If the database has a 'deskripsi' field instead of 'description', map it
        if ($request->has('description')) {
            $data['deskripsi'] = $request->input('description');
        }

        // Handle image based on image_type
        if ($request->image_type === 'file' && $request->hasFile('gambar')) {
            // Handle file upload
            $image = $request->file('gambar');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/products', $imageName);
            $data['gambar'] = 'products/' . $imageName;
        } elseif ($request->image_type === 'url' && $request->filled('gambar_url')) {
            // Handle image URL - make sure it's a valid URL
            $imageUrl = $request->gambar_url;

            // Validate URL format (must be http or https, not blob)
            if (!filter_var($imageUrl, FILTER_VALIDATE_URL) ||
                !Str::startsWith($imageUrl, ['http://', 'https://']) ||
                Str::startsWith($imageUrl, 'blob:')) {
                return redirect()->back()->withInput()->withErrors(['gambar_url' => 'Invalid image URL format. URL must start with http:// or https:// and cannot be a blob URL.']);
            }

            // Log the URL for debugging
            \Log::info('Image URL validation passed: ' . $imageUrl);

            // Add debug info
            \Log::info('Saving image URL: ' . $imageUrl);
            $data['gambar'] = $imageUrl;
        }

        DB::table('products')->insert($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $product = DB::table('products')->where('id', $id)->first();
        $categories = DB::table('categories')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $validationRules = [
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image_type' => 'required|in:file,url',
        ];

        // Add conditional validation rules based on image_type
        if ($request->image_type === 'file') {
            $validationRules['gambar'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
        } else {
            $validationRules['gambar_url'] = 'nullable|url';
        }

        $request->validate($validationRules);

        $data = $request->except(['_token', '_method', 'gambar', 'gambar_url', 'description', 'image_type']);

        // If the database has a 'deskripsi' field instead of 'description', map it
        if ($request->has('description')) {
            $data['deskripsi'] = $request->input('description');
        }

        // Get the old product data
        $oldProduct = DB::table('products')->where('id', $id)->first();

        // Handle image based on image_type
        if ($request->image_type === 'file') {
            if ($request->hasFile('gambar')) {
                // Delete old image if it's a local file
                if ($oldProduct && $oldProduct->gambar && !Str::startsWith($oldProduct->gambar, ['http://', 'https://'])) {
                    Storage::delete('public/' . $oldProduct->gambar);
                }

                // Upload new image
                $image = $request->file('gambar');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/products', $imageName);
                $data['gambar'] = 'products/' . $imageName;
            }
        } elseif ($request->image_type === 'url' && $request->filled('gambar_url')) {
            // If switching from file to URL or updating URL, delete old file if exists
            if ($oldProduct && $oldProduct->gambar && !Str::startsWith($oldProduct->gambar, ['http://', 'https://'])) {
                Storage::delete('public/' . $oldProduct->gambar);
            }

            // Update with new URL
            $imageUrl = $request->gambar_url;

            // Validate URL format (must be http or https, not blob)
            if (!filter_var($imageUrl, FILTER_VALIDATE_URL) ||
                !Str::startsWith($imageUrl, ['http://', 'https://']) ||
                Str::startsWith($imageUrl, 'blob:')) {
                return redirect()->back()->withInput()->withErrors(['gambar_url' => 'Invalid image URL format. URL must start with http:// or https:// and cannot be a blob URL.']);
            }

            // Log the URL for debugging
            \Log::info('Image URL validation passed: ' . $imageUrl);

            // Add debug info
            \Log::info('Updating image URL: ' . $imageUrl);
            $data['gambar'] = $imageUrl;
        }

        DB::table('products')->where('id', $id)->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy($id)
    {
        // Delete image
        $product = DB::table('products')->where('id', $id)->first();
        if ($product && $product->gambar) {
            Storage::delete('public/' . $product->gambar);
        }

        // Delete product
        DB::table('products')->where('id', $id)->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus');
    }
}
