<?php

namespace Tomasvotruba\Laratyped\Tests\Rule\NoEloquentModelOutsideRepositoryRule\Fixture;

use Tomasvotruba\Laratyped\Tests\Rule\NoEloquentModelOutsideRepositoryRule\Source\Car;

final class CallingModelSomewhere
{
    public function drive(Car $car)
    {
        $car->save();
    }
}
