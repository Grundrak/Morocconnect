<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();


        $this->beforeApplicationDestroyed(function () {
            if (DB::transactionLevel() > 0) {
                $this->fail('Database has an open transaction at the end of the test.');
            }
        });
    }
}