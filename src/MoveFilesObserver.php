<?php

namespace Petrisrf\MoveFiles;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MoveFilesObserver
{
    public function saving(Model $model)
    {
        $filemanager = $this->getFileManagerInstance($model);
		$filemanager->persistModelFiles($model);

		return true;
    }

    public function deleting(Model $model)
    {
        $filemanager = $this->getFileManagerInstance($model);
		$filemanager->removeModelFiles($model);

		return true;
    }

    protected function getFileManagerInstance($model)
    {
        return new FileManager(
            app()['files'],
            $model,
            new Str()
        );
    }
}
