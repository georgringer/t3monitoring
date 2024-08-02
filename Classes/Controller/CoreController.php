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
use T3Monitor\T3monitoring\Domain\Model\Dto\CoreFilterDemand;
use T3Monitor\T3monitoring\Domain\Repository\CoreRepository;
use TYPO3\CMS\Backend\Attribute\AsController;

#[AsController]
class CoreController extends BaseController
{
    public function listAction(?CoreFilterDemand $filter = null): ResponseInterface
    {
        if ($filter === null) {
            $filter = new CoreFilterDemand();
            $filter->setUsage(CoreRepository::USED_ONLY);
        }

        $this->view->assignMultiple([
            'filter' => $filter,
            'cores' => $this->coreRepository->findByDemand($filter)
        ]);

        return $this->htmlResponse();
    }
}
