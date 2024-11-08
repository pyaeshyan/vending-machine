<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SingleTransactionRequest;
use App\Http\Requests\Api\TransactionCreateRequest;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request) {
        $perPage = $request->input('per_page');
        $transactions = Transaction::paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Data fetched successfully',
            'data' => $transactions->items(),
            'pagination' => [
                'current_page' => $transactions->currentPage(),
                'per_page' => $transactions->perPage(),
                'total' => $transactions->total(),
                'last_page' => $transactions->lastPage(),
                'from' => $transactions->firstItem(),
                'to' => $transactions->lastItem(),
            ]
        ], 200);
    }

    public function create(TransactionCreateRequest $request) {

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
                    'total_amount' => $request->quantity * $product->price,
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

    public function detail(SingleTransactionRequest $request) {

        $transaction = Transaction::find($request->id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Item fetched successfully',
            'data' => $transaction
        ], 200);

    }

    public function destory(SingleTransactionRequest $request) {

        $Transaction = Transaction::find($request->id);

        if ($Transaction) {

            $Transaction->delete();

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
