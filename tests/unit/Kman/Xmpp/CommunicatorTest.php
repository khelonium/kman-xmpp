<?php
use double\TestAdapter;
use Kman\Xmpp\Communicator;

/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 09/11/14
 * Time: 21:05
 */

class CommunicatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var TestAdapter
     */
    private $testAdapter;
    /**
     * @var Kman\Xmpp\Communicator
     */
    private $communicator;

    /**
     * @before
     */
    function weCanConstructIt()
    {
        $this->testAdapter = $testAdapter = new TestAdapter();

        $this->communicator = new Communicator($testAdapter);
    }

    /**
     * @test
     */
    function ifWePassItADriver_theCommunicatorWillProvideACallback()
    {
        $this->communicator->connect();
        $this->assertTrue($this->testAdapter->messageWasPassed());
    }

    /**
     * @test
     */
    function theDriverConnectMethodIsCalled()
    {
        $this->communicator->connect();
        $this->assertTrue($this->testAdapter->connectWasCalled());
    }

    /**
     * @test
     */
    function aDriverIsAbleToUseTheHandlerToGetAResponse()
    {
        $brain = new FooBrain();
        $this->communicator->setBrain($brain);
        $this->communicator->connect();

        $this->testAdapter->getAResponseFor("TestMessage");

        $this->assertTrue($brain->sentenceWasRequested());
    }
}
 