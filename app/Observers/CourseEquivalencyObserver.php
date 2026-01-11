<?php

namespace App\Observers;

use App\Models\CourseEquivalency;

class CourseEquivalencyObserver
{
    /**
     * Handle the CourseEquivalency "created" event.
     */
    public function created(CourseEquivalency $courseEquivalency): void
    {
        $this->updateListStatistics($courseEquivalency);
    }

    /**
     * Handle the CourseEquivalency "updated" event.
     */
    public function updated(CourseEquivalency $courseEquivalency): void
    {
        $this->updateListStatistics($courseEquivalency);
    }

    /**
     * Handle the CourseEquivalency "deleted" event.
     */
    public function deleted(CourseEquivalency $courseEquivalency): void
    {
        $this->updateListStatistics($courseEquivalency);
    }

    /**
     * Update the parent EquivalencyList statistics
     */
    private function updateListStatistics(CourseEquivalency $courseEquivalency): void
    {
        if ($courseEquivalency->equivalency_list_id) {
            $list = $courseEquivalency->equivalencyList;

            if ($list) {
                $list->updateStatistics();
            }
        }
    }
}
