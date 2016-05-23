<?php
namespace T3Monitor\T3monitoring\Controller;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Domain\Model\Dto\ClientFilterDemand;
use T3Monitor\T3monitoring\Domain\Repository\ClientRepository;
use T3Monitor\T3monitoring\Domain\Repository\CoreRepository;
use T3Monitor\T3monitoring\Domain\Repository\SlaRepository;
use T3Monitor\T3monitoring\Domain\Repository\StatisticRepository;
use T3Monitor\T3monitoring\Service\BulletinImport;
use T3Monitor\T3monitoring\Service\Import\ClientImport;
use T3Monitor\T3monitoring\Service\Import\CoreImport;
use T3Monitor\T3monitoring\Service\Import\ExtensionImport;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;

/**
 * Class StatisticController
 */
class StatisticController extends BaseController
{

    /**
     * @var \T3Monitor\T3monitoring\Domain\Repository\SlaRepository
     */
    protected $slaRepository = null;

    /**
     * Initialize action
     */
    public function initializeAction()
    {
        $this->statisticRepository = $this->objectManager->get(StatisticRepository::class);
        $this->filterDemand = $this->objectManager->get(ClientFilterDemand::class);
        $this->clientRepository = $this->objectManager->get(ClientRepository::class);
        $this->coreRepository = $this->objectManager->get(CoreRepository::class);
        $this->slaRepository = $this->objectManager->get(SlaRepository::class);

        parent::initializeAction();
    }

    /**
     * Index action
     *
     * @param ClientFilterDemand|null $filter
     */
    public function indexAction(ClientFilterDemand $filter = null)
    {
        if (is_null($filter)) {
            $filter = $this->getClientFilterDemand();
            $this->view->assign('showIntro', true);
        } else {
            $this->view->assign('showSearch', true);
        }

        $errorMessageDemand = $this->getClientFilterDemand()->setWithErrorMessage(true);
        $insecureExtensionsDemand = $this->getClientFilterDemand()->setWithInsecureExtensions(true);
        $insecureCoreDemand = $this->getClientFilterDemand()->setWithInsecureCore(true);
        $outdatedCoreDemand = $this->getClientFilterDemand()->setWithOutdatedCore(true);
        $outdatedExtensionDemand = $this->getClientFilterDemand()->setWithOutdatedExtensions(true);
        $clientsWithWarningInfo = $this->getClientFilterDemand()->setWithExtraWarning(true);
        $clientsWithDangerInfo = $this->getClientFilterDemand()->setWithExtraDanger(true);
        $emptyClientDemand = $this->getClientFilterDemand();

        $feedItems = null;
        if ($this->emConfiguration->getLoadBulletins()) {
            /** @var BulletinImport $bulletinImport */
            $bulletinImport = GeneralUtility::makeInstance(BulletinImport::class,
                'https://typo3.org/xml-feeds/security/1/rss.xml', 5);
            $feedItems = $bulletinImport->start();
        }

        $this->view->assignMultiple([
            'filter' => $filter,
            'clients' => $this->clientRepository->findByDemand($filter),
            'coreVersions' => $this->getAllCoreVersions(),
            'coreVersionUsage' => $this->statisticRepository->getUsedCoreVersionCount(),
            'fullClientCount' => $this->clientRepository->countByDemand($emptyClientDemand),
            'clientsWithErrorMessages' => $this->clientRepository->countByDemand($errorMessageDemand),
            'clientsWithInsecureExtensions' => $this->clientRepository->countByDemand($insecureExtensionsDemand),
            'clientsWithOutdatedExtensions' => $this->clientRepository->countByDemand($outdatedExtensionDemand),
            'clientsWithInsecureCore' => $this->clientRepository->countByDemand($insecureCoreDemand),
            'clientsWithOutdatedCore' => $this->clientRepository->countByDemand($outdatedCoreDemand),
            'clientsWithWarningInfo' => $this->clientRepository->countByDemand($clientsWithWarningInfo),
            'clientsWithDangerInfo' => $this->clientRepository->countByDemand($clientsWithDangerInfo),
            'numberOfClients' => $this->clientRepository->countAll(),
            'slaVersions' => $this->slaRepository->findAll(),
            'feedItems' => $feedItems,
            'importTimes' => [
                'client' => $this->registry->get('t3monitoring', 'importClient'),
                'core' => $this->registry->get('t3monitoring', 'importCore'),
                'extension' => $this->registry->get('t3monitoring', 'importExtension'),
            ],
        ]);
    }

    /**
     * Administrator action
     *
     * @param string $import
     */
    public function administrationAction($import = '')
    {
        $success = $error = false;

        if (!empty($import)) {
            switch ($import) {
                case 'clients':
                    /** @var ClientImport $importService */
                    $importService = $this->objectManager->get(ClientImport::class);
                    $importService->run();
                    $success = true;
                    break;
                case 'extensions':
                    /** @var ExtensionImport $importService */
                    $importService = $this->objectManager->get(ExtensionImport::class);
                    $importService->run();
                    $success = true;
                    break;
                case 'core':
                    /** @var CoreImport $importService */
                    $importService = $this->objectManager->get(CoreImport::class);
                    $importService->run();
                    $success = true;
                    break;
            }
        }

        $this->view->assignMultiple([
            'success' => $success,
            'error' => $error
        ]);
    }

    /**
     * Get all core versions
     *
     * @return array
     */
    protected function getAllCoreVersions()
    {
        $result = $used = [];
        $versions = $this->coreRepository->findAllCoreVersions(CoreRepository::USED_ONLY);
        foreach ($versions as $version) {
            /** @var \T3Monitor\T3monitoring\Domain\Model\Core $version */
            $info = VersionNumberUtility::convertVersionStringToArray($version->getVersion());
            $branchVersion = $info['version_main'] . '.' . $info['version_sub'];
            if (!isset($used[$branchVersion])) {
                $key = $info['version_main'] . '.' . $info['version_sub'];

                $result[$key] = $branchVersion;
                $used[$branchVersion] = true;
            }
            $result[$version->getVersion()] = '- ' . $version->getVersion();
        }
        return $result;
    }
}
