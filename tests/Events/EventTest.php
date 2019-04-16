<?php
namespace Packaged\Event\Tests\Events;

use Packaged\Event\Events\CustomEvent;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
  public function testEvent()
  {
    $initialTime = microtime(true);
    $event = new CustomEvent('eventType');
    $this->assertEquals('eventType', $event->getType());
    $this->assertGreaterThan($initialTime, $event->getTimestamp());
  }
}
