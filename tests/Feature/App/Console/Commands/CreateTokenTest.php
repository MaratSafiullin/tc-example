<?php

namespace Tests\Feature\App\Console\Commands;

use App\Console\Commands\CreateToken;
use App\Models\User;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\RefreshDatabase;
use Tests\TestCase;

#[CoversClass(CreateToken::class)]
class CreateTokenTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function itFailsIfUserDoesNonExist(): void
    {
        $command = $this->artisan("sanctum:create-token foo@tc.tc");
        $command->assertFailed();
    }

    #[Test]
    public function itFailsOnWrongAbility(): void
    {
        $user = User::factory()->create();

        $command = $this->artisan("sanctum:create-token $user->email wrong_ability");
        $command->assertFailed();
    }

    #[Test]
    public function itCreatesToken(): void
    {
        $user = User::factory()->create();

        $command = $this->artisan("sanctum:create-token $user->email");
        $command->assertSuccessful();

        $command->run();
        $this->assertDatabaseHas(
            'personal_access_tokens',
            ['tokenable_type' => User::class, 'tokenable_id' => $user->id]
        );
    }
}
