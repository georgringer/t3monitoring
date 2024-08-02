<?php

declare(strict_types=1);

namespace T3Monitor\T3monitoring\Controller;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Domain\Model\Dto\ClientFilterDemand;
use T3Monitor\T3monitoring\Domain\Model\Dto\EmMonitoringConfiguration;
use T3Monitor\T3monitoring\Domain\Repository\ClientRepository;
use T3Monitor\T3monitoring\Domain\Repository\CoreRepository;
use T3Monitor\T3monitoring\Domain\Repository\StatisticRepository;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Registry;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3Fluid\Fluid\View\ViewInterface;

class BaseController extends ActionController
{
    protected ModuleTemplate $moduleTemplate;

    public function __construct(
        private readonly ModuleTemplateFactory $moduleTemplateFactory,
        protected StatisticRepository $statisticRepository,
        protected ClientRepository $clientRepository,
        protected CoreRepository $coreRepository,
        protected IconFactory $iconFactory,
        protected Registry $registry,
        protected EmMonitoringConfiguration $emConfiguration
    ) {}

    public function initializeAction(): void
    {
        parent::initializeAction();

        $this->moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $this->moduleTemplate->getDocHeaderComponent()->setMetaInformation([]);
        $this->moduleTemplate->setFlashMessageQueue($this->getFlashMessageQueue());

        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $fullJsPath = 'EXT:t3monitoring/Resources/Public/JavaScript';
        $fullJsPath = GeneralUtility::getFileAbsFileName($fullJsPath);
        $fullJsPath = PathUtility::getAbsoluteWebPath($fullJsPath);
        $pageRenderer->addRequireJsConfiguration([
            'paths' => [
                'datatables' => $fullJsPath . '/jquery.dataTables.min',
                'datatablesBootstrap' => $fullJsPath . '/dataTables.bootstrap.min',
            ],
        ]);

        $pageRenderer->loadRequireJsModule('TYPO3/CMS/T3monitoring/Main');
        $pageRenderer->addCssFile('EXT:t3monitoring/Resources/Public/Css/t3monitoring.css');

        $this->createMenu();
        $this->getButtons();
    }

    protected function initializeView(ViewInterface $view)
    {
        $view->assignMultiple([
            'emConfiguration' => $this->emConfiguration,
            'formats' => [
                'date' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'],
                'time' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['hhmm'],
                'dateAndTime' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'] . ' ' . $GLOBALS['TYPO3_CONF_VARS']['SYS']['hhmm'],
            ],
        ]);
    }

    protected function createMenu(): void
    {
        $menu = $this->moduleTemplate->getDocHeaderComponent()->getMenuRegistry()->makeMenu();
        $menu->setIdentifier('t3monitoring');

        $actions = [
            ['controller' => 'Statistic', 'action' => 'index', 'label' => $this->getLabel('home')],
            ['controller' => 'Extension', 'action' => 'list', 'label' => $this->getLabel('extensionList')],
            ['controller' => 'Core', 'action' => 'list', 'label' => $this->getLabel('coreVersions')],
            ['controller' => 'Sla', 'action' => 'list', 'label' => $this->getLabel('sla')],
            ['controller' => 'Tag', 'action' => 'list', 'label' => $this->getLabel('tag')],
            ['controller' => 'Statistic', 'action' => 'administration', 'label' => $this->getLabel('administration')],
        ];

        foreach ($actions as $action) {
            $isActive = match ($action['controller']) {
                'Statistic' => $this->request->getControllerName() === $action['controller']
                    && $this->request->getControllerActionName() === $action['action'],
                default => $this->request->getControllerName() === $action['controller'],
            };

            $item = $menu->makeMenuItem()
                ->setTitle($action['label'])
                ->setHref($this->getUriBuilder()->reset()->uriFor($action['action'], [], $action['controller']))
                ->setActive($isActive);
            $menu->addMenuItem($item);
        }

        $this->moduleTemplate->getDocHeaderComponent()->getMenuRegistry()->addMenu($menu);
    }

