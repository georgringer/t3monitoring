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
use T3Monitor\T3monitoring\Domain\Model\Core;
use T3Monitor\T3monitoring\Domain\Model\Dto\ClientFilterDemand;
use T3Monitor\T3monitoring\Domain\Repository\SlaRepository;
use T3Monitor\T3monitoring\Domain\Repository\TagRepository;
use T3Monitor\T3monitoring\Service\BulletinImport;
use T3Monitor\T3monitoring\Service\Import\ClientImport;
use T3Monitor\T3monitoring\Service\Import\CoreImport;
use T3Monitor\T3monitoring\Service\Import\ExtensionImport;
use TYPO3\CMS\Backend\Attribute\AsController;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;

#[AsController]
class StatisticController extends BaseController
{
    const RSS_URL = 'https://typo3.org/?type=101';

    protected SlaRepository $slaRepository;
    protected TagRepository $tagRepository;

    public function injectSlaRepository(SlaRepository $slaRepository): void
    {
        $this->slaRepository = $slaRepository;
    }

    public function injectTagRepository(TagRepository $tagRepository): void
    {
        $this->tagRepository = $tagRepository;
    }

    public function indexAction(?ClientFilterDemand $filter = null): ResponseInterface
    {
        if (null === $filter) {
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
            $bulletinImport = GeneralUtility::makeInstance(BulletinImport::class, self::RSS_URL, 5);
            $feedItems = $bulletinImport->start();
        }

        $this->view->assignMultiple([
            'filter' => $filter,
            'clients' => $this->clientRepository->findByDemand($filter),
            'coreVersions' => $this->getAllCoreVersions(),
            'coreVersionUsage' => $this->statisticRepository->getUsedCoreVersionCount(),
            'coreVersionUsageJson' => $this->statisticRepository->getUsedCoreVersionCountJson(),
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
            'tagVersions' => $this->tagRepository->findAll(),
            'feedItems' => $feedItems,
            'importTimes' => [
                'client' => $this->registry->get('t3monitoring', 'importClient'),
                'core' => $this->registry->get('t3monitoring', 'importCore'),
                'extension' => $this->registry->get('t3monitoring', 'importExtension'),
            ],
        ]);

        return $this->htmlResponse();
    }

    public function administrationAction(string $import = ''): ResponseInterface
    {
        $success = $error = false;

        if (!empty($import)) {
            switch ($import) {
                case 'clients':
                    $importService = GeneralUtility::makeInstance(ClientImport::class);
                    $importService->run();
                    $success = true;
                    break;
                case 'extensions':
                    $importService = GeneralUtility::makeInstance(ExtensionImport::class);
                    $importService->run();
                    $success = true;
                    break;
                case 'core':
                    $importService = GeneralUtility::makeInstance(CoreImport::class);
                    $importService->run();
                    $success = true;
                    break;
            }
        }

        $this->view->assignMultiple([
            'success' => $success,
            'error' => $error,
        ]);

        return $this->htmlResponse();
    }

    protected function getAllCoreVersions(): array
    {
        $result = $used = [];
        $versions = $this->coreRepository->findAllCoreVersions();
        foreach ($versions as $version) {
            /** @var Core $version */
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
