<?php

require_once '/../../server/api/shop/config.php';
require_once '/../../server/api/shop/libs/Validator.php';
require_once '/../../server/api/shop/libs/TSingeton.php';
require_once '/../../server/api/shop/libs/Models/DB.php';

require_once '/../../server/api/shop/libs/Models/Models.php';
require_once '/../../server/api/shop/libs/Models/User.php';


require_once 'Db.php';

class UserTest extends \PHPUnit_Framework_TestCase
{
    protected $pdo;
    protected function setUp()
    {
        $this->pdo = new Db();
    }
    protected function tearDown()
    {
        $this->pdo = NULL;
    }

    public function testGetUsersAllUsers()
    {
        $res = \Models\User::findAll();
        $this->assertTrue(count($res) > 0);
    }

    public function testGetUsersOneUsers()
    {
        $res = \Models\User::findByid(1);
        $this->assertTrue(count($res) > 0);
    }

    public function testAddUsersTrue()
    {
        $res = new \Models\User();
        $res->login = 'test';
        $res->pass = 'pass123';
        $res->email = 'test@test.ru';
        $res->hash = 'hash';
        $res->insert();
        $this->pdo->execQuery('DELETE FROM users WHERE login="test"');
    }

}