<?php

namespace Petrisrf\MoveFiles;

use Illuminate\Support\ServiceProvider;
use Petrisrf\MoveFiles\Contracts\FileManagerInterface;

class MoveFilesServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /*public function boot()
    {

    }*/

    public function register()
    {
        $this->app->bind(FileManagerInterface::class, FileManager::class);
    }

    public function provider()
    {
        return [FileManagerInterface::class];
    }
}