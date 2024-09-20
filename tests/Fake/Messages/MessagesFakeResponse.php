<?php

namespace Offlineagency\LaravelWebex\Tests\Fake\Messages;

class MessagesFakeResponse extends FakeResponse
{
    public function getMessagesFakeList()
    {
        return json_encode((object)[
            'items' => [
                $this->fakeMessage(),
                $this->fakeMessage(),
            ],
        ]);
    }
}
