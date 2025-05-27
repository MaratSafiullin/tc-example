<?php

namespace Tests\Integration\App\Policies;

use App\Models\Set;
use App\Models\User;
use App\Policies\SetPolicy;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\RefreshDatabase;
use Tests\TestCase;

#[CoversClass(SetPolicy::class)]
class SetPolicyTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function itChecksIfUserCanManageSet(): void
    {
        $notOwner = User::factory()->create();
        $owner    = User::factory()->create();
        $set      = Set::factory()->usingOwner($owner)->create();

        $policy = new SetPolicy();

        $this->assertTrue($policy->manage($notOwner, $set)->denied());
        $this->assertTrue($policy->manage($owner, $set)->allowed());
    }
}
