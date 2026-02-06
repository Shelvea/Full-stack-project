<?php

test('unauthenticated customer cannot access product fruits')//use pest test
    ->get('user/fruits')
    ->assertRedirect('login');

