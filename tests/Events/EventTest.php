<?php
namespace Packaged\Event\Tests\Events;

use Packaged\Event\Events\Event;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
  public function testEvent()
  {
    $initialTime = microtime(true);
    $event = new Event('eventType');
    $this->assertEquals('eventType', $event->getType());
    $this->assertGreaterThan($initialTime, $event->getTimestamp());
  }
}
