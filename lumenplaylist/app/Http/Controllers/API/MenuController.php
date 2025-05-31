<?php

namespace App\Http\Controllers\API;

use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;

class MenuController extends BaseController
{
    /**
     * Display a listing of all menu items with their categories.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $menus = Menu::with('kategori')->get();
            
            // Transform image URLs
            $menus->transform(function ($menu) {
                if ($menu->gambar) {
                    $menu->gambar_url = url($menu->gambar);
                }
                return $menu;
            });

            return $this->successResponse($menus, 'Daftar menu berhasil diambil');
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Gagal mengambil daftar menu: ' . $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Store a newly created menu item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'menu' => 'required|string|max:255',
            'id_kategori' => 'required|integer|exists:categories,id_kategori',
            'harga' => 'required|numeric|min:0',
            'gambar' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'tersedia' => 'boolean'
        ], [
            'menu.required' => 'Nama menu harus diisi',
            'id_kategori.required' => 'Kategori harus diisi',
            'id_kategori.exists' => 'Kategori yang dipilih tidak valid',
            'harga.required' => 'Harga harus diisi',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga.min' => 'Harga tidak boleh negatif',
            'gambar.required' => 'Nama file gambar harus diisi'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                $validator->errors(),
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            // Prepare data for database
            $data = $request->only(['menu', 'id_kategori', 'harga', 'gambar', 'deskripsi', 'tersedia']);
            
            // Add 'uploads/' prefix to image name if not already present
            if (!str_starts_with($data['gambar'], 'uploads/')) {
                $data['gambar'] = 'uploads/' . $data['gambar'];
            }

            // Verify category exists
            $category = Category::find($data['id_kategori']);
            if (!$category) {
                return $this->errorResponse(
                    'Kategori tidak ditemukan',
                    Response::HTTP_NOT_FOUND
                );
            }

            // Create menu item
            $menu = Menu::create($data);
            
            // Load the category relationship
            $menu->load('kategori');
            
            // Add image URL to response
            $menu->gambar_url = url($menu->gambar);

            return $this->successResponse(
                $menu,
                'Menu berhasil ditambahkan',
                Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Gagal menambahkan menu: ' . $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Display the specified menu item.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $menu = Menu::with('kategori')->find($id);

            if (!$menu) {
                return $this->errorResponse(
                    'Menu tidak ditemukan',
                    Response::HTTP_NOT_FOUND
                );
            }

            // Add image URL to response
            if ($menu->gambar) {
                $menu->gambar_url = url($menu->gambar);
            }

            return $this->successResponse($menu, 'Detail menu berhasil diambil');
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Gagal mengambil detail menu: ' . $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Update the specified menu item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'menu' => 'sometimes|required|string|max:255',
            'id_kategori' => 'sometimes|required|integer|exists:categories,id_kategori',
            'harga' => 'sometimes|required|numeric|min:0',
            'gambar' => 'sometimes|required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'tersedia' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $menu = Menu::find($id);

        if (!$menu) {
            return $this->errorResponse(
                'Menu tidak ditemukan',
                Response::HTTP_NOT_FOUND
            );
        }

        try {
            // Prepare the data for database
            $data = $request->only(['menu', 'id_kategori', 'harga', 'gambar', 'deskripsi', 'tersedia']);
            
            // Add 'uploads/' prefix to image name if provided and not already present
            if (isset($data['gambar']) && !str_starts_with($data['gambar'], 'uploads/')) {
                $data['gambar'] = 'uploads/' . $data['gambar'];
            }

            // Update the menu
            $menu->update($data);
            
            // Load the category relationship
            $menu->load('kategori');

            // Add image URL to response
            if ($menu->gambar) {
                $menu->gambar_url = url($menu->gambar);
            }

            return $this->successResponse($menu, 'Menu berhasil diperbarui');
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Gagal memperbarui menu: ' . $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Remove the specified menu item.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $menu = Menu::find($id);
        
        if (!$menu) {
            return $this->errorResponse(
                'Menu tidak ditemukan',
                Response::HTTP_NOT_FOUND
            );
        }

        $menu->delete();

        return $this->successResponse(null, 'Menu berhasil dihapus');
    }

    /**
     * Display menu items for a specific category.
     *
     * @param  int  $categoryId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByCategory($categoryId)
    {
        $menus = Menu::with('kategori')
            ->where('id_kategori', $categoryId)
            ->get();

        return $this->successResponse(
            $menus,
            'Daftar menu berdasarkan kategori berhasil diambil'
        );
    }

    /**
     * Verify menu image existence.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyImage($id)
    {
        try {
            $menu = Menu::find($id);

            if (!$menu) {
                return $this->errorResponse(
                    'Menu tidak ditemukan',
                    Response::HTTP_NOT_FOUND
                );
            }

            if (!$menu->gambar) {
                return $this->errorResponse(
                    'Menu tidak memiliki gambar',
                    Response::HTTP_NOT_FOUND
                );
            }

            $imagePath = public_path($menu->gambar);
            $exists = file_exists($imagePath);

            return $this->successResponse([
                'exists' => $exists,
                'image_url' => $exists ? url($menu->gambar) : null,
                'image_path' => $menu->gambar
            ], $exists ? 'Gambar ditemukan' : 'Gambar tidak ditemukan');

        } catch (\Exception $e) {
            return $this->errorResponse(
                'Gagal memverifikasi gambar: ' . $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Search for menu items based on various criteria.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        try {
            $query = Menu::query()->with('kategori');

            // Search by menu name
            if ($request->has('menu')) {
                $searchTerm = '%' . $request->input('menu') . '%';
                $query->where('menu', 'LIKE', $searchTerm);
            }

            // Search by category name
            if ($request->has('kategori')) {
                $kategoriTerm = '%' . $request->input('kategori') . '%';
                $query->whereHas('kategori', function (Builder $query) use ($kategoriTerm) {
                    $query->where('kategori', 'LIKE', $kategoriTerm);
                });
            }

            // Filter by price range
            if ($request->has('harga_min')) {
                $query->where('harga', '>=', $request->input('harga_min'));
            }
            if ($request->has('harga_max')) {
                $query->where('harga', '<=', $request->input('harga_max'));
            }

            // Filter by availability
            if ($request->has('tersedia')) {
                $query->where('tersedia', $request->boolean('tersedia'));
            }

            // Apply sorting
            $sortField = $request->input('sort', 'menu'); // default sort by menu name
            $sortDirection = $request->input('direction', 'asc');
            $allowedSortFields = ['menu', 'harga', 'created_at'];
            
            if (in_array($sortField, $allowedSortFields)) {
                $query->orderBy($sortField, $sortDirection);
            }

            // Apply pagination
            $perPage = $request->input('per_page', 10);
            $menus = $query->paginate($perPage);

            // Transform image URLs
            $menus->getCollection()->transform(function ($menu) {
                if ($menu->gambar) {
                    $menu->gambar_url = url($menu->gambar);
                }
                return $menu;
            });

            return $this->successResponse([
                'data' => $menus->items(),
                'pagination' => [
                    'current_page' => $menus->currentPage(),
                    'per_page' => $menus->perPage(),
                    'total' => $menus->total(),
                    'last_page' => $menus->lastPage()
                ]
            ], 'Pencarian menu berhasil');

        } catch (\Exception $e) {
            return $this->errorResponse(
                'Gagal melakukan pencarian: ' . $e->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
