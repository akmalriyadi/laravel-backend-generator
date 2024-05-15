<?php

namespace AkmalRiyadi\LaravelBackendGenerator\Commands;

use illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use AkmalRiyadi\LaravelBackendGenerator\CreateFile;

class MakeRepositoryAkm extends Command
{
    protected $signature = 'make:repositoryakm
    {name : The name of repository}
    ';

    /**
     * The console command description
     * @var string
     */
    protected $description = 'Create a new repository class';

    /**
     * Filesystem instance
     * @var Filesystem
     */
    protected $files;

    /**
     * Create a new command instance.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Handle the command
     * @return void
     */
    public function handle()
    {
        $name = str_replace('Repository', "", $this->argument("name"));

        $className = Str::studly($name);
        $arr = explode("/", $className);
        $className = end($arr);
        $path = $this->getRepositoryPath($className);

        if (!$this->files->exists($path)) {
            $this->makeDirectory(dirname($path));
            $this->createRepository($className);
            $this->info("File : {$path} created");
        } else {
            $this->error("File : {$path} already exits");
        }
    }

    /**
     * Create repository class
     * @param string $className
     * @return string
     */
    public function createRepository(string $className)
    {
        $repositoryNamespace = "App\Repositories";

        $repositoryName = $className . 'Repository';
        $stubProperties = [
            "{namespace}" => $repositoryNamespace,
            "{repositoryName}" => $repositoryName,
            "{ModelName}" => $className
        ];

        $stubName = "make-repository.stub";
        $repositoryPath = $this->getRepositoryPath($className);
        new CreateFile(
            $stubProperties,
            $repositoryPath,
            __DIR__ . "/stubs/$stubName"
        );
        return $repositoryNamespace . "\\" . $className;
    }

    /**
     * Make directory files
     * @param string $path
     * @return string
     */
    protected function makeDirectory(string $path)
    {
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }

    /**
     * Repository path file
     * @return string
     */
    protected function getRepositoryPath($className)
    {
        $path = "/" . "$className" . 'Repository' . ".php";
        return app()->basePath() . "/app/Repositories" . $path;
    }
}