<?php

namespace Natsu007\Ntak;

use PHPUnit\Framework\TestCase as FrameworkTestCase;

class TestCase extends FrameworkTestCase
{
    protected $taxNumber = '11223344122';
    protected $regNumber = 'ET23002557';
    protected $softwareRegNumber = 'SKYLON';
    protected $version = '3.2';
    protected $certPath = __DIR__.'/../auth/pem.pem';
}
