<?php

namespace Offlineagency\LaravelWebex\Entities\Meetings;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class MeetingTranscripts extends AbstractEntity
{
    public mixed $id = null;

    public mixed $downloadUrl = null;

    public mixed $text = null;

    public mixed $meetingId = null;

    public mixed $topic = null;
}
