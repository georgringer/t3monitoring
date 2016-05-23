<?php
namespace T3Monitor\T3monitoring\Controller;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

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
     *
     * @return void
     */
    public function listAction()
    {
        $slas = $this->slaRepository->findAll();
        $this->view->assign('slas', $slas);
    }

    /**
     * action show
     *
     * @param Sla $sla
     * @return void
     */
    public function showAction(Sla $sla = null)
    {
        if ($sla === null) {
            $this->redirect('index', 'Statistic');
        }

        $demand = $this->getClientFilterDemand();
        $demand->setSla($sla->getUid());
        $this->view->assignMultiple([
            'sla' => $sla,
            'clients' => $this->clientRepository->findByDemand($demand)
        ]);
    }
}
