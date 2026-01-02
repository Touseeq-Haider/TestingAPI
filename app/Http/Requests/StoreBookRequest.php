<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        // True means anyone can call this request, change if using auth
        return true;
    }

    public function rules(): array
    {
        return [
            'title'  => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'price'  => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'  => 'Book title is required',
            'author.required' => 'Author name is required',
            'price.required'  => 'Price is required',
            'price.numeric'   => 'Price must be a number',
        ];
    }
}
