<?php

namespace T3Monitor\T3monitoring\Tests\Unit\Service\Impport;

use T3Monitor\T3monitoring\Service\Import\ClientImport;
use TYPO3\CMS\Core\Tests\UnitTestCase;

class ClientImportTest extends UnitTestCase
{

    /**
     * @param string $given
     * @param string $expected
     * @dataProvider domainIsCorrectlyUnifiedProvider
     * @test
     */
    public function domainIsCorrectlyUnified($given, $expected)
    {
        $mockedClientImport = $this->getAccessibleMock(ClientImport::class, ['dummy'], [], '', false);
        $this->assertEquals($expected, $mockedClientImport->_call('unifyDomain', $given));
    }

    /**
     * @return array
     */
    public function domainIsCorrectlyUnifiedProvider()
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
