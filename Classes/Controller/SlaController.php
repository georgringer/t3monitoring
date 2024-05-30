<?php
namespace T3Monitor\T3monitoring\Controller;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Psr\Http\Message\ResponseInterface;
use T3Monitor\T3monitoring\Domain\Model\Sla;

/**
 * SlaController
 */
class SlaController extends BaseController
{

    /**
     * @var \T3Monitor\T3monitoring\Domain\Repository\SlaRepository
     */
    protected $slaRepository = null;

    /**
     * @param \T3Monitor\T3monitoring\Domain\Repository\SlaRepository $slaRepository
     */
    public function injectSlaRepository(\T3Monitor\T3monitoring\Domain\Repository\SlaRepository $slaRepository)
    {
        $this->slaRepository = $slaRepository;
    }

    /**
     * action list
     */
    public function listAction(): ResponseInterface
    {
        $slas = $this->slaRepository->findAll();
        $this->moduleTemplate->assign('slas', $slas);

        return $this->moduleTemplate->renderResponse('List');
    }

    /**
     * action show
     *
     * @param Sla $sla
     */
    public function showAction(Sla $sla = null)
    {
        if ($sla === null) {
            return $this->redirect('index', 'Statistic');
        }

        $demand = $this->getClientFilterDemand();
        $demand->setSla($sla->getUid());
        
        $this->moduleTemplate->assignMultiple([
            'sla' => $sla,
            'clients' => $this->clientRepository->findByDemand($demand)
        ]);

        return $this->moduleTemplate->renderResponse('Show');
    }
}
