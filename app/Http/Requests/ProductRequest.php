<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        if ($this->method() === 'POST') {
            return $this->store();
        }
        return $this->update();
    }

    private function store(): array
    {
        return [
            'code' => 'required|max:10|unique:products,code',
            'name' => 'required|max:50',
            'description' => 'required|max:255',
            'umkm_id' => 'required',
            'price' => 'required|integer|min:0',
            'photos' => 'required|array'
        ];
    }
    private function update(): array
    {
        return [
            'code' => 'required|unique:products,code,' . $this->product,
            'name' => 'required|max:50',
            'umkm_id' => 'required',
            'price' => 'required|integer|min:0',
            'description' => 'required|max:255',
            'photos' => 'nullable|array'
        ];
    }
}
