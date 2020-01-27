<?php
namespace Packaged\Event\Tests\Channel;

use Packaged\Event\Channel\Channel;
use Packaged\Event\Events\DataEvent;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class ChannelTest extends TestCase
{
  public function testChannel()
  {
    $default = null;
    $global = null;

    $channel = new Channel('channel');
    $channel->listen('default', function (DataEvent $e) use (&$default) { $default = $e->getData(); });
    $channel->listenChannel(function (DataEvent $e) use (&$global) { $global = $e->getData(); });
    $this->assertNull($default);
    $this->assertNull($global);
    $channel->trigger(new DataEvent("default", true));
    $this->assertTrue($default);
    $this->assertTrue($global);
    $channel->trigger(new DataEvent("random", false));
    $this->assertTrue($default);
    $this->assertFalse($global);
  }

  public function testConsume()
  {
    $channel = new Channel('consume');
    $channel->listenChannel(function (DataEvent $e) { return $e->getData() * 2; });
    $channel->listenChannel(function () { throw new \RuntimeException("Hi"); });
    $channel->listen('event', function () { throw new \RuntimeException("Hi"); });
    $results = [];
    $exceptionCount = 0;
    foreach($channel->triggerAndConsume(new DataEvent('event', 2)) as $result)
    {
      $results[] = $result;
      if($result instanceof \Exception)
      {
        $exceptionCount++;
      }
    }
    $this->assertEquals(2, $exceptionCount);
    $this->assertTrue(in_array(4, $results, true));
  }

  public function testHasListeners()
  {
    $channel = new Channel('consume');
    $this->assertFalse($channel->hasListeners(null));
    $this->assertFalse($channel->hasListeners('event'));
    $channel->listenChannel(function (DataEvent $e) { return $e->getData() * 2; });

    $this->assertTrue($channel->hasListeners(null));
    $this->assertFalse($channel->hasListeners('event'));

    $channel->listen('event', function () { throw new \RuntimeException("Hi"); });
    $this->assertTrue($channel->hasListeners(null));
    $this->assertTrue($channel->hasListeners('event'));
  }

  public function testExceptions()
  {
    $channel = new Channel('consume');
    $channel->setShouldThrowExceptions(true);
    $channel->listenChannel(function () { throw new \RuntimeException("Hi"); });
    $this->expectException(RuntimeException::class);
    $channel->trigger(new DataEvent('event'));
  }

  public function testEventExceptions()
  {
    $channel = new Channel('consume');
    $channel->setShouldThrowExceptions(true);
    $channel->listen('event', function () { throw new \RuntimeException("Hi"); });
    $this->expectException(RuntimeException::class);
    $channel->trigger(new DataEvent('event'));
  }
}
