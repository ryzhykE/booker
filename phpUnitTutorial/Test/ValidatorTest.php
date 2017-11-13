<?php

require_once '/../../server/api/shop/config.php';
require_once '/../../server/api/shop/libs/Validator.php';

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    protected $valid;
    protected function setUp()
    {
        $this->valid = new Validator();
    }
    protected function tearDown()
    {
        $this->valid = NULL;
    }

    //this->assertEquals($res, 'Incorrect Name');


    public function testloginValidFalse()
    {
        $result = $this->valid->loginValid('lo');
        $this->assertFalse($result);
    }
   public function testloginValidTrue()
    {
        $result = $this->valid->loginValid('login');
        $this->assertTrue($result);
    }

    public function testpasswordValidFalse()
    {
        $result = $this->valid->passwordValid('pass');
        $this->assertFalse($result);
    }

    public function testpasswordValidTrue()
    {
        $result = $this->valid->passwordValid('pass123');
        $this->assertTrue($result);
    }





}