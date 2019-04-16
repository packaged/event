<?php
namespace Packaged\Event\Events;

abstract class AbstractEvent implements Event
{
  private $_timestamp;

  /**
   * Event constructor.
   */
  public function __construct()
  {
    $this->_timestamp = microtime(true);
  }

  /**
   * Time the event was triggered
   *
   * @return float
   */
  public function getTimestamp()
  {
    return $this->_timestamp;
  }
}
