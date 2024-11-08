<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:2|max:255',
            'category_id' => 'required',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Product Name is required',
            'name.min:2' => 'Product Name must be at least 2 character',
            'name.max:255' => 'Product Name must be at most 255 character',
            'category_id.required' => 'Category Name is requierd',
            'price.required' => 'Price is required',
            'price.numric' => 'Price must be numric value',
            'price.min' => 'Price must be positive value',
            'quantity.required' => 'Quantity is required',
            'quantity.numric' => 'Quantity must be numric value',
            'quantity.min' => 'Quantity must be positive value',
        ];
    }
}