    /**
     * Create the panel of buttons for submitting the form or otherwise perform operations.
     */
    protected function getButtons(): void
    {
        $buttonBar = $this->moduleTemplate->getDocHeaderComponent()->getButtonBar();

        // Home
        if (($this->request->getControllerName() !== 'Statistic'
                || $this->request->getControllerActionName() !== 'index')
            || $this->request->hasArgument('filter')
        ) {
            $viewButton = $buttonBar->makeLinkButton()
                ->setTitle($this->getLabel('home'))
                ->setHref($this->getUriBuilder()->reset()->uriFor('index', [], 'Statistic'))
                ->setIcon($this->iconFactory->getIcon('actions-view-go-back', Icon::SIZE_SMALL));
            $buttonBar->addButton($viewButton);
        }

        $uriBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Backend\Routing\UriBuilder::class);

        $queryParams = array_merge(
            $this->request->getQueryParams()['tx_t3monitoring_tools_t3monitoringt3monitor'] ?? [],
            $this->request->getParsedBody()['tx_t3monitoring_tools_t3monitoringt3monitor'] ?? []
        );
        // Buttons for new records
        $returnUrl = rawurlencode((string)$uriBuilder->build('t3monitoring', [
            'tx_t3monitoring_tools_t3monitoringt3monitor' => $queryParams,
        ]));
        $pid = $this->emConfiguration->getPid();

        // new client
        $parameters = GeneralUtility::explodeUrl2Array('edit[tx_t3monitoring_domain_model_client][' . $pid . ']=new&returnUrl=' . $returnUrl);
        $addUserGroupButton = $buttonBar->makeLinkButton()
            ->setHref($uriBuilder->buildUriFromRoute('record_edit', $parameters))
            ->setTitle($this->getLabel('createNew.client'))
            ->setIcon($this->iconFactory->getIcon('actions-document-new',
                Icon::SIZE_SMALL));
        $buttonBar->addButton($addUserGroupButton, ButtonBar::BUTTON_POSITION_LEFT);

        // client single view
        if ($this->request->getControllerActionName() === 'show'
            && $this->request->getControllerName() === 'Client'
        ) {
            // edit client
            $arguments = $this->request->getArguments();
            $clientId = (int)$arguments['client'];
            $parameters = GeneralUtility::explodeUrl2Array('edit[tx_t3monitoring_domain_model_client][' . $clientId . ']=edit&returnUrl=' . $returnUrl);
            $editClientButton = $buttonBar->makeLinkButton()
                ->setHref($uriBuilder->buildUriFromRoute('record_edit', $parameters))
                ->setTitle($this->getLabel('edit.client'))
                ->setIcon($this->iconFactory->getIcon('actions-open',
                    Icon::SIZE_SMALL));
            $buttonBar->addButton($editClientButton, ButtonBar::BUTTON_POSITION_LEFT);

            // fetch client data
            $downloadClientDataButton = $buttonBar->makeLinkButton()
                ->setHref($this->getUriBuilder()->reset()->uriFor('fetch', ['client' => $clientId], 'Client'))
                ->setTitle($this->getLabel('fetchClient.link'))
                ->setIcon($this->iconFactory->getIcon('actions-system-extension-download',
                    Icon::SIZE_SMALL));
            $buttonBar->addButton($downloadClientDataButton, ButtonBar::BUTTON_POSITION_LEFT);
        }
    }

    protected function getLabel(string $key): string
    {
        return $this->getLanguageService()->sL('LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:' . $key);
    }

    protected function getUriBuilder(): UriBuilder
    {
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $uriBuilder->setRequest($this->request);

        return $uriBuilder;
    }

    protected function getClientFilterDemand(): ClientFilterDemand
    {
        return new ClientFilterDemand();
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
