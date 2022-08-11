<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Transaction;
use App\TransactionDetail;


class DashboardController extends Controller
{
    public function index()
    {
        $customer = User::count();
        $days = Transaction::whereIn('transaction_status', ["SUCCESS", "PENDING"])->whereDate('created_at', date('Y-m-d'))->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->sum('total_price');
        $month = Transaction::whereIn('transaction_status', ["SUCCESS", "PENDING"])->whereMonth('created_at', date('m'))->sum('total_price');
        $years = Transaction::whereIn('transaction_status', ["SUCCESS", "PENDING"])->whereYear('created_at', date('Y'))->sum('total_price');
        // $transaction = Transaction::whereIn('transaction_status', ["SUCCESS", "PENDING"])->count();

        $transactions = TransactionDetail::with(['transaction.user', 'product.galleries'])
            ->wherehas('transaction', function ($q) {
                $q->whereIn('transaction_status', ["SUCCESS", "PENDING"]);
            })
            ->when(request()->start, function ($query) {
                $query->whereDate('created_at', '>=', request()->start);
            })->when(request()->end, function ($query) {
                $query->whereDate('created_at', '<=', request()->end);
            })
            ->when(request()->bulan, function ($query) {
                $query->whereMonth('created_at', request()->bulan);
            })->when(request()->tahun, function ($query) {
                $query->whereYear('created_at', request()->tahun);
            })
            ->latest();


        return view('pages.admin.dashboard', [
            'customer' => $customer,
            'days' => $days,
            'month' => $month,
            'years' => $years,
            'transaction_count' => $transactions->count(),
            'transaction_data' => $transactions->get(),
            'transaction_sum' => $transactions->sum("price"),
            // 'transaction' => $transaction
        ]);
    }

    public function reportProduct()
    {
        $failed = Transaction::where('transaction_status', "FAILED")->count();
        $days = TransactionDetail::wherehas('transaction', function ($q) {
            $q->whereIn('transaction_status', ["SUCCESS", "PENDING"]);
        })->whereDate('created_at', date('Y-m-d'))->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->sum('quantity');
        $month = TransactionDetail::wherehas('transaction', function ($q) {
            $q->whereIn('transaction_status', ["SUCCESS", "PENDING"]);
        })->whereMonth('created_at', date('m'))->sum('quantity');
        $years = TransactionDetail::wherehas('transaction', function ($q) {
            $q->whereIn('transaction_status', ["SUCCESS", "PENDING"]);
        })->whereYear('created_at', date('Y'))->sum('quantity');



        $transactions = TransactionDetail::with(['transaction', 'product.galleries'])
            ->wherehas('transaction', function ($q) {
                $q->whereIn('transaction_status', ["SUCCESS", "PENDING"]);
            })
            ->when(request()->start, function ($query) {
                $query->whereDate('created_at', '>=', request()->start);
            })->when(request()->end, function ($query) {
                $query->whereDate('created_at', '<=', request()->end);
            })
            ->when(request()->bulan, function ($query) {
                $query->whereMonth('created_at', request()->bulan);
            })->when(request()->tahun, function ($query) {
                $query->whereYear('created_at', request()->tahun);
            })
            ->groupBy('products_id')
            ->orderByRaw('SUM(quantity) desc')
            ->selectRaw('sum(quantity) as sum, products_id,transactions_id,price');



        return view('pages.admin.product.report', [
            'failed' => $failed,
            'days' => $days,
            'month' => $month,
            'years' => $years,
            'transaction_count' => $transactions->count(),
            'transaction_data' => $transactions->get(),
        ]);
    }
}
