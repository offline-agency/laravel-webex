<?php

namespace Offlineagency\LaravelWebex\Entities\Messages;

use Offlineagency\LaravelWebex\Entities\AbstractEntity;

class Messages extends AbstractEntity
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $parentId;

    /**
     * @var string
     */
    public $roomId;

    /**
     * @var string
     */
    public $roomType;

    /**
     * @var string
     */
    public $text;

    /**
     * @var string
     */
    public $markdown;

    /**
     * @var string
     */
    public $html;

    /**
     * @var array<int, string>|null
     */
    public $files;

    /**
     * @var string
     */
    public $personId;

    /**
     * @var string
     */
    public $personEmail;

    /**
     * @var array<int, string>|null
     */
    public $mentionedPeople;

    /**
     * @var array<int, string>|null
     */
    public $mentionedGroups;

    /**
     * @var array<int, mixed>|null
     */
    public $attachments;

    /**
     * @var string
     */
    public $created;

    /**
     * @var string
     */
    public $updated;

    /**
     * @var bool
     */
    public $isVoiceClip;
}
