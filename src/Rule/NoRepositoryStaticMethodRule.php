<?php

declare(strict_types=1);

namespace Tomasvotruba\Laratyped\Rule;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @see \Tomasvotruba\Laratyped\Tests\Rule\NoEloquentModelOutsideRepositoryRule\NoEloquentModelOutsideRepositoryRuleTest
 */
final class NoRepositoryStaticMethodRule implements Rule
{
    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    /**
     * @param InClassNode $node
     * @return RuleError[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return [];
        }

        if (! str_ends_with($classReflection->getName(), 'Repository')) {
            return [];
        }

        $classLike = $node->getOriginalNode();
        if (! $classLike instanceof Node\Stmt\Class_) {
            return [];
        }

        $ruleErrors = [];

        foreach ($classLike->getMethods() as $method) {
            if (! $method->isStatic()) {
                continue;
            }

            $errorMessage = sprintf('Static method "%s()" is not allowed in repository', $method->name->toString());

            $ruleErrors[] = RuleErrorBuilder::message($errorMessage)
                ->line($method->getLine())
                ->build();
        }

        return $ruleErrors;
    }
}
