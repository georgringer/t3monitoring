<?php

namespace T3Monitor\T3monitoring\Controller;

use T3Monitor\T3monitoring\Domain\Repository\TaskRepository;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * TaskController
 */
class TaskController extends BaseController
{
    /**
     * The task repository
     *
     * @var TaskRepository
     */
    protected $taskRepository = null;

    public function injectTaskRepository(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * List action shows all tasks
     *
     * @return void
     */
    protected function listAction(): void
    {
        $this->view->assignMultiple([
            'tasks' => $this->taskRepository->findAll()
        ]);
    }
}
