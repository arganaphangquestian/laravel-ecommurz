<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::all();
        return response()->json(compact('transactions'));
    }

    public function store(Request $request)
    {
        $transaction = Transaction::create($request->all());
        return response()->json(compact('transaction'), 201);
    }

    public function show($id)
    {
        $transaction = Transaction::find($id);
        return response()->json(compact('transaction'));
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        if($transaction) {
            $transaction->update($request->all());
            return response()->json(compact('transaction'), 200);
        }
        return response()->json(["message" => "Transaction not found."], 404);
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        if($transaction) {
            $transaction->destroy();
            return response()->json(compact('transaction'), 200);
        }
        return response()->json(["message" => "Transaction not found."], 404);
    }

    public function getTransactionByUser() {
        $transaction = JWTAuth::parseToken()->authenticate()->transactions;
        return response()->json(['data' => $transaction], 200);
    }

    public function payTransactionByID($id) {
        $transaction = JWTAuth::parseToken()->authenticate()->transactions->where('isPaid', '=', 0)->first();
        if($transaction) {
            $transaction->update(['isPaid' => 1]);
            return response()->json(['message' => 'Payment with transaction id='.$id.' accepted.'], 200);
        }
        return response()->json(['message' => 'Payment with transaction id='.$id.' rejected.'], 400);
    }

}
