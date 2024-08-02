<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Monitoring for TYPO3 installations',
    'description' => '',
    'category' => 'be',
    'author' => 'Georg Ringer',
    'author_email' => '',
    'state' => 'stable',
    'version' => '3.0.0',
    'constraints' => [
        'depends' => [
            'php' => '8.2.0-8.4.99',
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
