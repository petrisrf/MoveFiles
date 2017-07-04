<?php

namespace Petrisrf\MoveFiles;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase;
use Petrisrf\MoveFiles\Tests\Dummy;
use Mockery as m;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManagerTest extends TestCase
{
    protected $filesystem;

    protected $model;

    protected $fileManager;

    public function setUp()
    {
        parent::setUp();

        $this->filesystem = m::mock(Filesystem::class);
        $this->model      = m::mock(Dummy::class);

        $this->fileManager = new FileManager(
            $this->filesystem,
            $this->model,
            new Str()
        );
    }

    /**
     * @test
     * @dataProvider attribute_provider
     */
    public function should_validate_if_attribute_can_be_persisted($attr, $result)
    {
        $dummy      = new Dummy(['image' => $attr]);
        $filesystem = m::mock(Filesystem::class);

        $fileManager = new FileManager($filesystem, $dummy, new Str());

        $this->assertEquals($result, $fileManager->canMoveFile('image'));
    }

    public function attribute_provider()
    {
        return [
            'when attribute is string' => ['attr' => 'string', 'result' => false],
            'when attribute is null'   => ['attr' => null, 'result' => false],
            'when attribute is instance of UploadedFile' => [
                'attr' => new UploadedFile(
                    'tests/image.png',
                    'image.png',
                    'image/png',
                    1234
                ),
                'result' => true
            ]
        ];
    }

    /** @test */
    public function should_persist_file_attributes()
    {
        $image = m::mock(UploadedFile::class.'[move]', [
            'tests/image.png',
            'image.png',
            'image/png',
            12345,
            0,
            true
        ]);

        $string = m::mock(Str::class);

        $string->shouldReceive("random")->with(10)->once()->andReturn('randomstr');
        $image->shouldReceive('move')->with('administracao/uploaded_images/', 'randomstr.png')->once();

        $this->model->shouldReceive('getAttribute')->with('image')->times(3)->andReturn($image);
        $this->model->shouldReceive('getOriginal')->with('image')->once()->andReturn('path/to/old/image.png');
        $this->model->shouldReceive('getUploadFolder')->once()->andReturn('administracao/uploaded_images/');
        $this->model->shouldReceive('getFileAttributes')->once()->andReturn(['image']);
        $this->model->shouldReceive('setAttribute')->once()->with('image', 'administracao/uploaded_images/randomstr.png');

//        $this->filesystem->shouldReceive('canMoveFile')->once()->andReturn(true);
        $this->filesystem->shouldReceive('exists')->with('path/to/old/image.png')->once()->andReturn(true);
        $this->filesystem->shouldReceive('delete')->with('path/to/old/image.png')->once();

        $this->fileManager = new FileManager(
            $this->filesystem,
            $this->model,
            $string
        );

        $this->fileManager->persistModelFiles();
    }

    protected function getUploadedFileSample()
    {
        return new UploadedFile(
            'tests/image.png',
            'image.png',
            'image/png',
            12345,
            0,
            true
        );
    }
}