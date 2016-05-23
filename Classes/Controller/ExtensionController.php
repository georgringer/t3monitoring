<?php
namespace T3Monitor\T3monitoring\Controller;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Domain\Model\Dto\ExtensionFilterDemand;

/**
 * ExtensionController
 */
class ExtensionController extends BaseController
{

    /**
     * @var \T3Monitor\T3monitoring\Domain\Repository\ExtensionRepository
     */
    protected $extensionRepository = null;

    /**
     * @param \T3Monitor\T3monitoring\Domain\Repository\ExtensionRepository $extensionRepository
     */
    public function injectExtensionRepository(\T3Monitor\T3monitoring\Domain\Repository\ExtensionRepository $extensionRepository)
    {
        $this->extensionRepository = $extensionRepository;
    }

    /**
     * @param ExtensionFilterDemand $filter
     */
    public function listAction(ExtensionFilterDemand $filter = null)
    {
        if ($filter === null) {
            /** @var ExtensionFilterDemand $filter */
            $filter = $this->objectManager->get(ExtensionFilterDemand::class);
        }

        $this->view->assignMultiple([
            'filter' => $filter,
            'extensions' => $this->extensionRepository->findByDemand($filter)
        ]);
    }

    /**
     * action show
     *
     * @param string $extension
     */
    public function showAction($extension = '')
    {
        if (empty($extension)) {
            $this->redirect('list');
        }
        $versions = $this->extensionRepository->findAllVersionsByName($extension);
        $this->view->assignMultiple([
             'versions' => $versions,
            'latest' => $versions->getFirst(),
        ]);
    }
}
