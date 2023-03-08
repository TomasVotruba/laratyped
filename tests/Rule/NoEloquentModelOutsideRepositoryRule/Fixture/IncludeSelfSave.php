<?php

namespace Tomasvotruba\Laratyped\Tests\Rule\NoEloquentModelOutsideRepositoryRule\Fixture;

use Illuminate\Database\Eloquent\Model;

final class IncludeSelfSave extends Model
{
    public function go()
    {
        $this->save();
    }
}
