<?php

namespace T3Monitor\T3monitoring\ViewHelpers;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Domain\Model\Extension;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

class AvailableUpdatesViewHelper extends AbstractViewHelper
{
    /**
     * For CMS 8
     *
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * @param Extension $extension
     * @param string $as
     * @return string
     */
    public function render(Extension $extension, $as = 'list')
    {
        $versions = [
            'bugfix' => $extension->getLastBugfixRelease(),
            'minor' => $extension->getLastMinorRelease(),
            'major' => $extension->getLastMajorRelease()
        ];

        $result = [];
        foreach ($versions as $name => $version) {
            if (!empty($version) && $extension->getVersion() !== $version && !isset($result[$version])) {
                $result[$version] = [
                    'name' => $name,
                    'version' => $version,
                    'serializedDependencies' => $this->getDependenciesOfExtensionVersion($extension->getName(), $version),
                ];
            }
        }
        $this->templateVariableContainer->add($as, $result);
        $output = $this->renderChildren();
        $this->templateVariableContainer->remove($as);

        return $output;
    }

    /**
     * @param string $name
     * @param string $version
     * @return mixed
     */
    protected function getDependenciesOfExtensionVersion($name, $version)
    {
        $table = 'tx_t3monitoring_domain_model_extension';
        $where = sprintf('name=%s AND version=%s',
            $this->getDatabase()->fullQuoteStr($name, $table),
            $this->getDatabase()->fullQuoteStr($version, $table)
        );
        $row = $this->getDatabase()->exec_SELECTgetSingleRow(
            'serialized_dependencies',
            $table,
            $where);
        return $row['serialized_dependencies'];
    }

    /**
     * @return DatabaseConnection
     */
    protected function getDatabase()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
