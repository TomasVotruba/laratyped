<?php

declare(strict_types=1);

namespace Tomasvotruba\Laratyped\Rule;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Rules\Rule;
use PHPStan\Type\ObjectType;
use PHPStan\Type\ThisType;

/**
 * @see \Tomasvotruba\Laratyped\Tests\Rule\NoEloquentModelOutsideRepositoryRule\NoEloquentModelOutsideRepositoryRuleTest
 */
final class NoEloquentModelOutsideRepositoryRule implements Rule
{
    /**
     * @var string[]
     */
    public const ACTIVE_METHOD_NAMES = ['save', 'get', 'count', 'all', 'query'];

    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Calling active model method "%s()" is allowed only in repository';

    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    /**
     * @param MethodCall $node
     * @return string[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $methodName = $this->resolveMethodName($node);
        if (! in_array($methodName, self::ACTIVE_METHOD_NAMES, true)) {
            return [];
        }

        if (! $this->isLaravelModelObjectType($node, $scope)) {
            return [];
        }

        if ($this->isRepositoryClass($scope)) {
            return [];
        }

        $errorMessage = sprintf(self::ERROR_MESSAGE, $methodName);

        return [$errorMessage];
    }

    private function resolveMethodName(MethodCall $methodCall): ?string
    {
        if (! $methodCall->name instanceof Identifier) {
            return null;
        }

        return $methodCall->name->toString();
    }

    private function isLaravelModelObjectType(MethodCall $methodCall, Scope $scope): bool
    {
        $callerType = $scope->getType($methodCall->var);

        if ($callerType instanceof ThisType) {
            $callerType = new ObjectType($callerType->getClassName());
        }

        if (! $callerType instanceof ObjectType) {
            return false;
        }

        return $callerType->isInstanceOf('Illuminate\Database\Eloquent\Model')->yes();
    }

    private function isRepositoryClass(Scope $scope): bool
    {
        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        // allowed in repository only
        return str_ends_with($classReflection->getName(), 'Repository');
    }
}
