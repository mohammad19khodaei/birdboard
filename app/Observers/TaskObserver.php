<?php

namespace App\Observers;

use App\Task;

class TaskObserver
{
    /**
     * record created activity
     *
     * @param  Task $task
     * @return void
     */
    public function created(Task $task)
    {
        $task->recordActivity('task-created');
    }

        
    /**
     * record deleted activity
     *
     * @param  Task $task
     * @return void
     */
    public function deleted(Task $task)
    {
        $task->recordActivity('task-deleted');
    }
}
