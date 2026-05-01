<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    public function index()
    {
        Gate::authorize('manage-category');
        
        $categories = Category::paginate(10);

        return view('category.index', compact('categories'));
    }

    public function store(Request $request)
    {
        Gate::authorize('manage-category');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ], [
            'name.required' => 'Nama kategori harus diisi.',
            'name.unique' => 'Nama kategori sudah ada.',
            'name.max' => 'Nama kategori maksimal 255 karakter.',
        ]);

        try {
            Category::create($validated);

            return redirect()
                ->route('category.index')
                ->with('success', 'Kategori berhasil ditambahkan.');

        } catch (QueryException $e) {
            Log::error('Category store database error', [
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error database saat membuat kategori.');

        } catch (\Throwable $e) {
            Log::error('Category store unexpected error', [
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
        Gate::authorize('manage-category');
        
        return view('category.create');
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);

        return view('category.view', compact('category'));
    }

    public function update(Request $request, $id)
    {
        Gate::authorize('manage-category');
        
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
        ], [
            'name.required' => 'Nama kategori harus diisi.',
            'name.unique' => 'Nama kategori sudah ada.',
            'name.max' => 'Nama kategori maksimal 255 karakter.',
        ]);

        try {
            $category->update($validated);

            return redirect()
                ->route('category.index')
                ->with('success', 'Kategori berhasil diperbarui.');

        } catch (QueryException $e) {
            Log::error('Category update database error', [
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error database saat memperbarui kategori.');

        } catch (\Throwable $e) {
            Log::error('Category update unexpected error', [
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi error yang tidak terduga.');
        }
    }

    public function edit(Category $category)
    {
        Gate::authorize('manage-category');
        
        return view('category.edit', compact('category'));
    }

    public function destroy($id)
    {
        Gate::authorize('manage-category');
        
        $category = Category::findOrFail($id);

        $category->delete();

        return redirect()->route('category.index')->with('success', 'Kategori berhasil dihapus');
    }
}
