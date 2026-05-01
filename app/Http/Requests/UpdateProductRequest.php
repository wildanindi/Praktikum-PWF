<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|min:3|max:255|not_regex:/^[\s]+$/',
            'quantity' => 'sometimes|integer|min:1|max:999999',
            'price' => 'sometimes|numeric|min:0.01|max:999999999.99',
            'user_id' => 'sometimes|exists:users,id',
            'category_id' => 'nullable|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'Nama produk harus berupa teks.',
            'name.min' => 'Nama produk minimal 3 karakter.',
            'name.max' => 'Nama produk tidak boleh lebih dari 255 karakter.',
            'name.not_regex' => 'Nama produk tidak boleh hanya berisi spasi.',

            'quantity.integer' => 'Jumlah produk harus berupa angka bulat (tidak boleh desimal).',
            'quantity.min' => 'Jumlah produk minimal 1 unit.',
            'quantity.max' => 'Jumlah produk tidak boleh melebihi 999.999 unit.',

            'price.numeric' => 'Harga produk harus berupa angka yang valid.',
            'price.min' => 'Harga produk minimal Rp 0,01.',
            'price.max' => 'Harga produk terlalu besar.',

            'user_id.exists' => 'Pemilik produk yang dipilih tidak valid.',
            
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
        ];
    }
}
