<?php

namespace Tests\Unit;

use App\Http\Controllers\Api\ProductController;
use App\Http\Requests\Api\ProductCreateRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
// use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class ProductCrudUnitTest extends TestCase
{
    protected $productServiceMock;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();

        
        $this->productServiceMock = Mockery::mock(ProductService::class);
        $this->app->instance(ProductService::class, $this->productServiceMock);

        $this->controller = new ProductController($this->productServiceMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_creates_a_product()
    {
        $productMock = Mockery::mock(Product::class);
        $productMock->id = 1;
        $productMock->name = 'Test Product';
        $productMock->category_id = 1;
        $productMock->price = 99.99;
        $productMock->quantity = 100;

        $this->productServiceMock
            ->shouldReceive('createProduct')
            ->once()
            ->with([
                'name' => 'Test Product',
                'category_id' => 1,
                'price' => 99.99,
                'quantity' => 100,
            ])
            ->andReturn($productMock);

        $response = $this->productServiceMock->createProduct([
            'name' => 'Test Product',
            'category_id' => 1,
            'price' => 99.99,
            'quantity' => null,
        ]);

        $this->assertInstanceOf(Product::class, $response);
        $this->assertEquals('Test Product', $response->name);
        $this->assertEquals(99.99, $response->price);
        $this->assertNull($response->quantity);
    }

    public function test_reads_a_product()
    {
        
        $productId = 1;
        $data = [
            'id' => $productId,
            'name' => 'Test Product',
            'price' => 99.99,
            'category_id' => 1,
            'quantity' => 100,
        ];

        $mock = Mockery::mock('alias:Illuminate\Support\Facades\DB');
        $mock->shouldReceive('table')
            ->once()
            ->with('products')
            ->andReturnSelf();
        $mock->shouldReceive('find')
            ->once()
            ->with($productId)
            ->andReturn((object) $data);

        
        $product = new Product();
        $result = $product->findProduct($productId); 

        $this->assertEquals($data['name'], $result->name);
        $this->assertEquals($data['price'], $result->price);
    }

    
    public function it_updates_a_product()
    {

        $productId = 1;
        $data = [
            'id' => $productId,
            'name' => 'Test Product',
            'price' => 99.99,
            'category_id' => 1,
            'quantity' => 100,
        ];

        $mock = Mockery::mock('alias:Illuminate\Support\Facades\DB');
        $mock->shouldReceive('table')
            ->once()
            ->with('products')
            ->andReturnSelf();
        $mock->shouldReceive('where')
            ->once()
            ->with('id', $productId)
            ->andReturnSelf();
        $mock->shouldReceive('update')
            ->once()
            ->with($data)
            ->andReturn(true);


        $product = new Product();
        $result = $product->updateProduct($productId, $data); 

        $this->assertTrue($result);
    }

    public function it_deletes_a_product()
    {
        $productId = 1;

        $mock = Mockery::mock('alias:Illuminate\Support\Facades\DB');
        $mock->shouldReceive('table')
            ->once()
            ->with('products')
            ->andReturnSelf();
        $mock->shouldReceive('where')
            ->once()
            ->with('id', $productId)
            ->andReturnSelf();
        $mock->shouldReceive('delete')
            ->once()
            ->andReturn(true);

        $product = new Product();
        $result = $product->deleteProduct($productId); 
        
        $this->assertTrue($result);
    }

}
