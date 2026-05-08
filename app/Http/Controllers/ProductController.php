<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;  
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);

        return view('product.index', compact('products'));
    }

    public function store(StoreProductRequest $request)
    {
        try {
        $validated = $request->validated();

        $validated['user_id'] = Auth::id();

        $product = Product::create($validated);

        Log::info('Menambah data produk', [
            'list' => $product
        ]);

        return response()->json([
            'message' => 'Produk berhasil ditambahkan!!',
            'data' => $product,
        ], 201);
    } catch (\Throwable $e) {
        Log::error('Error saat menambah product', [
            'message' => $e->getMessage(),
        ]);
    }
        $validated = $request->validated();

        // Map 'quantity' to 'qty' for database
        $validated['qty'] = $validated['quantity'];
        unset($validated['quantity']);

        try {
            Product::create($validated);

            return redirect()
                ->route('product.index')
                ->with('success', 'Produk berhasil ditambahkan.');

        } catch (QueryException $e) {
            Log::error('Product store database error', [
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error database saat membuat produk.');

        } catch (\Throwable $e) {
            Log::error('Product store unexpected error', [
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi error yang tidak terduga.');
        }
    }

    public function create()
    {
        Gate::authorize('manage-product');

        $users = User::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('product.create', compact('users', 'categories'));
    }

    public function show($id)
    {
        try {
        $product = Product::with('category')->find($id);

        if (!$product)
        {
            return response()->json([
                'message' => 'Product tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'message' => 'Product retrieved successfully',
            'data' => $product
        ], 200);
    } catch (\Throwable $e) {
        Log::error('Gagal mengambil data produk', [
            'message' => $e->getMessage(),
        ]);
    }


        $product = Product::findOrFail($id);

        return view('product.view', compact('product'));
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);

        Gate::authorize('update', $product);

        $validated = $request->validated();

        // Map 'quantity' to 'qty' for database
        if (isset($validated['quantity'])) {
            $validated['qty'] = $validated['quantity'];
            unset($validated['quantity']);
        }

        try {
            $product->update($validated);

            return redirect()
                ->route('product.index')
                ->with('success', 'Produk berhasil diperbarui.');

        } catch (QueryException $e) {
            Log::error('Product update database error', [
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error database saat memperbarui produk.');

        } catch (\Throwable $e) {
            Log::error('Product update unexpected error', [
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi error yang tidak terduga.');
        }
    }

    public function edit(Product $product)
    {
        Gate::authorize('update', $product);
        $users = User::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('product.edit', compact('product', 'users', 'categories'));
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        Gate::authorize('delete', $product);

        $product->delete();

        return redirect()->route('product.index')->with('success', 'Product berhasil dihapus');
    }
}