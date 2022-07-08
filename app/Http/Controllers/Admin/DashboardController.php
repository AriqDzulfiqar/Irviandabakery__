<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\User;
use App\Transaction;
use App\TransactionDetail;


class DashboardController extends Controller
{
    public function index()
    {
        $customer = User::count();
        $days = Transaction::whereDate('created_at',date('d'))->sum('total_price');
        $month = Transaction::whereMonth('created_at',date('m'))->sum('total_price');
        $years = Transaction::whereYear('created_at',date('Y'))->sum('total_price');
        $revenue = Transaction::sum('total_price');
        $transaction = Transaction::count();

        $transactions = TransactionDetail::with(['transaction.user', 'product.galleries'])
            ->whereHas('product', function ($product) {
                $product->where('users_id', Auth::user()->id);
            })->when(request()->start, function($query){
                 $query->whereDate('created_at', '>=', request()->start);
            })->when(request()->end, function($query){
                $query->whereDate('created_at', '<=', request()->end);
            })->latest();


        return view('pages.admin.dashboard', [
            'customer' => $customer,
            'days' => $days,
            'month' => $month,
            'years' => $years,
            'transaction_count' => $transactions->count(),
            'transaction_data' => $transactions->get(),
            'transaction_sum' => $transactions->sum("price"),
            'transaction' => $transaction
        ]);
    }
}
