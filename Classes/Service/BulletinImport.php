<?php

namespace T3Monitor\T3monitoring\Service;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DOMDocument;

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

    public function start()
    {
        $feed = array();
        try {
            $rss = new DOMDocument();
            $rss->load($this->url);
            foreach ($rss->getElementsByTagName('item') as $node) {
                $feed[] = [
                    'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
                    'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
                    'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
                    'date' => strtotime($node->getElementsByTagName('pubDate')->item(0)->nodeValue),
                ];
            }
        } catch (\Exception $e) {
            // do nothing
        }

        return $feed;
    }
}
