<?php

namespace Tests\Integration\App\Rules;

use App\Models\Set;
use App\Models\Theme;
use App\Rules\SetThemesUnique;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(SetThemesUnique::class)]
class SetThemesUniqueTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function itFailsIfDuplicatesInPostedData(): void
    {
        $set = Set::factory()->create();

        $rule = new SetThemesUnique($set);

        $themes       = [
            ['name' => 'Theme 1'],
            ['name' => 'Theme 1'],
        ];
        $errorMessage = '';
        $rule->validate('', $themes, function (string $message) use (&$errorMessage): void {
            $errorMessage = $message;
        });

        $this->assertSame('Theme names must be unique within the set.', $errorMessage);
    }

    #[Test]
    public function itFailsIfThemeAlreadyExists(): void
    {
        $set = Set::factory()->create();
        Theme::factory()->usingSet($set)->create(['name' => 'Theme 1']);

        $rule = new SetThemesUnique($set);

        $themes       = [
            ['name' => 'Theme 1'],
        ];
        $errorMessage = '';
        $rule->validate('', $themes, function (string $message) use (&$errorMessage): void {
            $errorMessage = $message;
        });

        $this->assertSame('Theme names must be unique within the set.', $errorMessage);
    }

    #[Test]
    public function itPassesValidation(): void
    {
        $set = Set::factory()->create();
        Theme::factory()->usingSet($set)->create(['name' => 'Theme 1']);

        $rule = new SetThemesUnique($set);

        $themes = [
            ['name' => 'Theme 2'],
        ];

        $validated = true;
        $rule->validate('', $themes, function () use (&$validated): void {
            $validated = false;
        });

        $this->assertTrue($validated);
    }
}
