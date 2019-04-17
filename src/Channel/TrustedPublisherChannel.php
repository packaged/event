<?php
namespace Packaged\Event\Channel;

use Packaged\Event\Events\Event;

class TrustedPublisherChannel extends Channel
{
  private $_publisher;

  public function __construct(string $name, $publisher)
  {
    parent::__construct($name);
    $this->_publisher = $publisher;
  }

  protected function _assertPublisher($publisher)
  {
    if($publisher !== $this->_publisher)
    {
      throw new \RuntimeException("You are not permitted to publish to this channel", 403);
    }
  }

  public function triggerAndConsume(Event $event, bool $throwExceptions = false, $publisher = null)
  {
    $this->_assertPublisher($publisher);
    return parent::triggerAndConsume($event, $throwExceptions);
  }

  public function trigger(Event $event, bool $throwExceptions = false, $publisher = null)
  {
    iterator_to_array($this->triggerAndConsume($event, $throwExceptions, $publisher));
    return $this;
  }

}
