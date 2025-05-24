<?php

namespace Tests\Feature\App\Console\Commands;

use App\Console\Commands\CreateUser;
use App\Models\User;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\RefreshDatabase;
use Tests\TestCase;

#[CoversClass(CreateUser::class)]
class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function itFailsIfEmailAlreadyExists(): void
    {
        $email = 'email@domain.ex';
        User::factory()->create(['email' => $email]);

        $command = $this->artisan("users:create user_name $email");
        $command->assertFailed();
    }

    #[Test]
    public function itCreatesUser(): void
    {
        $email = 'email@domain.ex';

        $command = $this->artisan("users:create user_name $email");
        $command->assertSuccessful();

        $command->run();
        $this->assertDatabaseHas(User::class, ['email' => $email]);
    }
}
