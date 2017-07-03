<?php

namespace Petrisrf\MoveFiles;

trait MoveFiles
{
    static public function bootMoveFiles()
    {
        static::observe(MoveFilesObserver::class);
    }
}