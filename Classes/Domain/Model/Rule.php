<?php
namespace T3Monitor\T3monitoring\Domain\Model;

class Rule extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\T3Monitor\T3monitoring\Domain\Model\Check>
     */
    protected $executionCriteria = null;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\T3Monitor\T3monitoring\Domain\Model\Check>
     */
    protected $failureCriteria = null;

    /**
     * @var string
     */
    protected $messageCategory;

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\T3Monitor\T3monitoring\Domain\Model\Check> executionCriteria
     */
    public function getExecutionCriteria()
    {
        return $this->executionCriteria;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\T3Monitor\T3monitoring\Domain\Model\Check> $executionCriteria
     * @return void
     */
    public function setExecutionCriteria(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $executionCriteria)
    {
        $this->executionCriteria = $executionCriteria;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\T3Monitor\T3monitoring\Domain\Model\Check> failureCriteria
     */
    public function getFailureCriteria()
    {
        return $this->failureCriteria;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\T3Monitor\T3monitoring\Domain\Model\Check> $failureCriteria
     * @return void
     */
    public function setFailureCriteria(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $failureCriteria)
    {
        $this->failureCriteria = $failureCriteria;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getMessageCategory(): string
    {
        return $this->messageCategory;
    }

    /**
     * @param string $messageCategory
     */
    public function setMessageCategory(string $messageCategory)
    {
        $this->messageCategory = $messageCategory;
    }
}
