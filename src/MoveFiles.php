<?php

namespace Petrisrf\MoveFiles;

trait MoveFiles
{
    static public function bootMoveFiles()
    {
        static::observe(MoveFilesObserver::class);
    }

    abstract public function getFileAttributes();

    public function getUploadFolder()
    {
        return 'administracao/uploaded_files/';
    }
}