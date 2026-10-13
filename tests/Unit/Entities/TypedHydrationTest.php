<?php

use Offlineagency\LaravelWebex\Entities\Messages\Messages;
use Offlineagency\LaravelWebex\Tests\Fixtures\NumericEntity;

it('fills typed properties from the values Webex returns', function () {
    $message = new Messages(['id' => 'm1', 'files' => ['https://x'], 'isVoiceClip' => true]);

    expect($message->id)->toBe('m1')
        ->and($message->files)->toBe(['https://x'])
        ->and($message->isVoiceClip)->toBeTrue();
});

it('ignores a value whose type differs from the declared one, keeping null', function () {
    $message = new Messages(['id' => 42, 'files' => 'not a list', 'isVoiceClip' => 'yes']);

    expect($message->id)->toBeNull()
        ->and($message->files)->toBeNull()
        ->and($message->isVoiceClip)->toBeNull();
});

it('fills an untyped property with any value (entities defined by applications)', function () {
    expect((new NumericEntity(['untyped' => ['any' => 'value']]))->untyped)->toBe(['any' => 'value']);
});

it('accepts any member of a union type and rejects the rest', function (mixed $value, mixed $expected) {
    expect((new NumericEntity(['count' => $value, 'anything' => $value]))->count)->toBe($expected);
})->with([
    'int' => [3, 3],
    'float' => [2.5, 2.5],
    'null' => [null, null],
    'numeric string' => ['3', null],
    'array' => [[1], null],
]);
