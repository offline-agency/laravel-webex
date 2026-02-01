<?php

namespace Offlineagency\LaravelWebex\Tests\Fake\Messages;

class MessagesFakeResponse extends FakeResponse
{
    public function getMessagesFakeList()
    {
        return json_encode((object) [
            'items' => [
                $this->fakeMessage(),
                $this->fakeMessage(),
            ],
        ]);
    }

    public function getMessagesFakeDetail()
    {
        return json_encode($this->fakeMessage());
    }

    public function getMessagesFakeCreate()
    {
        return json_encode($this->fakeMessage(['text' => 'new message']));
    }

    public function getMessagesFakeError()
    {
        return json_encode((object) [
            'message' => 'fake_message',
            'errors' => [],
            'trackingId' => 'fake_trackingId',
        ]);
    }
}
