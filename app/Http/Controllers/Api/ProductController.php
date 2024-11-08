<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProductCreateRequest;
use App\Http\Requests\Api\ProductUpdateRequest;
use App\Http\Requests\Api\SingleProductRequest;
use App\Models\Categorie;
use Illuminate\Http\JsonResponse;
use App\Services\ProductService;
use Illuminate\Http\Request;


class ProductController extends Controller
{

    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request) {

        $perPage = $request->input('per_page')?:10;
        $products = $this->productService->getAllProducts($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Data fetched successfully',
            'data' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'last_page' => $products->lastPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
            ]
        ], 200);

    }

    public function categories(Request $request) {
        $perPage = $request->input('per_page');
        $categories = Categorie::paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Data fetched successfully',
            'data' => $categories->items(),
            'pagination' => [
                'current_page' => $categories->currentPage(),
                'per_page' => $categories->perPage(),
                'total' => $categories->total(),
                'last_page' => $categories->lastPage(),
                'from' => $categories->firstItem(),
                'to' => $categories->lastItem(),
            ]
        ], 200);
    }

    public function create(ProductCreateRequest $request) : JsonResponse {
        
        $this->productService->createProduct([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'quantity' => $request->quantity,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully!.'
        ], 201);
    }

    public function detail(SingleProductRequest $request) {

        $product = $this->productService->findProduct($request->id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Item fetched successfully',
            'data' => $product
        ], 200);

    }

    public function update(ProductUpdateRequest $request) {

        try {

            $product = $this->productService->updateProduct($request->id, $request->all());

                $product->name = $request->name;
                $product->category_id = $request->category_id;
                $product->price = $request->price;
                $product->quantity = $request->quantity;
                $product->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Product updated successfully!'
                ], 404);

            

        } catch (\Throwable $th) {
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product'
            ], 500);

        }
    }

    public function destory(SingleProductRequest $request) {
        
        $product = $this->productService->deleteProduct($request->id);

        if ($product) {

            return response()->json([
                'success' => true,
                'message' => 'Item deleted successfully',
            ], 200);

        } else {
            return response()->json([
                'success' => false,
                'message' => 'Item not found'
            ], 404);
        }

    }
}
