<?php
namespace Packaged\Event\Events;

class DataEvent extends CustomEvent
{
  protected $_data;

  public function __construct(string $type = null, $data = null)
  {
    parent::__construct($type);
    $this->_data = $data;
  }

  public function getData()
  {
    return $this->_data;
  }
}
