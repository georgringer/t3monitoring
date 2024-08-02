<?php

declare(strict_types=1);

namespace T3Monitor\T3monitoring\Tests\Unit\Domain\Service\Import;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use T3Monitor\T3monitoring\Service\Import\ClientImport;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class ClientImportTest extends UnitTestCase
{
    #[Test]
    #[DataProvider('domainIsCorrectlyUnifiedProvider')]
    public function domainIsCorrectlyUnified(string $given, string $expected)
    {
        $mockedClientImport = $this->getAccessibleMock(ClientImport::class, ['dummy'], [], '', false);
        $this->assertEquals($expected, $mockedClientImport->_call('unifyDomain', $given));
    }

    public static function domainIsCorrectlyUnifiedProvider(): array
    {
        return [
            'domainWithProtocolAndEndSlash' => [
                'http://www.domain.com/',
                'http://www.domain.com'
            ],
            'domainWithHttpsProtocol' => [
                'https://www.domain2.com',
                'https://www.domain2.com'
            ],
            'domainWithoutProtocol' => [
                'domain3.at',
                'http://domain3.at'
            ],
        ];
    }
}
