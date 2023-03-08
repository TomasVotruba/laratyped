<?php

declare(strict_types=1);

namespace Tomasvotruba\Laratyped\Tests\Rule\NoRepositoryStaticMethodRule;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tomasvotruba\Laratyped\Rule\NoRepositoryStaticMethodRule;

final class NoRepositoryStaticMethodRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new NoRepositoryStaticMethodRule();
    }

    #[DataProvider('provideData')]
    public function testRule(string $filePath, array $expectedErrorsWithLines): void
    {
        $this->analyse([$filePath], $expectedErrorsWithLines);
    }

    public static function provideData(): \Iterator
    {
        yield [
            __DIR__ . '/Fixture/WithStaticClassMethodRepository.php',
            [['Static method "get()" is not allowed in repository', 7]],
        ];

        yield [__DIR__ . '/Fixture/SkipNoRepositorySuffix.php', []];
        yield [__DIR__ . '/Fixture/SkipNoStaticMethodRepository.php', []];
    }
}
