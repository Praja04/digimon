<?php

namespace App\Listeners;

use App\Events\ProcessOutsideDisposition;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendProcessNotification
{
    /**
     * Handle the event.
     */
    public function handle(ProcessOutsideDisposition $event): void
    {
        $users = User::whereIn('role', ['foreman', 'supervisor'])->get();

        if ($users->isEmpty()) {
            return;
        }

        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'production_batch_id' => $event->production_batch_id,
                'title' => $event->title,
                'disposisi' => $event->disposisi,
                'remark' => $event->remark,
                'status' => 'unread',
            ]);
        }
    }
}
