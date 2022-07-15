<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\Log;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function created(Product $product)
    {
        //
    }

    /**
     * Handle the Product "updated" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function updated(Product $product)
    {
        Log::create($this->result(
            $product,
            __FUNCTION__)
        );
    }

    /**
     * Handle the Product "deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function deleted(Product $product)
    {
        Log::create($this->result(
            $product,
            __FUNCTION__)
        );
    }

    /**
     * Handle the Product "restored" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function restored(Product $product)
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function forceDeleted(Product $product)
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
