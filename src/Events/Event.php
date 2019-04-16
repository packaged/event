<?php
namespace Packaged\Event\Events;

interface Event
{
  /**
   * Time the event was triggered
   *
   * @return float
   */
  public function getTimestamp();

  /**
   * @return string Event Type
   */
  public function getType();
}
