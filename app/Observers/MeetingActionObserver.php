<?php

namespace App\Observers;

use App\Notifications\DataChangeEmailNotification;
use App\Notifications\AssignedTicketNotification;
use App\Meeting;
use Illuminate\Support\Facades\Notification;

class MeetingActionObserver
{
    public function created(Meeting $model)
    {
        $data  = ['action' => 'New meeting has been created!', 'model_name' => 'Meeting', 'meeting' => $model];
        $users = \App\User::whereHas('roles', function ($q) {
            return $q->where('title', 'Admin');
        })->get();
      
    }

    public function updated(Meeting $model)
    {
        if($model->isDirty('assigned_to_user_id'))
        {
            $user = $model->assigned_to_user;
            
        }
    }
}
