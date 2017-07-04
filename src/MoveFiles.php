<?php

namespace Petrisrf\MoveFiles;

trait MoveFiles
{
    static public function bootMoveFiles()
    {
        static::observe(new MoveFilesObserver());
    }

    abstract public function getFileAttributes();

    public function getUploadFolder()
    {
        return 'administracao/uploaded_files/';
    }
}
