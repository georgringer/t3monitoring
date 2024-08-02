<?php

declare(strict_types=1);

namespace T3Monitor\T3monitoring\Controller;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Psr\Http\Message\ResponseInterface;
use T3Monitor\T3monitoring\Domain\Model\Dto\ExtensionFilterDemand;
use T3Monitor\T3monitoring\Domain\Repository\ExtensionRepository;
use TYPO3\CMS\Backend\Attribute\AsController;

#[AsController]
class ExtensionController extends BaseController
{
    protected ExtensionRepository $extensionRepository;

    public function injectExtensionRepository(ExtensionRepository $extensionRepository): void
    {
        $this->extensionRepository = $extensionRepository;
    }

    public function listAction(?ExtensionFilterDemand $filter = null): ResponseInterface
    {
        if ($filter === null) {
            $filter = new ExtensionFilterDemand();
        }

        $this->view->assignMultiple([
            'filter' => $filter,
            'extensions' => $this->extensionRepository->findByDemand($filter),
        ]);

        return $this->htmlResponse();
    }

    public function showAction(string $extension = ''): ResponseInterface
    {
        if (empty($extension)) {
            return $this->redirect('list');
        }
        $versions = $this->extensionRepository->findAllVersionsByName($extension);
        $this->view->assignMultiple([
             'versions' => $versions,
            'latest' => $versions->getFirst(),
        ]);
        return $this->htmlResponse();
    }
}
