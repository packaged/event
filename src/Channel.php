<?php
namespace Packaged\Event;

use Exception;

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
   * @param string   $eventName
   * @param callable $listener
   *
   * @return $this
   */
  public function listen(string $eventName, callable $listener)
  {
    if(!isset($this->_eventListeners[$eventName]))
    {
      $this->_eventListeners[$eventName] = [];
    }
    $this->_eventListeners[$eventName][] = $listener;
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
   * @param string $eventName
   * @param mixed  ...$data
   *
   * @return \Generator|null
   */
  public function triggerAndConsume(string $eventName, ...$data)
  {
    //Trigger event listeners
    if(isset($this->_eventListeners[$eventName]))
    {
      foreach($this->_eventListeners[$eventName] as $listener)
      {
        try
        {
          yield $listener($this->getName(), $eventName, ...$data);
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
          yield $listener($this->getName(), $eventName, ...$data);
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
   * @param string $eventName
   * @param mixed  ...$data
   *
   * @return $this
   */
  public function trigger(string $eventName, ...$data)
  {
    iterator_to_array($this->triggerAndConsume($eventName, ...$data));
    return $this;
  }
}
