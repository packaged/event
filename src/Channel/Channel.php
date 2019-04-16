<?php
namespace Packaged\Event\Channel;

use Exception;
use Packaged\Event\Events\Event;

class Channel
{
  protected $_channelName;

  protected $_eventListeners = [];
  protected $_channelListeners = [];

  public function __construct(string $name)
  {
    $this->_channelName = $name;
  }

  /**
   * @return string Channel Name
   */
  public function getName()
  {
    return $this->_channelName;
  }

  /**
   * Listen to a specific event triggered on the channel
   *
   * @param string   $eventType
   * @param callable $listener
   *
   * @return $this
   */
  public function listen(string $eventType, callable $listener)
  {
    if(!isset($this->_eventListeners[$eventType]))
    {
      $this->_eventListeners[$eventType] = [];
    }
    $this->_eventListeners[$eventType][] = $listener;
    return $this;
  }

  /**
   * Listen to events triggered on the channel
   *
   * @param callable $listener
   *
   * @return $this
   */
  public function listenChannel(callable $listener)
  {
    $this->_channelListeners[] = $listener;
    return $this;
  }

  /**
   * Trigger an event, and consume the responses
   *
   * @param Event $event
   *
   * @return \Generator|null
   */
  public function triggerAndConsume(Event $event)
  {
    //Trigger event listeners
    if(isset($this->_eventListeners[$event->getType()]))
    {
      foreach($this->_eventListeners[$event->getType()] as $listener)
      {
        try
        {
          yield $listener($event, $this->getName());
        }
        catch(Exception $e)
        {
          yield $e;
        }
      }
    }

    //Trigger channel listeners
    if(isset($this->_channelListeners))
    {
      foreach($this->_channelListeners as $listener)
      {
        try
        {
          yield $listener($event, $this->getName());
        }
        catch(Exception $e)
        {
          yield $e;
        }
      }
    }
    return null;
  }

  /**
   * Trigger an event, and ignore the result
   *
   * @param Event $event
   *
   * @return $this
   */
  public function trigger(Event $event)
  {
    iterator_to_array($this->triggerAndConsume($event));
    return $this;
  }
}
