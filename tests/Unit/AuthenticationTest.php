<?php

describe('Authentication', function () {
    it('passes auth placeholder', function () {
        expect(true)->toBeTrue();
    });

    it('asserts thrown exception with expect()->toThrow()', function () {
        expect(fn () => throw new \RuntimeException('expected message'))
            ->toThrow(\RuntimeException::class, 'expected message');
    });
});
