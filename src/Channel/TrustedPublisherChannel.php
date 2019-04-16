<?php
namespace Packaged\Event\Channel;

use Packaged\Event\Events\Event;

class TrustedPublisherChannel extends Channel
{
  private $_publisher;

  public function __construct(string $name, object $publisher)
  {
    parent::__construct($name);
    $this->_publisher = $publisher;
  }

  public function verifyPublisher($publisher)
  {
    return $publisher === $this->_publisher;
  }

  public function triggerAndConsume(Event $event, object $publisher = null)
  {
    if(!$this->verifyPublisher($publisher))
    {
      throw new \RuntimeException("You are not permitted to publish to this channel", 403);
    }
    return parent::triggerAndConsume($event);
  }

  public function trigger(Event $event, object $publisher = null)
  {
    iterator_to_array($this->triggerAndConsume($event, $publisher));
    return $this;
  }

}
