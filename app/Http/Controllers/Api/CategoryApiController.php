<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryApiController extends Controller
{
    /**
     * GET - Menampilkan semua kategori
     */
    public function index()
    {
        try {
            $categories = Category::all();

            return response()->json([
                'message' => 'Data kategori berhasil diambil',
                'data' => $categories,
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Error mengambil data kategori', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST - Membuat kategori baru
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|unique:categories,name',
            ]);

            $category = Category::create($data);

            return response()->json([
                'message' => 'Kategori berhasil dibuat',
                'data' => $category,
            ], 201);
        } catch (\Throwable $e) {
            Log::error('Error membuat kategori', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Terjadi kesalahan saat membuat kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET - Menampilkan kategori berdasarkan ID
     */
    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);

            return response()->json([
                'message' => 'Data kategori berhasil diambil',
                'data' => $category,
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Error mengambil data kategori', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Kategori tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * PUT - Memperbarui kategori
     */
    public function update(Request $request, $id)
    {
        try {
            $category = Category::findOrFail($id);

            $data = $request->validate([
                'name' => 'required|string|unique:categories,name,' . $id,
            ]);

            $category->update($data);

            return response()->json([
                'message' => 'Kategori berhasil diperbarui',
                'data' => $category,
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Error memperbarui kategori', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Terjadi kesalahan saat memperbarui kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * DELETE - Menghapus kategori
     */
    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();

            return response()->json([
                'message' => 'Kategori berhasil dihapus',
                'data' => $category,
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Error menghapus kategori', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
