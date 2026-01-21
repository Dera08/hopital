<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Payment;
use App\Models\TransactionLog;
use App\Models\SpecialistWallet;

$txFile = __DIR__ . '/last_txref.txt';
$txRef = 'HPTEST';
if (file_exists($txFile)) {
    $txRef = trim(file_get_contents($txFile));
}
$payment = Payment::where('transaction_ref', 'like', "%{$txRef}%")->orderBy('id','desc')->first();
if ($payment) {
    echo "Payment: id={$payment->id} ref={$payment->transaction_ref} status={$payment->status} amount={$payment->amount}\n";
} else {
    echo "No payment found matching {$txRef}\n";
}

$logs = TransactionLog::where('description', 'like', "%{$txRef}%")->get();
if ($logs->isNotEmpty()) {
    echo "TransactionLog entries:\n";
    foreach ($logs as $l) {
        echo " - id={$l->id} source_type={$l->source_type} amount={$l->amount} net_income={$l->net_income} desc={$l->description}\n";
    }
} else {
    echo "No TransactionLog entries for {$txRef}\n";
}

$wallet = SpecialistWallet::where('specialist_id', 999)->first();
if ($wallet) {
    echo "SpecialistWallet: id={$wallet->id} specialist_id={$wallet->specialist_id} balance={$wallet->balance}\n";
} else {
    echo "No SpecialistWallet for specialist_id=999\n";
}
