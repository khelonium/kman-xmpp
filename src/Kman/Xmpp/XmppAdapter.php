<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 09/11/14
 * Time: 21:30
 */

namespace Kman\Xmpp;


use XMPPHP_Log;
use XMPPHP_XMPP;

class XmppAdapter implements XmppAdapterInterface
{

    /**
     * @var XMPPHP_XMPP
     */
    private $conn = null;

    /**
     * @var callable
     */
    private $handler = null;
    /**
     * @var
     */
    private $host;
    /**
     * @var
     */
    private $user;
    /**
     * @var
     */
    private $password;
    /**
     * @var
     */
    private $port;

    public function __construct($host, $user, $password, $port)
    {

        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->port = $port;
    }


    public function setMessageHandler(callable $handler)
    {
        $this->handler = $handler;
    }

    /**
     * Starts the client. It is expected that the callback provided in setMessageHandler
     * it is called for each message with $handler($message)
     * @return
     */
    public function connect()
    {
        $this->conn = new XMPPHP_XMPP(
            $this->host,
            $this->port,
            $this->user,
            $this->password,
            'xmpphp',
            null,
            $printlog = true,
            $loglevel = XMPPHP_Log::LEVEL_DEBUG
        );


        $conn = $this->conn;


        $conn->autoSubscribe();

        $conn->connect();

        while (!$conn->isDisconnected()) {
            usleep(150);
            foreach ($this->getPayloads() as $event) {
                $this->processEvent($event);
            }
        }

    }

    /**
     * @param $event
     * @param $conn
     * @param $vcard_request
     */
    private function processEvent($event)
    {

        $conn = $this->conn;
        $pl = $event[1];
        switch ($event[0]) {
            case 'message':
                $this->processMessage($pl);

                break;
            case 'presence':
                print "Presence: {$pl['from']} [{$pl['show']}] {$pl['status']}\n";
                break;
            case 'session_start':
                $this->handleSessionStart($conn);
                break;

        }
    }

    /**
     * @param $conn
     */
    private function handleSessionStart($conn)
    {
        print "Session Start\n";
        $conn->getRoster();
        $conn->presence($status = "Ready to serve!");
    }

    /**
     * @return array
     */
    private function getPayloads()
    {
        return $this->conn->processUntil(array('message', 'presence', 'end_stream', 'session_start'));
    }

    /**
     * @param $eventData =>
     *[
     * 'type' => chat|?
     * 'from' => client qualified name
     * 'body' => the actual message ,
     * 'xml'  => 'xml body'
     *]
     * @param $conn
     */
    private function processMessage($eventData)
    {
        print "---------------------------------------------------------------------------------\n";
        print "Message from: {$eventData['from']}\n";
        if (isset($eventData['subject'])) {
            print "Subject: {$eventData['subject']}\n";
        }
        print $eventData['body'] . "\n";
        print "---------------------------------------------------------------------------------\n";

        if ($this->handler) {
            $this->giveResponseFromHandler($eventData['body'], $eventData['from']);
        } else {
            $this->giveDefaultResponse($eventData);
        }

    }

    /**
     * @param $eventData
     */
    private function giveDefaultResponse($eventData)
    {
        $this->conn->message($eventData['from'], $body = "Thanks for sending me \"{$eventData['body']}\".",
            $type = $eventData['type']);
    }

    private function giveResponseFromHandler($message, $to)
    {
        $callback = $this->handler;

        foreach ($callback($message) as $response) {
                $this->send($to, $response);
        }
    }

    public function send($who, $message)
    {
        $this->conn->message($who, $message);
    }


} 