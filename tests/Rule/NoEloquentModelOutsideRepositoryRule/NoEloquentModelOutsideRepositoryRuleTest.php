<?php

declare(strict_types=1);

namespace Tomasvotruba\Laratyped\Tests\Rule\NoEloquentModelOutsideRepositoryRule;

use Iterator;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tomasvotruba\Laratyped\Rule\NoEloquentModelOutsideRepositoryRule;

final class NoEloquentModelOutsideRepositoryRuleTest extends RuleTestCase
{
    /**
     * @param mixed[] $expectedErrorMessagesWithLines
     */
    #[DataProvider('provideData')]
    public function testRule(string $filePath, array $expectedErrorMessagesWithLines): void
    {
        $this->analyse([$filePath], $expectedErrorMessagesWithLines);
    }

    public static function provideData(): Iterator
    {
        $errorMessage = sprintf(NoEloquentModelOutsideRepositoryRule::ERROR_MESSAGE, 'save');

        yield [__DIR__ . '/Fixture/CallingModelSomewhere.php', [[$errorMessage, 11]]];
    }

    /**
     * @return string[]
     */
    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/config/configured_rule.neon'];
    }

    protected function getRule(): Rule
    {
        return new NoEloquentModelOutsideRepositoryRule();
    }
}
