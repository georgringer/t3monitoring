<?php
namespace T3Monitor\T3monitoring\Service;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class BulletinImport
 */
class BulletinImport
{
    /** @var string */
    protected $url;

    /** @var int */
    protected $limit;

    /**
     * @param string $url
     * @param int $limit
     */
    public function __construct($url, $limit = 10)
    {
        $this->url = $url;
        $this->limit = $limit;
    }

    /**
     * @return array
     */
    public function start()
    {
        $feed = [];
        try {
            /** @var RequestFactory $requestFactory */
            $requestFactory = GeneralUtility::makeInstance(RequestFactory::class);
            $response = $requestFactory->request($this->url);
            if ($response->getStatusCode() == 200) {
                $rss = new \DOMDocument();
                $rss->loadXML($response->getBody()->getContents());

                /** @var \DOMElement $node */
                foreach ($rss->getElementsByTagName('item') as $node) {
                    $feed[] = [
                        'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
                        'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
                        'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
                        'date' => strtotime($node->getElementsByTagName('pubDate')->item(0)->nodeValue),
                    ];
                }
            }
        } catch (\Throwable $e) {
            // do nothing
        }

        return $feed;
    }
}
