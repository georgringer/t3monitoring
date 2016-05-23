<?php
namespace T3Monitor\T3monitoring\Controller;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Domain\Model\Client;
use T3Monitor\T3monitoring\Service\Import\ClientImport;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * ClientController
 */
class ClientController extends BaseController
{

    /**
     * Show client
     *
     * @param Client $client
     * @ignorevalidation $client
     * @return void
     */
    public function showAction(Client $client = null)
    {
        if ($client === null) {
            // @todo flash message
            $this->redirect('index', 'Statistic');
        }

        $this->view->assignMultiple([
            'client' => $client,
        ]);
    }

    /**
     * Fetch client
     *
     * @param Client $client
     * @ignorevalidation $client
     * @return void
     */
    public function fetchAction(Client $client = null)
    {
        if ($client === null) {
            // @todo flash message
            $this->redirect('index', 'Statistic');
        } else {
            /** @var ClientImport $import */
            $import = GeneralUtility::makeInstance(ClientImport::class);
            $import->run($client->getUid());
            $this->addFlashMessage($this->getLabel('fetchClient.success'));
            $this->redirect('show', null, null, ['client' => $client]);
        }
    }
}