<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UmkmRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>>
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
            'name' => 'required|max:100',
            'description' => 'required|max:150',
            'address' => 'required|max:150',
            'city' => 'required|max:50',
            'province' => 'required|max:50',
            'owner_name' => 'required|max:50',
            'contact' => 'required|max:20|unique:umkms,contact',
            'photos' => 'required|array'
        ];
    }
    private function update(): array
    {
        return [
            'name' => 'required|max:100',
            'description' => 'required|max:150',
            'address' => 'required|max:150',
            'city' => 'required|max:50',
            'province' => 'required|max:50',
            'owner_name' => 'required|max:50',
            'contact' => 'required|unique:umkms,contact,' . $this->umkm,
            'photos' => 'nullable|array'
        ];
    }
}
