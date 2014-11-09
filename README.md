kman-xmpp
=========

Following example  shows how kman can be used to communicate via jabber

<pre>
$adapter = new \Kman\Xmpp\XmppAdapter('cosmins-mbp','kman','kman',5222);
$kman = new \Kman\Xmpp\Communicator($adapter);

$brain =  new \Kman\Megahal\Brain();
$kman->setBrain($brain);
$kman->addCommand(new \Kman\Communicator\Command\Uptime());
$kman->connect();
</pre>

