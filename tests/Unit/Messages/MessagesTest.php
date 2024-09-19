<?php

namespace Offlineagency\LaravelWebex\Tests\Unit\Messages;

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\LaravelWebex;
use Offlineagency\LaravelWebex\Tests\Fake\Messages\MessagesFakeResponse;
use Offlineagency\LaravelWebex\Tests\TestCase;

class MessagesTest extends TestCase
{
    public function test_messages_list()
    {
        Http::fake([
            'messages' => Http::response(
                (new MessagesFakeResponse())->getMessagesFakeList()
            ),
        ]);

        $laravel_webex = new LaravelWebex();
        $messages_list = $laravel_webex->messages()->list('fake_id');

        dd($messages_list);
    }
}
