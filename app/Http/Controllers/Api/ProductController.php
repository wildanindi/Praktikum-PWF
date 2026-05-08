<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * GET - Menampilkan semua produk
     */
    public function index()
    {
        try {
            $products = Product::with('category')->get();

            return response()->json([
                'message' => 'Data produk berhasil diambil',
                'data' => $products,
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Error mengambil data produk', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST - Membuat produk baru
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|unique:products,name',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'category_id' => 'nullable|exists:categories,id',
            ]);

            $product = Product::create($data);

            return response()->json([
                'message' => 'Produk berhasil dibuat',
                'data' => $product,
            ], 201);
        } catch (\Throwable $e) {
            Log::error('Error membuat produk', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Terjadi kesalahan saat membuat produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET - Menampilkan produk berdasarkan ID
     */
    public function show(string $id)
    {
        try {
            $product = Product::with('category')->findOrFail($id);

            return response()->json([
                'message' => 'Data produk berhasil diambil',
                'data' => $product,
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Error mengambil data produk', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Produk tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * PUT - Memperbarui produk
     */
    public function update(Request $request, string $id)
    {
        try {
            $product = Product::findOrFail($id);

            $data = $request->validate([
                'name' => 'required|string|unique:products,name,' . $id,
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'category_id' => 'nullable|exists:categories,id',
            ]);

            $product->update($data);

            return response()->json([
                'message' => 'Produk berhasil diperbarui',
                'data' => $product,
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Error memperbarui produk', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Terjadi kesalahan saat memperbarui produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * DELETE - Menghapus produk
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            return response()->json([
                'message' => 'Produk berhasil dihapus',
                'data' => $product,
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Error menghapus produk', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
