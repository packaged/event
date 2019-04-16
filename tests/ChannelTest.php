<?php

use Packaged\Event\Channel;
use PHPUnit\Framework\TestCase;

class ChannelTest extends TestCase
{
  public function testChannel()
  {
    $default = null;
    $global = null;

    $channel = new Channel('channel');
    $channel->listen('default', function ($c, $e, ...$data) use (&$default) { $default = $data[0]; });
    $channel->listenChannel(function ($c, $e, ...$data) use (&$global) { $global = $data[0]; });
    $this->assertNull($default);
    $this->assertNull($global);
    $channel->trigger('default', true);
    $this->assertTrue($default);
    $this->assertTrue($global);
    $channel->trigger('random', false);
    $this->assertTrue($default);
    $this->assertFalse($global);
  }

  public function testConsume()
  {
    $channel = new Channel('consume');
    $channel->listenChannel(function ($c, $e, $int) { return $int * 2; });
    $channel->listenChannel(function ($c, $e) { throw new RuntimeException("Hi"); });
    $channel->listen('event', function ($c, $e) { throw new RuntimeException("Hi"); });
    $results = [];
    $exceptionCount = 0;
    foreach($channel->triggerAndConsume('event', 2) as $result)
    {
      $results[] = $result;
      if($result instanceof Exception)
      {
        $exceptionCount++;
      }
    }
    $this->assertEquals(2, $exceptionCount);
    $this->assertTrue(in_array(4, $results, true));
  }
}
