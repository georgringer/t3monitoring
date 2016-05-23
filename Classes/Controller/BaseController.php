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
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\View\BackendTemplateView;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Registry;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Lang\LanguageService;

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
        $this->statisticRepository = $this->objectManager->get(StatisticRepository::class);
        $this->filterDemand = $this->objectManager->get(ClientFilterDemand::class);
        $this->clientRepository = $this->objectManager->get(ClientRepository::class);
        $this->coreRepository = $this->objectManager->get(CoreRepository::class);
        $this->iconFactory = GeneralUtility::makeInstance(IconFactory::class);
        $this->registry = GeneralUtility::makeInstance(Registry::class);
        $this->emConfiguration = GeneralUtility::makeInstance(EmMonitoringConfiguration::class);

        parent::initializeAction();
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
        $pageRenderer->addCssFile(ExtensionManagementUtility::extRelPath('t3monitoring')
            . 'Resources/Public/Css/t3monitoring.css');

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

        // Buttons for new records
        $returnUrl = rawurlencode(BackendUtility::getModuleUrl('tools_T3monitoringT3monitor', [
            'tx_t3monitoring_tools_t3monitoringt3monitor' => [
                'action' => $this->request->getControllerActionName(),
                'controller' => $this->request->getControllerName()
            ]
        ], false, true));
        $pid = $this->emConfiguration->getPid();

        // new client
        $parameters = GeneralUtility::explodeUrl2Array('edit[tx_t3monitoring_domain_model_client][' . $pid . ']=new&returnUrl=' . $returnUrl);
        $addUserGroupButton = $buttonBar->makeLinkButton()
            ->setHref(BackendUtility::getModuleUrl('record_edit', $parameters))
            ->setTitle($this->getLabel('createNew.client'))
            ->setIcon($this->view->getModuleTemplate()->getIconFactory()->getIcon('actions-document-new',
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
                ->setHref(BackendUtility::getModuleUrl('record_edit', $parameters))
                ->setTitle($this->getLabel('edit.client'))
                ->setIcon($this->view->getModuleTemplate()->getIconFactory()->getIcon('actions-open',
                    Icon::SIZE_SMALL));
            $buttonBar->addButton($editClientButton, ButtonBar::BUTTON_POSITION_LEFT);

            // fetch client data
            $arguments = $this->request->getArguments();
            $downloadClientDataButton = $buttonBar->makeLinkButton()
                ->setHref($this->getUriBuilder()->reset()->uriFor('fetch', ['client' => $clientId], 'Client'))
                ->setTitle($this->getLabel('fetchClient.link'))
                ->setIcon($this->view->getModuleTemplate()->getIconFactory()->getIcon('actions-system-extension-download',
                    Icon::SIZE_SMALL));
            $buttonBar->addButton($downloadClientDataButton, ButtonBar::BUTTON_POSITION_LEFT);
        }

        // Configuration
        $configurationLink = BackendUtility::getModuleUrl('tools_ExtensionmanagerExtensionmanager', [
            'tx_extensionmanager_tools_extensionmanagerextensionmanager' => [
                'action' => 'showConfigurationForm',
                'controller' => 'Configuration',
                'extension' => ['key' => 't3monitoring']
            ]
        ]);
        $configurationButton = $buttonBar->makeLinkButton()
            ->setHref($configurationLink . '&returnUrl=' . $returnUrl)
            ->setTitle($this->getLabel('emConfiguration.link'))
            ->setIcon($this->view->getModuleTemplate()->getIconFactory()->getIcon('actions-system-extension-configure',
                Icon::SIZE_SMALL));
        $buttonBar->addButton($configurationButton, ButtonBar::BUTTON_POSITION_RIGHT);
    }

    /**
     * @param string $key
     * @return string
     */
    protected function getLabel($key)
    {
        return $this->getLanguageService()->sL('LLL:EXT:t3monitoring/Resources/Private/Language/locallang.xlf:' . $key);
    }

    /**
     * @return UriBuilder
     */
    protected function getUriBuilder()
    {
        /** @var UriBuilder $uriBuilder */
        $uriBuilder = $this->objectManager->get(UriBuilder::class);
        $uriBuilder->setRequest($this->request);

        return $uriBuilder;
    }

    /**
     * @return ClientFilterDemand
     */
    protected function getClientFilterDemand()
    {
        return $this->objectManager->get(ClientFilterDemand::class);
    }

    /**
     * Returns the LanguageService
     *
     * @return LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }
}
