<?php

namespace Offlineagency\LaravelWebex\Tests\Fake\Messages;

use Offlineagency\LaravelWebex\Tests\Fake\BaseFakeResponse;

class FakeResponse extends BaseFakeResponse
{
    public function fakeMessage(array $params = []): object
    {
        return (object) [
            'id' => $this->value($params, 'id', 'fake_id'),
            'parentId' => $this->value($params, 'parentId', 'fake_parentId'),
            'roomId' => $this->value($params, 'roomId', 'fake_roomId'),
            'roomType' => $this->value($params, 'roomType', 'fake_roomType'),
            'toPersonId' => $this->value($params, 'toPersonId', 'fake_toPersonId'),
            'toPersonEmail' => $this->value($params, 'toPersonEmail', 'fake_toPersonEmail'),
            'text' => $this->value($params, 'text', 'fake_text'),
            'markdown' => $this->value($params, 'markdown', 'fake_markdown'),
            'html' => $this->value($params, 'html', 'fake_html'),
            'files' => $this->value($params, 'files', (object) []),
            'personId' => $this->value($params, 'personId', 'fake_personId'),
            'personEmail' => $this->value($params, 'personEmail', 'fake_personEmail'),
            'mentionedPeople' => $this->value($params, 'mentionedPeople', (object) []),
            'mentionedGroups' => $this->value($params, 'mentionedGroups', (object) []),
            'attachments' => $this->value($params, 'attachments', (object) []),
            'created' => $this->value($params, 'created', 'fake_created'),
            'updated' => $this->value($params, 'updated', 'fake_updated'),
            'isVoiceClip' => $this->value($params, 'isVoiceClip', false),
        ];
    }
}