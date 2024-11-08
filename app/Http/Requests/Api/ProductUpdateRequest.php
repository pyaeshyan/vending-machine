<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|numeric|min:0',
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
            'id.required' => 'Product Id is required',
            'id.numric' => 'Product Id must be numric value',
            'id.min' => 'Product Id must be positive value',
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
