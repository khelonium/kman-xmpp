<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 09/11/14
 * Time: 20:29
 */

namespace Kman\Xmpp;



use Kman\Communicator\AbstractCommunicator;

class Communicator extends AbstractCommunicator
{
    /**
     * @var XmppAdapterInterface
     */
    private $driver;

    /**
     * @param XmppAdapterInterface $driver
     */
    public function __construct(XmppAdapterInterface $driver)
    {
        parent::__construct();
        $this->driver = $driver;
    }

    /**
     * Starts the communicator.
     * @return bool
     */
    public function connect()
    {
        $that = $this;

        $messageHandler = function ($message) use ($that) {
            return $that->getResponse($message);
        };

        $this->driver->setMessageHandler($messageHandler);

        $this->driver->connect();


    }

} 