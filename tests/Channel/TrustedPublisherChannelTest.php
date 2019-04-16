<?php

namespace Packaged\Event\Tests\Channel;

use Packaged\Event\Channel\TrustedPublisherChannel;
use Packaged\Event\Events\Event;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use stdClass;

class TrustedPublisherChannelTest extends TestCase
{
  public function testVerifyPublisher()
  {
    $trusted = new stdClass();
    $imposter = new stdClass();
    $channel = new TrustedPublisherChannel('channelName', $trusted);
    $channel->trigger(new Event('event'), $trusted);
    $this->expectException(RuntimeException::class);
    $channel->trigger(new Event('imposter'), $imposter);
  }
}
