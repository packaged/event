<?php
namespace Packaged\Event\Events;

class Event
{
  private $_type;
  private $_timestamp;

  /**
   * Event constructor.
   *
   * @param string $type Event Type
   */
  public function __construct(string $type)
  {
    $this->_timestamp = microtime(true);
    $this->_type = $type;
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

  /**
   * @return string Event Type
   */
  public function getType()
  {
    return $this->_type;
  }
}
