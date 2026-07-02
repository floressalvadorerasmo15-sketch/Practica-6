<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // Listar transacciones del usuario autenticado
    public function index(Request $request)
    {
        return response()->json(
            $request->user()->transactions()->with('category')->get()
        );
    }

    // Crear transacción
    public function store(StoreTransactionRequest $request)
    {
        $transaction = $request->user()->transactions()->create(
            $request->validated()
        );

        return response()->json([
            'message' => 'Transacción creada correctamente',
            'data' => $transaction
        ], 201);
    }

    // Mostrar una transacción
    public function show(Transaction $transaction)
    {
        if ($transaction->user_id != auth()->id()) {
            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        return response()->json($transaction);
    }

    // Actualizar una transacción
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        if ($transaction->user_id != auth()->id()) {
            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        $transaction->update($request->validated());

        return response()->json([
            'message' => 'Transacción actualizada correctamente',
            'data' => $transaction
        ]);
    }

    // Eliminar una transacción
    public function destroy(Transaction $transaction)
    {
        if ($transaction->user_id != auth()->id()) {
            return response()->json([
                'message' => 'Transacción eliminada correctamente'
            ]);
        }

        $transaction->delete();

        return response()->json([
            'message' => 'Transacción eliminada correctamente'
        ]);
    }
}