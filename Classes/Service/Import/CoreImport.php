<?php
namespace T3Monitor\T3monitoring\Service\Import;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Service\DataIntegrity;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;

/**
 * Class CoreImport
 */
class CoreImport extends BaseImport
{

    const TYPE_REGULAR = 0;
    const TYPE_RELEASE = 1;
    const TYPE_SECURITY = 2;
    const TYPE_DEVELOPMENT = 4;

    const URL = 'https://get.typo3.org/json';
    const MINIMAL_TYPO3_VERSION = '4.5.0';

    /**
     * Run
     * @throws \InvalidArgumentException
     */
    public function run()
    {
        $table = 'tx_t3monitoring_domain_model_core';
        $data = $this->getSimplifiedData();
        $previousCoreVersions = $this->getDatabaseConnection()->exec_SELECTgetRows(
            'uid,version',
            $table,
            '1=1',
            '',
            '',
            '',
            'version'
        );

        $this->getDatabaseConnection()->sql_query('START TRANSACTION');

        foreach ($data as $item) {
            $version = $item['version'];

            $item['pid'] = $this->emConfiguration->getPid();
            $item['tstamp'] = $GLOBALS['EXEC_TIME'];

            // update
            if (isset($previousCoreVersions[$version])) {
                $this->getDatabaseConnection()->exec_UPDATEquery(
                    $table,
                    'uid=' . $previousCoreVersions[$version]['uid'],
                    $item
                );
            } else {
                $item['crdate'] = $GLOBALS['EXEC_TIME'];
                $this->getDatabaseConnection()->exec_INSERTquery(
                    $table,
                    $item
                );
            }
        }
        $this->getDatabaseConnection()->sql_query('COMMIT');

        /** @var DataIntegrity $dataIntegrity */
        $dataIntegrity = GeneralUtility::makeInstance(DataIntegrity::class);
        $dataIntegrity->invokeAfterCoreImport();
        $this->setImportTime('core');
    }

    /**
     * @return array
     */
    protected function getSimplifiedData()
    {
        $data = [];
        $import = $this->getRawData();
        $minimalTypo3Version = VersionNumberUtility::convertVersionNumberToInteger(self::MINIMAL_TYPO3_VERSION);

        foreach ($import as $major => $minor) {
            if ((int)$major >= 4) {
                $latest = $minor['latest'];
                $stable = $minor['stable'];
                $active = $minor['active'];

                foreach ($minor['releases'] as $release) {
                    $version = $release['version'];
                    $versionAsInt = VersionNumberUtility::convertVersionNumberToInteger($version);

                    if ($versionAsInt < $minimalTypo3Version) {
                        continue;
                    }

                    $data[$version] = [
                        'version' => $version,
                        'version_integer' => $versionAsInt,
                        'release_date' => $this->getReleaseDate($release['date']),
                        'type' => $this->getType($release['type']),
                        'latest' => $latest,
                        'is_latest' => $latest === $version,
                        'is_stable' => $stable === $version,
                        'is_active' => $active && $release['type'] !== 'development',
                        'is_official' => 1,
                        'insecure' => 0,
                        'stable' => $stable,
                    ];
                }
            }
        }

        $this->addInsecureFlag($data);

        return $data;
    }

    /**
     * If version 7.6.1 has been a security release, also mark version 7.6.0 as insecure
     *
     * @param array $releases
     */
    protected function addInsecureFlag(array &$releases)
    {
        // mark all as insecure which are not maintained
        foreach ($releases as &$release) {
            if (!$release['is_active']) {
                $release['insecure'] = 1;
                $release['next_secure_version'] = ''; // @todo: next major?
            }
        }

        $listOfInsecureAndActiveVersions = [];
        foreach ($releases as $version => $release) {
            if ($release['is_active'] && $release['type'] === self::TYPE_SECURITY) {
                $listOfInsecureAndActiveVersions[] = $version;
            }
        }
        foreach ($listOfInsecureAndActiveVersions as $version) {
            $split = explode('.', $version);
            if (count($split) !== 3) {
                continue; // @todo: what to do
            }

            for ($i = 0; $i < $split[2]; $i++) {
                $computedVersion = sprintf('%s.%s.%s', $split[0], $split[1], $i);
                $releases[$computedVersion]['insecure'] = 1;
                $releases[$computedVersion]['next_secure_version'] = $version;
            }
        }
    }

    /**
     * @param string $date
     * @return string
     */
    protected function getReleaseDate($date)
    {
        $converted = new \DateTime($date);
        return $converted->format('Y-m-d H:i:s');
    }

    /**
     * @param string $type
     * @return int
     * @throws \UnexpectedValueException
     */
    protected function getType($type)
    {
        switch ($type) {
            case 'regular':
                $id = self::TYPE_REGULAR;
                break;
            case 'release':
                $id = self::TYPE_RELEASE;
                break;
            case 'security':
                $id = self::TYPE_SECURITY;
                break;
            case 'development':
                $id = self::TYPE_DEVELOPMENT;
                break;
            default:
                throw new \UnexpectedValueException(sprintf('Not known type "%s" found', $type));
        }
        return $id;
    }

    /**
     * @return mixed
     * @throws \UnexpectedValueException
     */
    protected function getRawData()
    {
        $content = GeneralUtility::getUrl(self::URL);
        if (empty($content)) {
            throw new \UnexpectedValueException('JSON could not be downloaded');
        }

        return json_decode($content, true);
    }
}
