<?php
namespace T3Monitor\T3monitoring\Controller;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Domain\Model\Dto\CoreFilterDemand;
use T3Monitor\T3monitoring\Domain\Repository\CoreRepository;

/**
 * CoreController
 */
class CoreController extends BaseController
{

    /**
     * @param CoreFilterDemand|null $filter
     */
    public function listAction(CoreFilterDemand $filter = null)
    {
        if (is_null($filter)) {
            /** @var CoreFilterDemand $filter */
            $filter = $this->objectManager->get(CoreFilterDemand::class);
            $filter->setUsage(CoreRepository::USED_ONLY);
        }

        $this->view->assignMultiple([
            'filter' => $filter,
            'cores' => $this->coreRepository->findByDemand($filter)
        ]);
    }
}
