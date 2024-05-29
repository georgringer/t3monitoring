<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Monitoring for TYPO3 installations',
    'description' => '',
    'category' => 'be',
    'author' => 'Georg Ringer',
    'author_email' => '',
    'state' => 'beta',
    'version' => '2.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-12.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'psr-4' => [
            'T3Monitor\\T3monitoring\\' => 'Classes'
        ]
    ],
];
