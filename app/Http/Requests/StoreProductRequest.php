<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:255|not_regex:/^[\s]+$/',
            'quantity' => 'required|integer|min:1|max:999999',
            'price' => 'required|numeric|min:0.01|max:999999999.99',
            'user_id' => 'required|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama produk wajib diisi.',
            'name.string' => 'Nama produk harus berupa teks.',
            'name.min' => 'Nama produk minimal 3 karakter.',
            'name.max' => 'Nama produk tidak boleh lebih dari 255 karakter.',
            'name.not_regex' => 'Nama produk tidak boleh hanya berisi spasi.',

            'quantity.required' => 'Jumlah (kuantitas) produk wajib diisi.',
            'quantity.integer' => 'Jumlah produk harus berupa angka bulat (tidak boleh desimal).',
            'quantity.min' => 'Jumlah produk minimal 1 unit.',
            'quantity.max' => 'Jumlah produk tidak boleh melebihi 999.999 unit.',

            'price.required' => 'Harga produk wajib diisi.',
            'price.numeric' => 'Harga produk harus berupa angka yang valid.',
            'price.min' => 'Harga produk minimal Rp 0,01.',
            'price.max' => 'Harga produk terlalu besar.',

            'user_id.required' => 'Pemilik produk wajib dipilih.',
            'user_id.exists' => 'Pemilik produk yang dipilih tidak valid.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => auth()->id(),
        ]);
    }
}