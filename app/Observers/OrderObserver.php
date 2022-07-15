<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Log;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function created(Order $order)
    {
        //
    }

    /**
     * Handle the Order "updated" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        if($order->is_deleted == 1) {
            Log::create($this->result(
                $order,
                'deleted')
            );
        } else {
            Log::create($this->result(
                $order,
                __FUNCTION__)
            );
        }
    }

    /**
     * Handle the Order "deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function deleted(Order $order)
    {
        Log::create($this->result(
            $order,
            __FUNCTION__)
        );
    }

    /**
     * Handle the Order "restored" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function restored(Order $order)
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        //
    }

    protected function result($post, $action)
    {
        return [
            'admin_id' => auth()->user()->id,
            'model' => str_replace(
                'Observer',
                '',
                class_basename(self::class)
            ),
            'item_id' => $post->id,
            'action' => $action
        ];
    }
}
