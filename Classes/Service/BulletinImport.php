<?php

declare(strict_types=1);

namespace T3Monitor\T3monitoring\Service;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class BulletinImport
{
    public function __construct(protected string $url, protected int $limit = 10) {}

    public function start(): array
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
        } catch (\Throwable) {
            // do nothing
        }

        return $feed;
    }
}
