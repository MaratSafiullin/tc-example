<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase as BaseRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;

trait RefreshDatabase
{
    use BaseRefreshDatabase;

    protected function beforeRefreshingDatabase(): void
    {
        if (config('app.disable_db_migration_for_tests')) {
            RefreshDatabaseState::$migrated = true;
        }
    }
}
