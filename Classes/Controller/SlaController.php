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
use T3Monitor\T3monitoring\Domain\Model\Sla;
use T3Monitor\T3monitoring\Domain\Repository\SlaRepository;
use TYPO3\CMS\Backend\Attribute\AsController;

#[AsController]
class SlaController extends BaseController
{
    protected SlaRepository $slaRepository;

    public function injectSlaRepository(SlaRepository $slaRepository): void
    {
        $this->slaRepository = $slaRepository;
    }

    public function listAction(): ResponseInterface
    {
        $slas = $this->slaRepository->findAll();
        $this->view->assign('slas', $slas);
        return $this->htmlResponse();
    }

    public function showAction(?Sla $sla = null): ResponseInterface
    {
        if ($sla === null) {
            return $this->redirect('index', 'Statistic');
        }

        $demand = $this->getClientFilterDemand();
        $demand->setSla($sla->getUid());
        $this->view->assignMultiple([
            'sla' => $sla,
            'clients' => $this->clientRepository->findByDemand($demand)
        ]);

        return $this->htmlResponse();
    }
}
