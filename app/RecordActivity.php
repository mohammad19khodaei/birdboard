<?php

namespace App;

use Illuminate\Support\Arr;

trait RecordActivity
{
    public array $old = [];
    
    /**
     * activities
     *
     * @return void
     */
    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }
    
    /**
     * bootRecordActivity
     *
     * @return void
     */
    public static function bootRecordActivity()
    {
        static::updating(function ($model) {
            $model->old = $model->getOriginal();
        });
    }
    /**
     * recordActivity
     *
     * @param  string $description
     * @return void
     */
    public function recordActivity(string $description)
    {
        $this->activities()->create([
            'description' => $description,
            'user_id' => ($this->project ?? $this)->owner->id,
            'project_id' => class_basename($this) === 'Project' ? $this->id : $this->project_id,
            'changes' =>  $this->activityChanges()
        ]);
    }
    
    /**
     * activityChanges
     *
     * @return void
     */
    protected function activityChanges()
    {
        if ($this->wasChanged()) {
            return [
                'before' => Arr::except(array_diff($this->old, $this->getAttributes()), ['updated_at']),
                'after' => Arr::except($this->getChanges(), ['updated_at'])
            ];
        }
    }
}
