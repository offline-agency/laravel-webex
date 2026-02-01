<?php

describe('Events AuthController', function () {
    it('auth route returns 200 and OK', function () {
        $response = $this->get('/auth');

        $response->assertStatus(200);
        $response->assertSee('OK');
    });
});
