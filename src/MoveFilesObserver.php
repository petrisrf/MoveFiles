<?php

namespace Petrisrf\MoveFiles;

use Illuminate\Database\Eloquent\Model;

class MoveFilesObserver
{
    protected $filemanager;

    public function __construct(FileManager $filemanager)
    {
        $this->filemanager = $filemanager;
    }

    public function saving(Model $model)
    {
        $this->filemanager->setModel($model);
        $this->filemanager->persistModelFiles($model);
    }

    public function deleting(Model $model)
    {
        $this->filemanager->setModel($model);
        $this->filemanager->removeModelFiles($model);
    }
}