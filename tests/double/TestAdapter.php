<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 09/11/14
 * Time: 21:14
 */

namespace double;


use Kman\Xmpp\XmppAdapterInterface;

class TestAdapter implements  XmppAdapterInterface
{

    private $messageWasPassed = false;
    private $connected = false;
    /**
     * @var callable
     */
    private $messageHandler;

    public function setMessageHandler(callable $messageHandler)
    {
        $this->messageHandler = $messageHandler;
        $this->messageWasPassed = true;
    }

    /**
     * @return boolean
     */
    public function messageWasPassed()
    {
        return $this->messageWasPassed;
    }

    public function send($who, $message)
    {
        // TODO: Implement send() method.
    }

    public function connect()
    {
        $this->connected = true;
    }

    public function connectWasCalled()
    {
        return $this->connected ;
    }

    public function getAResponseFor($query)
    {
        $callable = $this->messageHandler;
        return $callable($query);
    }

} 