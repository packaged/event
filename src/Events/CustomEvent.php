<?php
namespace Packaged\Event\Events;

class CustomEvent extends AbstractEvent
{
  private $_type;

  /**
   * Event constructor.
   *
   * @param string $type Event Type
   */
  public function __construct(string $type)
  {
    parent::__construct();
    $this->_type = $type;
  }

  /**
   * @return string Event Type
   */
  public function getType()
  {
    return $this->_type;
  }
}
