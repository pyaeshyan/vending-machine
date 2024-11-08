<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    
    public function createProduct(array $data): Product
    {
        return $this->product->create($data);
    }

    public function findProduct(int $id): ?Product
    {
        return $this->product->find($id);
    }

    public function updateProduct(int $id, array $data): ?Product
    {
        $product = $this->findProduct($id);

        if ($product) {
            $product->update($data);
            return $product;
        }

        return null;
    }

    public function deleteProduct(int $id): bool
    {
        $product = $this->findProduct($id);

        if ($product) {
            return $product->delete();
        }

        return false;
    }

    public function getAllProducts(int $perPage): LengthAwarePaginator
    {
        return $this->product->paginate($perPage);
    }
}
