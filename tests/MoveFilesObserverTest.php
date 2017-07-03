<?php

namespace Petrisrf\MoveFiles;

use Mockery as m;
use Orchestra\Testbench\TestCase;
use Petrisrf\MoveFiles\Tests\Dummy;

class MoveFilesObserverTest extends TestCase
{
    protected $dummy;

    protected $filesystem;

    protected $observer;

    public function setUp()
    {
        parent::setUp();
        $this->dummy      = m::mock(Dummy::class);
        $this->filesystem = m::mock(FileManager::class);
        $this->observer   = new MoveFilesObserver($this->filesystem);

        $this->filesystem->shouldReceive('setModel')
                         ->once()
                         ->with($this->dummy);
    }

    /** @test */
    public function should_move_model_files()
    {
        $this->filesystem->shouldReceive('persistModelFiles')
                         ->once()
                         ->with($this->dummy);

        $this->observer->saving($this->dummy);
    }


    /** @test */
    public function should_delete_model_files()
    {
        $this->filesystem->shouldReceive('removeModelFiles')
            ->once()
            ->with($this->dummy);

        $this->observer->deleting($this->dummy);
    }
}