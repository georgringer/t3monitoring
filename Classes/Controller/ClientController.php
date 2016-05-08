<?php
namespace T3Monitor\T3monitoring\Controller;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Domain\Model\Client;

/**
 * ClientController
 */
class ClientController extends BaseController
{

    /**
     * action show
     *
     * @param Client $client
     * @ignorevalidation $client
     * @return void
     */
    public function showAction(Client $client = null)
    {
        if (is_null($client)) {
            // @todo flash message
            $this->redirect('index', 'Statistic');
        }

        $this->view->assignMultiple([
            'client' => $client,
        ]);
    }
}
