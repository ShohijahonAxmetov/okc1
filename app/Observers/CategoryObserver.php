<?php

namespace App\Observers;

use App\Models\Category;
use App\Models\Log;

class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     *
     * @param  \App\Models\Category  $category
     * @return void
     */
    public function created(Category $category)
    {
        //
    }

    /**
     * Handle the Category "updated" event.
     *
     * @param  \App\Models\Category  $category
     * @return void
     */
    public function updated(Category $category)
    {
        Log::create($this->result(
            $category,
            __FUNCTION__)
        );
    }

    /**
     * Handle the Category "deleted" event.
     *
     * @param  \App\Models\Category  $category
     * @return void
     */
    public function deleted(Category $category)
    {
        //
    }

    /**
     * Handle the Category "restored" event.
     *
     * @param  \App\Models\Category  $category
     * @return void
     */
    public function restored(Category $category)
    {
        //
    }

    /**
     * Handle the Category "force deleted" event.
     *
     * @param  \App\Models\Category  $category
     * @return void
     */
    public function forceDeleted(Category $category)
    {
        //
    }

    protected function result($post, $action)
    {
        return [
            'admin_id' => auth()->user()->id ?? 0,
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
