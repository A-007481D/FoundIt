<?php

return [
    // weights for each matching criterion, max sum should equal 1
    'weights' => [
        'category' => 0.3,
        'location' => 0.2,
        'timeframe' => 0.2,
        'description' => 0.3,
    ],

    // min score to consider a match
    'threshold' => 0.7,

    'timeframe_window_days' => 30,

    'location_radius_km' => 0.5,
];
