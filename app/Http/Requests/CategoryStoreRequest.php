<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryStoreRequest extends FormRequest
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
            'name' => 'required | max:255',
            'slug' => 'max:255|unique:categories,slug',
            'description' => 'max:255',
            'seo_keywords' => 'max:255',
            'seo_description' => 'max:255',
        ];
    }
    public function messages(){
        return [
            'name.required' => 'Kategori adı alanı zorunludur',
            'name.max' => 'Kategori adı alanı en fazla 255 karakter olabilir',
            'description.max' => 'Kategori açıklama alanı en fazla 255 karakter olabilir',
            'seo_keywords.max' => 'Kategori Seo Keywords adı alanı en fazla 255 karakter olabilir',
            'seo_description.max' => 'Kategori Seo Description adı alanı en fazla 255 karakter olabilir',
        ];
    }
}
