<?php

describe('AuthController', function () {
    it('auth route returns 200 and OK', function () {
        $response = $this->get(route('auth'));

        $response->assertStatus(200);
        $response->assertSee('OK');
    });
});
