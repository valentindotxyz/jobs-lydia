<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class LydiaRequestServiceTest extends TestCase
{
    public function testSignatureMatches()
    {
        $this->assertTrue(\App\Services\LydiaRequestService::isRequestSigned([
            'fakeArg1' => 'fakeArg1Value',
            'fakeArg2' => 'fakeArg2Value',
        ], 'fb5aa688692a80ebdf145514796ceccf'));
    }

    public function testSignatureDoesNotMatch()
    {
        $this->assertFalse(\App\Services\LydiaRequestService::isRequestSigned([
            'fakeArg1' => 'fakeArg1Value',
            'fakeArg2' => 'fakeArg2Value',
        ], ''));
    }
}
