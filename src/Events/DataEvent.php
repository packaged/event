<?php
namespace Packaged\Event\Events;

class DataEvent extends Event
{
  protected $_data;

  public function __construct(string $type, $data)
  {
    parent::__construct($type);
    $this->_data = $data;
  }

  public function getData()
  {
    return $this->_data;
  }
}
