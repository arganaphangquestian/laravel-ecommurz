<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Transaction;

class CartController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/cart",
     *     tags={"Cart"},
     *     summary="Returns all cart data.",
     *     description="Get All Cart operation",
     *     operationId="cartIndex",
     *     security={{ "apiAuth": {} }},
     *     @OA\Response(
     *         response="default",
     *         description="successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string"),
     *         ),
     *     ),
     * )
     */
    public function index()
    {
        $carts = Cart::all();
        return response()->json(compact('carts'), 200);
    }

    public function store(Request $request)
    {
        $cart = Cart::create($request->all());
        return response()->json(compact('cart'), 201);
    }

    public function show($id)
    {
        $cart = Cart::findOrFail($id);
        return response()->json(compact('cart'), 201);
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::find($id);
        if($cart) {
            $cart->update($request->all());
            return response()->json(compact('cart'), 200);
        }
        return response()->json(["message" => "Cart not found"], 404);
    }

    public function destroy($id)
    {
        $cart = Cart::findOrFail($id);
        if($cart) {
            $cart->destroy();
            return response()->json(compact('cart'), 200);
        }
        return response()->json(["message" => "Cart not found"], 404);
    }

    public function getCartByUser() {
        $transaction = JWTAuth::parseToken()->authenticate()->transactions->where('isPaid', '=', 0)->first();
        if(!$transaction) {
            return response()->json(['message' => "Cart is Empty"], 404);
        }
        $products = $transaction->carts->map(function($x) {
            $product = Product::findOrFail($x->product_id);
            return ['product' => $product, 'amount' => $x->amount, 'total' => $product->price * $x->amount];
        });
        if(!$products) {
            return response()->json(['message' => "Cart is Empty"], 404);
        }
        return response()->json(['data' => $products], 200);
    }

    public function addToCart(Request $request) {
        // get latest transaction if isPaid == false, if there is no transaction create it
        $transaction = JWTAuth::parseToken()->authenticate()->transactions->where('isPaid', '=', 0)->first();
        if(!$transaction) {
            $transaction = Transaction::create([
                'buyer_id' => JWTAuth::parseToken()->authenticate()->id,
            ]);
        }
        $cart = Cart::firstOrCreate([
            'transaction_id' => $transaction->id,
            'product_id' => $request->input('product_id'),
        ]);

        return response()->json(['data' => $cart], 201);
    }

    public function changeAmount(Request $request) {
        $cart = Cart::findOrFail($request->input('id'));
        if(!$cart) {
            return response()->json(['message' => 'Cart not found.'], 404);
        }
        $cart->update(['amount' => $request->input('amount')]);
        return response()->json(['data' => $cart], 200);
    }
}