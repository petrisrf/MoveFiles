<?php

namespace Petrisrf\MoveFiles;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Model;

class FileManager
{
    protected $filesystem;

    protected $model;

    public function __construct(
        Filesystem $filesystem,
        Model $model
    ) {
        $this->filesystem = $filesystem;
        $this->model      = $model;
    }

    public function persistModelFiles()
    {
        $attributes = $this->model->getFileAttributes();

        foreach ($attributes as $attribute) {
            $this->persistFileIfNecessary($attribute);
        }
    }

    public function removeModelFiles(Model $model)
    {
        $attributes = $model->getFileAttributes();

        foreach ($attributes as $attribute) {
            $this->removeFileIfExists($attribute);
        }
    }

    private function persistFileIfNecessary($field)
    {
        if (!$this->canMoveFile($field)) {
            $this->model->setAttribute($field, $this->getOriginal($field));
        } else {
            $this->removeFileIfExists($field);
            $this->moveNewFile($field);
        }
    }

    public function canMoveFile($field)
    {
        $attribute = $this->model->getAttribute($field);

        return is_string($attribute) === false && $attribute != null;
    }

    public function removeFileIfExists($field)
    {
        $modelAttr = $this->model->getOriginal($field);

        if ($this->filesystem->exists($modelAttr)) {
            $this->filesystem->delete($modelAttr);
        }
    }

    private function moveNewFile($field)
    {
        $ext      = $this->model->getAttribute($field)->guessExtension();
        $filename = str_random(10).".{$ext}";

        $filepath = "{$this->model->getUploadFolder()}{$filename}";

        $this->model->$field->move($this->model->getUploadFolder(), $filename);
        $this->model->$field = $filepath;
    }
}