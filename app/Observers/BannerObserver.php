<?php

namespace App\Observers;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Log;

class BannerObserver extends Controller
{
    /**
     * Handle the Banner "created" event.
     *
     * @param  \App\Models\Banner  $banner
     * @return void
     */
    public function created(Banner $banner)
    {
        Log::create($this->result(
            $banner,
            __FUNCTION__)
        );
    }

    /**
     * Handle the Banner "updated" event.
     *
     * @param  \App\Models\Banner  $banner
     * @return void
     */
    public function updated(Banner $banner)
    {
        Log::create($this->result(
            $banner,
            __FUNCTION__)
        );
    }

    /**
     * Handle the Banner "deleted" event.
     *
     * @param  \App\Models\Banner  $banner
     * @return void
     */
    public function deleted(Banner $banner)
    {
        Log::create($this->result(
            $banner,
            __FUNCTION__)
        );
    }

    /**
     * Handle the Banner "restored" event.
     *
     * @param  \App\Models\Banner  $banner
     * @return void
     */
    public function restored(Banner $banner)
    {
        //
    }

    /**
     * Handle the Banner "force deleted" event.
     *
     * @param  \App\Models\Banner  $banner
     * @return void
     */
    public function forceDeleted(Banner $banner)
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
