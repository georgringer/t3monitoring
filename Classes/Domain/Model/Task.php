<?php
namespace T3Monitor\T3monitoring\Domain\Model;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Task
 */
class Task extends AbstractEntity
{
    /**
     * The uid of the scheduled task on the client
     *
     * @var int
     */
    protected $clientTaskUid = 0;

    /**
     * The scheduled task description
     *
     * @var string
     */
    protected $description = '';

    /**
     * The next execution timestamp
     *
     * @var int
     */
    protected $nextexecution = 0;

    /**
     * The last execution timestamp
     *
     * @var int
     */
    protected $lastexecution = 0;

    /**
     * The last execution failure error message
     *
     * @var string
     */
    protected $lastexecutionFailure = '';

    /**
     * The last execution context
     *
     * @var string
     */
    protected $lastexecutionContext = '';

    /**
     * The attached client
     *
     * @var Client
     */
    protected $client = null;

    /**
     * Get the uid of the tasks on the client system.
     *
     * @return int
     */
    public function getClientTaskUid(): int
    {
        return $this->clientTaskUid;
    }

    /**
     * Set the uid of the task on the client system.
     *
     * @param int $clientTaskUid The uid of the tasks on the client system.
     *
     * @return void
     */
    public function setClientTaskUid(int $clientTaskUid): void
    {
        $this->clientTaskUid = $clientTaskUid;
    }

    /**
     * Get the task description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set the task description.
     *
     * @param string $description The task description.
     *
     * @return void
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Get the next execution timestamp
     *
     * @return int
     */
    public function getNextexecution(): int
    {
        return $this->nextexecution;
    }

    /**
     * Set the next execution timestamp
     *
     * @param int $nextexecution The next execution timestamp
     *
     * @return void
     */
    public function setNextexecution(int $nextexecution): void
    {
        $this->nextexecution = $nextexecution;
    }

    /**
     * Get the last execution timestamp
     *
     * @return int
     */
    public function getLastexecution(): int
    {
        return $this->lastexecution;
    }

    /**
     * Set the last execution timestamp
     *
     * @param int $lastexecution The last execution timestamp
     *
     * @return void
     */
    public function setLastexecution(int $lastexecution): void
    {
        $this->lastexecution = $lastexecution;
    }

    /**
     * Get the last execution failure error message
     *
     * @return string
     */
    public function getLastexecutionFailure(): string
    {
        return $this->lastexecutionFailure;
    }

    /**
     * Set the last execution failure error message
     *
     * @param string $lastexecutionFailure The last execution failure error message
     *
     * @return void
     */
    public function setLastexecutionFailure(string $lastexecutionFailure): void
    {
        $this->lastexecutionFailure = $lastexecutionFailure;
    }

    /**
     * Get the last execution context
     *
     * @return string
     */
    public function getLastexecutionContext(): string
    {
        return $this->lastexecutionContext;
    }

    /**
     * Set the last execution context
     *
     * @param string $lastexecutionContext The last execution context
     *
     * @return void
     */
    public function setLastexecutionContext(string $lastexecutionContext): void
    {
        $this->lastexecutionContext = $lastexecutionContext;
    }

    /**
     * Get the attached client
     *
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Set the attached client
     *
     * @param Client $client The attached client
     *
     * @return void
     */
    public function setClient(Client $client): void
    {
        $this->client = $client;
    }
}
