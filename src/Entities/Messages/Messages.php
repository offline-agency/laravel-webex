<?php

namespace Offlineagency\LaravelWebex\Entities\Messages;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Messages extends AbstractEntity
{
    public ?string $id = null;

    public ?string $parentId = null;

    public ?string $roomId = null;

    public ?string $roomType = null;

    public ?string $text = null;

    public ?string $markdown = null;

    public ?string $html = null;

    /** @var array<mixed>|null */
    public ?array $files = null;

    public ?string $personId = null;

    public ?string $personEmail = null;

    /** @var array<mixed>|null */
    public ?array $mentionedPeople = null;

    /** @var array<mixed>|null */
    public ?array $mentionedGroups = null;

    /** @var array<mixed>|null */
    public ?array $attachments = null;

    public ?string $created = null;

    public ?string $updated = null;

    public ?bool $isVoiceClip = null;
}
