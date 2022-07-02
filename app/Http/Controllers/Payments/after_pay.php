<?php
    \App\Models\Order::find($transaction->transactionable_id)->update([
        'is_payed' => 1
    ]);