<?php

namespace Offlineagency\LaravelWebex\Entities\Meetings;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class MeetingTranscripts extends AbstractEntity
{
    public $id;

    public $downloadUrl;

    public $text;

    public $meetingId;

    public $topic;
}
