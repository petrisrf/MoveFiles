<?php

namespace Petrisrf\MoveFiles;

use Petrisrf\MoveFiles\Tests\Dummy;
use Orchestra\Testbench\TestCase;

class MoveFileTest extends TestCase
{
    protected $dispatcher;

    public function setUp()
    {
        parent::setUp();
        Dummy::boot();
        $this->dispatcher = Dummy::getEventDispatcher();
    }

    /** @test */
    public function should_add_saving_event_listener_to_class()
    {
        $this->assertTrue($this->dispatcher->hasListeners('eloquent.saving: '. Dummy::class));
    }

    /** @test */
    public function should_add_deleting_event_listener_to_class()
    {
        $this->assertTrue($this->dispatcher->hasListeners('eloquent.deleting: '. Dummy::class));
    }
}