<?php

namespace Petrisrf\MoveFiles\Tests;

use Illuminate\Database\Eloquent\Model;
use Petrisrf\MoveFiles\MoveFiles;

class Dummy extends Model
{
    use MoveFiles;

    protected $fillable = ['image'];

    public function getFileAttributes()
    {
        return ['image'];
    }
}