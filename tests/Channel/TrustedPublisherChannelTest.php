<?php

namespace Packaged\Event\Tests\Channel;

use Packaged\Event\Channel\TrustedPublisherChannel;
use Packaged\Event\Events\CustomEvent;
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
    $channel->trigger(new CustomEvent('event'), false, $trusted);
    $this->expectException(RuntimeException::class);
    $channel->trigger(new CustomEvent('imposter'), false, $imposter);
  }
}
