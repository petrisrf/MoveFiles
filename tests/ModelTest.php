<?php

namespace Petrisrf\MoveFiles;

use Illuminate\Http\UploadedFile;
use Orchestra\Testbench\TestCase;
use Petrisrf\MoveFiles\Tests\Dummy;

class ModelTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function should_save()
    {
        $dummy = new Dummy(['image' => new UploadedFile(
            'tests/image.png',
            'image.png',
            'image/png',
            1234,
            null,
            true
        )]);

        $dummy->save();
    }
}