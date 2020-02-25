<?php

return [
    'monitoring:importCore' => [
        'class' => T3Monitor\T3monitoring\Command\ImportCoreCommand::class,
    ],
    'monitoring:importExtensions' => [
        'class' => T3Monitor\T3monitoring\Command\ImportExtensionsCommand::class,
    ],
    'monitoring:importClients' => [
        'class' => T3Monitor\T3monitoring\Command\ImportClientsCommand::class,
    ],
    'monitoring:importAll' => [
        'class' => T3Monitor\T3monitoring\Command\ImportAllCommand::class,
    ],
    'reporting:admin' => [
        'class' => T3Monitor\T3monitoring\Command\ReportAdminCommand::class,
    ],
    'reporting:client' => [
        'class' => T3Monitor\T3monitoring\Command\ReportClientCommand::class,
    ],
];
