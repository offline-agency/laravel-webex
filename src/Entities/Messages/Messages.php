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
     * @var array
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
     * @var array
     */
    public $mentionedPeople;

    /**
     * @var array
     */
    public $mentionedGroups;

    /**
     * @var array
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