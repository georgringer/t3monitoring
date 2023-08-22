<?php

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
use TYPO3\CMS\Backend\View\BackendTemplateView;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Registry;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;

/**
 * Class BaseController
 */
class BaseController extends ActionController
{

    /** @var BackendTemplateView */
    protected $view;

    /** @var StatisticRepository */
    protected $statisticRepository;

    /** @var ClientRepository */
    protected $clientRepository;

    /** @var CoreRepository */
    protected $coreRepository;

    /** @var ClientFilterDemand */
    protected $filterDemand;

    /** @var BackendTemplateView */
    protected $defaultViewObjectName = BackendTemplateView::class;

    /** @var IconFactory */
    protected $iconFactory;

    /** @var Registry */
    protected $registry;

    /** @var EmMonitoringConfiguration */
    protected $emConfiguration;

    /**
     * Initialize action
     */
    public function initializeAction()
    {
        $this->statisticRepository = GeneralUtility::makeInstance(StatisticRepository::class);
        $this->filterDemand = GeneralUtility::makeInstance(ClientFilterDemand::class);
        $this->clientRepository = GeneralUtility::makeInstance(ClientRepository::class);
        $this->coreRepository = GeneralUtility::makeInstance(CoreRepository::class);
        $this->iconFactory = GeneralUtility::makeInstance(IconFactory::class);
        $this->registry = GeneralUtility::makeInstance(Registry::class);
        $this->emConfiguration = GeneralUtility::makeInstance(EmMonitoringConfiguration::class);

        parent::initializeAction();

        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $fullJsPath = 'EXT:t3monitoring/Resources/Public/JavaScript';
        $fullJsPath = GeneralUtility::getFileAbsFileName($fullJsPath);
        $fullJsPath = PathUtility::getAbsoluteWebPath($fullJsPath);
        $pageRenderer->addRequireJsConfiguration([
            'paths' => [
                'datatables' => $fullJsPath . '/jquery.dataTables.min',
                'datatablesBootstrap' => $fullJsPath . '/dataTables.bootstrap.min',
            ]
        ]);
    }

    /**
     * Set up the doc header properly here
     *
     * @param ViewInterface $view
     * @throws \InvalidArgumentException
     */
    protected function initializeView(ViewInterface $view)
    {
        /** @var BackendTemplateView $view */
        parent::initializeView($view);
        $view->getModuleTemplate()->getDocHeaderComponent()->setMetaInformation([]);
        $view->assignMultiple([
            'emConfiguration' => $this->emConfiguration,
            'formats' => [
                'date' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'],
                'time' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['hhmm'],
                'dateAndTime' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'] . ' ' . $GLOBALS['TYPO3_CONF_VARS']['SYS']['hhmm'],
            ]
        ]);

        /** @var PageRenderer $pageRenderer */
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $pageRenderer->loadRequireJsModule('TYPO3/CMS/T3monitoring/Main');
        $pageRenderer->addCssFile('EXT:t3monitoring/Resources/Public/Css/t3monitoring.css');

        $this->createMenu();
        $this->getButtons();
    }

    /**
     * Create menu
     * @throws \InvalidArgumentException
     */
    protected function createMenu()
    {
        $menu = $this->view->getModuleTemplate()->getDocHeaderComponent()->getMenuRegistry()->makeMenu();
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
            switch ($action['controller']) {
                case 'Statistic':
                    $isActive = $this->request->getControllerName() === $action['controller']
                        && $this->request->getControllerActionName() === $action['action'];
                    break;
                default:
                    $isActive = $this->request->getControllerName() === $action['controller'];
            }

            $item = $menu->makeMenuItem()
                ->setTitle($action['label'])
                ->setHref($this->getUriBuilder()->reset()->uriFor($action['action'], [], $action['controller']))
                ->setActive($isActive);
            $menu->addMenuItem($item);
        }

        $this->view->getModuleTemplate()->getDocHeaderComponent()->getMenuRegistry()->addMenu($menu);
    }

    /**
     * Create the panel of buttons for submitting the form or otherwise perform operations.
     * @throws \InvalidArgumentException
     */
    protected function getButtons()
    {
        $buttonBar = $this->view->getModuleTemplate()->getDocHeaderComponent()->getButtonBar();

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

        // Buttons for new records
        $returnUrl = rawurlencode($uriBuilder->buildUriFromRoute('tools_T3monitoringT3monitor', [
            'tx_t3monitoring_tools_t3monitoringt3monitor' => GeneralUtility::_GPmerged('tx_t3monitoring_tools_t3monitoringt3monitor')
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

    /**
     * @param string $key
     * @return string
     */
    protected function getLabel($key): string
    {
        return $this->getLanguageService()->sL('LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:' . $key);
    }

    /**
     * @return UriBuilder
     */
    protected function getUriBuilder(): UriBuilder
    {
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $uriBuilder->setRequest($this->request);

        return $uriBuilder;
    }

    /**
     * @return ClientFilterDemand
     */
    protected function getClientFilterDemand(): ClientFilterDemand
    {
        return GeneralUtility::makeInstance(ClientFilterDemand::class);
    }

    /**
     * Returns the LanguageService
     *
     * @return LanguageService
     */
    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
