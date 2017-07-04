<?php

namespace Petrisrf\MoveFiles\Contracts;

interface FileManagerInterface
{
    public function persistModelFiles();

    public function removeModelFiles();

    public function canMoveFile($attribute);

    public function removeFileIfExists($attribute);
}