<?php

declare(strict_types=1);

namespace Tomasvotruba\Laratyped\Tests\Rule\NoRepositoryStaticMethodRule;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Tomasvotruba\Laratyped\Rule\NoRepositoryStaticMethodRule;

final class NoRepositoryStaticMethodRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new NoRepositoryStaticMethodRule();
    }

    public function testRule(): void
    {
        $this->analyse([__DIR__ . '/Fixture/WithStaticClassMethodRepository.php'], [
            ['Static method "get()" is not allowed in repository', 7],
        ]);
    }
}
