<?php

namespace Offlineagency\LaravelWebex\Tests\Unit\Messages;

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Messages\Messages;
use Offlineagency\LaravelWebex\LaravelWebex;
use Offlineagency\LaravelWebex\Tests\Fake\Messages\MessagesFakeResponse;
use Offlineagency\LaravelWebex\Tests\TestCase;

class MessagesTest extends TestCase
{
    public function test_messages_list()
    {
        Http::fake([
            'https://webexapis.com/v1/messages*' => Http::response(
                (new MessagesFakeResponse())->getMessagesFakeList()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $messages_list = $laravel_webex->messages()->list('fake_id');

        $this->assertCount(2, $messages_list);

        $single_message = null;

        foreach ($messages_list as $message) {
            $this->assertInstanceOf(Messages::class, $message);
            $single_message = $message;
        }

        $this->assertEquals('fake_id', $single_message->id);
    }
}
