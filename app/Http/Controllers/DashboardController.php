<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transactions; // Supondo que você tenha um modelo Transaction
use App\Models\Wallets;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $balance = Wallets::select('balance')->where('user_id', $user->id)->first(); // Obtém o saldo do usuário
        $moneySent = Transactions::where('sender_document', $user->documento)->get(); // Obtém as transações enviadas pelo usuário
        $moneyReceived = Transactions::where('recipient_document', $user->documento)->get(); // Obtém as transações recebidas pelo usuário

        return view('dashboard', compact('balance', 'moneySent', 'moneyReceived'));
    }
}
