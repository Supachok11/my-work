<?php

namespace App\Nova\Metrics;

use App\Models\LeaveRequest;
use DateTimeInterface;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;
use Laravel\Nova\Metrics\PartitionResult;

class LeaveRequestMetric extends Partition
{
    /**
     * Calculate the value of the metric.
     */
    public function calculate(NovaRequest $request): PartitionResult
    {
        $query = LeaveRequest::query();

        // If user is not admin, show only their own requests
        if (!($request->user()->hasRole('admin'))) {
            $query->where('user_id', $request->user()->id);
        }

        return $this->count(
            $request, 
            $query, 
            groupBy: 'status',
        );
    }

    /**
     * Determine the amount of time the results of the metric should be cached.
     */
    public function cacheFor(): DateTimeInterface|null
    {
        // return now()->addMinutes(5);

        return null;
    }

    /**
     * Get the URI key for the metric.
     */
    public function uriKey(): string
    {
        return 'leave-request-metric';
    }
}
