<?php

namespace App\Observers;

use App\Models\Application;
use App\Models\Log;

class ApplicationObserver
{
    /**
     * Handle the Application "created" event.
     *
     * @param  \App\Models\Application  $application
     * @return void
     */
    public function created(Application $application)
    {
        //
    }

    /**
     * Handle the Application "updated" event.
     *
     * @param  \App\Models\Application  $application
     * @return void
     */
    public function updated(Application $application)
    {
        //
    }

    /**
     * Handle the Application "deleted" event.
     *
     * @param  \App\Models\Application  $application
     * @return void
     */
    public function deleted(Application $application)
    {
        Log::create($this->result(
            $application,
            __FUNCTION__)
        );
    }

    /**
     * Handle the Application "restored" event.
     *
     * @param  \App\Models\Application  $application
     * @return void
     */
    public function restored(Application $application)
    {
        //
    }

    /**
     * Handle the Application "force deleted" event.
     *
     * @param  \App\Models\Application  $application
     * @return void
     */
    public function forceDeleted(Application $application)
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
