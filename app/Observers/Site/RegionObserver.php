<?php

namespace App\Observers\Site;

use App\Actions\Insights\CreateCensusMetric;
use App\Models\Site\Region;
use App\Traits\AsInsightSender;

class RegionObserver
{
    use AsInsightSender;

    private function sendInsights($model, bool $shouldIncrement)
    {
        CreateCensusMetric::dispatch(
            [
                'model' => [
                    'id' => $model->id,
                    'class' => Region::class,
                ],
                'point' => [
                    'increment' => $shouldIncrement,
                    'measurement' => 'census-region',
                ],
            ]
        );
    }
}
