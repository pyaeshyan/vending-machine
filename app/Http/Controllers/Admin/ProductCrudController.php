<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ProductCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProductCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Product::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product');
        CRUD::setEntityNameStrings('product', 'products');
        CRUD::denyAccess(['create', 'update', 'delete', 'show']);

        if (backpack_user()->can('create_product')) {
            CRUD::allowAccess('create');
        }

        if (backpack_user()->can('edit_product')) {
            CRUD::allowAccess('update');
        }


        if (backpack_user()->can('delete_product')) {
            CRUD::allowAccess('delete');
        }
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {

        CRUD::addFilter([ // select2_multiple filter
            'name' => 'category',
            'type' => 'select2',
            'label' => 'Category',
        ], function () {
            $categories = \App\Models\Categorie::get();
            if ($categories->count() > 0) {
                foreach ($categories as $k => $v) {
                    $res[$v->id] = $v->title;
                }
                return ($res);
            }
        }, function ($values) {
            CRUD::addClause('where', 'category_id', '=', (int) $values);
        });


        CRUD::column('id');
        CRUD::column('name');
        CRUD::column('category');
        CRUD::column('price');
        CRUD::column('quantity');
        CRUD::column('created_at');
        CRUD::column('updated_at');
        

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ProductRequest::class);

        CRUD::addField([ // Select
            'label' => 'Product Name',
            'type' => 'text',
            'name' => 'name',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],

        ]);

        CRUD::addField([ // Select
            'label' => 'Select Category',
            'type' => 'select',
            'name' => 'category_id',
            'entity'    => 'category',
            'model'     => "App\Models\Categorie",
            'attribute' => 'title',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],

        ]);

        CRUD::addField([ // Select
            'label' => 'Price',
            'type' => 'number',
            'name' => 'price',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],

        ]);

        CRUD::addField([ // Select
            'label' => 'Quantity',
            'type' => 'number',
            'name' => 'quantity',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],

        ]);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function store(ProductRequest $request) {

        try {
            
            $product = Product::create([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'price' => $request->price,
                'quantity' => $request->quantity,
            ]);

            if($product) {

                \Alert::add('success', 'Product created successfully!')->flash();
                return redirect()->route('product.index');

            } else {

                \Alert::add('danger', 'Failed to create Product!')->flash();
                return redirect()->route('product.index');

            }

        } catch (\Throwable $th) {
           
            \Alert::add('danger', 'Failed to create Product!')->flash();
                return redirect()->route('product.index');

        }

    }

    protected function update(ProductRequest $request) {

        try {
            
            $product = Product::find($request->id);

            if($product) {

                $product->name = $request->name;
                $product->category_id = $request->category_id;
                $product->price = $request->price;
                $product->quantity = $request->quantity;
                $product->save();

                \Alert::add('success', 'Product updated successfully!')->flash();
                return redirect()->route('product.index');

            } else {

                \Alert::add('danger', 'Failed to update product!')->flash();
                return redirect()->route('product.index');

            }

        } catch (\Throwable $th) {
            
            \Alert::add('danger', 'Failed to update product!')->flash();
            return redirect()->route('product.index');

        }

    }
}
