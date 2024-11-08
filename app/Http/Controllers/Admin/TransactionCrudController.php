<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TransactionRequest;
use App\Models\Product;
use App\Models\Transaction;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class TransactionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TransactionCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Transaction::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/transaction');
        CRUD::setEntityNameStrings('transaction', 'transactions');
        CRUD::denyAccess(['create', 'update', 'delete', 'show']);

        if (backpack_user()->can('delete_transaction')) {
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
            'name' => 'product',
            'type' => 'select2',
            'label' => 'Product',
        ], function () {
            $products = \App\Models\Product::get();
            if ($products->count() > 0) {
                foreach ($products as $k => $v) {
                    $res[$v->id] = $v->name;
                }
                return ($res);
            }
        }, function ($values) {
            CRUD::addClause('where', 'product_id', '=', (int) $values);
        });

        CRUD::column('id');
        CRUD::column('ref_no');
        CRUD::column('product_id');
        CRUD::column('quantity');
        CRUD::column('total_amount');
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
    

    protected function store(TransactionRequest $request) {

        try {

            $ref = 'INV-' . floor(microtime(true) * 1000);
            
            $product = Product::find($request->product_id);

            if ($product) {

                if ($product->quantity < $request->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Not Enough Product!',
                    ], 403);
                }

                $transaction = Transaction::create([
                    'ref_no' => $ref,
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                    'total_amount' => $request->total_amount,
                ]);
    
    
                if($transaction) {
    
                    $product->quantity = $product->quantity - $request->quantity;
                    $product->save();
    
                    return response()->json([
                        'success' => true,
                        'message' => 'Enjoy your snack',
                    ], 201);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to buy!',
                    ], 500);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Product Not Found!',
                ], 404);
            }

            

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to buy!',
            ], 500);
        }

    }
}
