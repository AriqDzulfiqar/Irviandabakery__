<?php

namespace App\Console\Commands;

use App\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TransactionFailed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:failed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Update Status Menjadi Failed';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $expired = Carbon::now()->subHour(24);
        $transaction = Transaction::where('transaction_status', '=', 'PENDING')->where('created_at', '<=', $expired)->get();
        foreach ($transaction as $key => $value) {
            $value->transaction_status = 'FAILED';
            $value->notes = $value->notes . ' - update otomatis';
            $value->save();
        }
        \Log::info('Cron Update : ' . $transaction->count());
    }
}
