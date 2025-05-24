<?php

namespace App\Console\Commands;

use App\Models\AccessToken\Ability;
use App\Models\User;
use Illuminate\Console\Command;

class CreateToken extends Command
{
    protected $signature = 'sanctum:create-token {email} {ability=public-api} {name=default}';

    /**
     * @throws \Throwable
     */
    public function handle(): int
    {
        $ability = $this->argument('ability');
        $name    = $this->argument('name');

        $user = User::where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error('User not found.');

            return 1;
        }

        $availableValues = array_column(Ability::cases(), 'value');
        if (! in_array($ability, $availableValues)) {
            $this->error('Invalid ability.');

            return 1;
        }

        $accessToken = $user->createToken($name, [$ability]);
        $this->info("Token created: $accessToken->plainTextToken");

        return 0;
    }
}
