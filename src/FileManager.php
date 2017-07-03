<?php

namespace Petrisrf\MoveFiles;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Model;

class FileManager
{
    protected $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function persistModelFiles(Model $model)
    {
        $attributes = $model->getStorableFileFields();

        foreach ($attributes as $attribute) {
            $this->persistFileIfNecessary($attribute);
        }
    }

    public function removeModelFiles(Model $model)
    {
        $attributes = $model->getStorableFileFields();

        foreach ($attributes as $attribute) {
            $this->removeFileIfExists($attribute);
        }
    }

    private function persistFileIfNecessary($field)
    {
        if (!$this->hasFileToMove($field)) {
            $this->setAttribute($field, $this->getOriginal($field));
        } else {
            $this->removeOldFileIfExists($field);
            $this->moveNewFile($field);
        }
    }

    private function hasFileToMove($field)
    {
        return !is_string($this->getAttribute($field)) &&
            $this->getAttribute($field) != null;
    }

    private function removeFileIfExists($field)
    {
        if (File::exists($this->getOriginal($field))) {
            File::delete($this->getOriginal($field));
        }
    }

    private function moveNewFile($field)
    {
        $ext      = $this->getAttribute($field)->guessExtension();
        $filename = str_random(10).".{$ext}";

        $filepath = "{$this->folder}{$filename}";

        $this->$field->move($this->folder, $filename);
        $this->$field = $filepath;
    }
}