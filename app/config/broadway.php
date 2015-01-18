<?php

return [
    'read-model' => 'elastic-search',
    'read-model-connections' => [
        'elastic-search' => [
            'config' => [
                'hosts' => ['10.0.0.1:9200']
            ],
            'index' => 'read-model'
        ],
    ],
];
