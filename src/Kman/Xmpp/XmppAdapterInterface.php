<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 09/11/14
 * Time: 21:12
 */

namespace Kman\Xmpp;


interface XmppAdapterInterface
{
    public function setMessageHandler(callable $handler);

    /**
     * Starts the client. It is expected that the callback provided in setMessageHandler
     * it is called for each message with $handler($message)
     * @return
     */
    public function connect();

    public function send($who, $message);
} 