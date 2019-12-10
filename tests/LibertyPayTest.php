<?php

declare(strict_types=1);

namespace Premier\Payment\Tests;

use PHPUnit\Framework\TestCase;
use Premier\Payment\LibertyPay;

class LibertyPayTest extends TestCase
{
    public function setUp(): void
    {
    }

    protected function tearDown(): void
    {
    }

    public function testInit()
    {
        $object = $this->getMockBuilder(LibertyPay::class)
            ->setConstructorArgs(['foo', 'bar', 800, 500])
            ->setMethods(null)
            ->getMock();

        $this->assertEquals(LibertyPay::DEFAULT_GATEWAY, $object->getGatewayUrl());
        $this->assertIsString($object->getSignature());
        $this->assertEquals(128, strlen($object->getSignature()));
        $this->assertIsArray($object->getFormFields());
        $this->assertEquals(
            [
                'merchantID' => 'foo',
                'action' => 'SALE',
                'type' => 1,
            ],
            $object->getFormFields()
        );
    }

    public function testFilled()
    {
        $data =  [
            'merchantID' => 'foo',
            'action' => 'PREAUTH',
            'type' => 2,
            'amount' => 1000,
            'transactionUnique' => 'foo bar',
            'orderRef' => 'bar foo',
            'captureDelay' => 5,
            'callbackURL' => 'http://foo.bar',
            'redirectURL' => 'http://bar.foo',
        ];

        $object = $this->getMockBuilder(LibertyPay::class)
            ->setConstructorArgs(['foo', 'bar', 800, 500])
            ->setMethods(null)
            ->getMock();

        $object
            ->setAmount($data['amount'])
            ->setAction($data['action'])
            ->setType($data['type'])
            ->setTransactionUnique($data['transactionUnique'])
            ->setOrderRef($data['orderRef'])
            ->setCaptureDelay($data['captureDelay'])
            ->setCallbackURL($data['callbackURL'])
            ->setRedirectURL($data['redirectURL'])
        ;


        $this->assertIsArray($object->getFormFields());
        $this->assertIsString($object->getSignature());
        $this->assertEquals(128, strlen($object->getSignature()));
        $this->assertEquals($data, $object->getFormFields());
        $this->assertEquals(json_encode($data), json_encode($object));
    }

    public function testSignature()
    {
        $data =  [
            'a' => 'b',
            'c' => 'd',
        ];

        $object = $this->getMockBuilder(LibertyPay::class)
            ->setConstructorArgs(['foo', 'bar', 800, 500])
            ->setMethods(null)
            ->getMock();

        $this->assertEquals('bab68407bd842c973f37666cb2e387a340b4a977a383473472ad3d78bf3c8d10968c0faf4d3866ecbfac1843f7162483c560141508f2ef382652c406e468ca45', $object->buildSignature($data));
    }
}
