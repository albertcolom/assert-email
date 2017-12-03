<?php

namespace Tests\albertcolom\Assert;

use albertcolom\Assert\AssertEmail;
use PHPUnit_Framework_TestCase;

class AssertEmailTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider validEmailDataProvider
     * @param $email
     */
    public  function itShouldReturnTrueWhenCorrectValidateEmail($email)
    {
        $result = AssertEmail::valid($email);
        $this->assertTrue($result);
    }

    public function validEmailDataProvider()
    {
        return [
            ['test@gmail.com'],
            ['_test_@somedomain.com'],
            ['$_#test!@test.com'],
            ['gmail@gmail.com']
        ];
    }

    /**
     * @test
     * @dataProvider inValidEmailDataProvider
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp /Invalid email "([^"]*)"/
     * @param $email
     */
    public  function itShouldThrowInvalidArgumentExceptionWhenGetIncorrectValidateEmail($email)
    {
        AssertEmail::valid($email);
    }

    public function invalidEmailDataProvider()
    {
        return [
            ['test @gmail.com'],
            ['test@somedo?main.com'],
            [' test@test.com '],
            ['gmail@gmail. com'],
            ['gmail@.com']
        ];
    }

    /**
     * @test
     * @dataProvider invalidDomainDataProvider
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp /Incorrect domain name "([^"]*)"/
     * @param $email
     */
    public  function itShouldThrowInvalidArgumentExceptionWhenGetInvalidDomain($email)
    {
        AssertEmail::dns($email);
    }

    public function invalidDomainDataProvider()
    {
        return [
            ['a@-domain.com'],
            ['a@domain--.com'],
            ['a@-domain-.-.com'],
            ['a@domain.000'],
            ['a@do?main.com']
        ];
    }

    /**
     * @test
     */
    public  function itShouldReturnTrueWhenDomainIsAllowed()
    {
        $allowed = ['mysite.com', 'somedomain.xy', 'test.dev'];
        $result = AssertEmail::domainsAllowed('user@somedomain.xy', $allowed);
        $this->assertTrue($result);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * expectedExceptionMessage Domain is not allowed "test@gmail.com"
     */
    public  function itShouldThrowInvalidArgumentExceptionWhenDomainIsNotAllowed()
    {
        $allowed = ['mysite.com', 'somedomain.xy', 'test.dev'];
        AssertEmail::domainsAllowed('test@gmail.com', $allowed);
    }

    /**
     * @test
     */
    public  function itShouldReturnTrueWhenCorrectDNSValidateEmail()
    {
        $result = AssertEmail::dns('test@gmail.com');
        $this->assertTrue($result);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid email "test@fakedomain.com"
     */
    public  function itShouldThrowInvalidArgumentExceptionWhenGetInvalidDomainDNS()
    {
        AssertEmail::dns('test@fakedomain.com');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Temporal email is not allowed "test@yopmail.com"
     */
    public  function itShouldThrowInvalidArgumentExceptionWhenGetTemporalMail()
    {
        AssertEmail::temporalMail('test@yopmail.com');
    }
}
