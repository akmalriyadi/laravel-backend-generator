<?php

namespace AkmalRiyadi\LaravelBackendGenerator\Commands;

use Illuminate\Support\Str;
use illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use AkmalRiyadi\LaravelBackendGenerator\CreateFile;

class MakeControllerAkm extends Command
{

    protected $signature = 'make:controllerakm
    {name : The name of controller }
    {--api : Create api controller}
    ';

    /**
     * The console command description
     * @var string
     */
    protected $description = 'Create a new controller class';

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
        $name = str_replace('Controller', "", $this->argument("name"));

        $api = $this->option("api");

        $className = Str::studly($name);
        $arr = explode("/", $className);
        $className = end($arr);
        $path = $this->getControllerPath($className, $api);
        if (!$this->files->exists($path)) {
            $this->makeDirectory(dirname($path));
            $this->createController($className, $api);
            $this->info("File : {$path} created");
        } else {
            $this->error("File : {$path} already exits");
        }

        if ($api) {
            $this->createRepository($className);
            $this->createRequest($className);
            $this->createService($className);
        }
    }

    private function createRepository($className)
    {
        $arr = explode("/", $className);
        $className = end($arr);

        $this->call("make:repositoryakm", [
            "name" => $className,
        ]);
    }

    private function createService($className)
    {
        $arr = explode("/", $className);
        $className = end($arr);

        if ($this->option("api")) {
            $this->call("make:serviceakm", [
                "name" => $className,
                "--api" => null
            ]);
        } else {
            $this->call("make:serviceakm", [
                "name" => $className,
            ]);
        }
    }

    private function createRequest($className)
    {
        $arr = explode("/", $className);
        $className = end($arr);

        $this->call("make:requestakm", [
            "name" => $className,
        ]);
    }

    /**
     * Create controller class
     * @param string $className
     * @return string
     */
    public function createController(string $className, $api = false)
    {
        $controllerNamespace = $api ? "App\Http\Controllers\Api" : "App\Http\Controllers";

        $controllerName = $api ? $className . 'ControllerApi' : $className . 'Controller';
        $stubProperties = [
            "{namespace}" => $controllerNamespace,
            "{controllerName}" => $controllerName,
            "{ModelName}" => $className,
            "{repoName}" => lcfirst($className)
        ];

        $stubName = $api ? "make-controller-api.stub" : "make-controller.stub";
        $controllerPath = $this->getControllerPath($className, $api);
        new CreateFile(
            $stubProperties,
            $controllerPath,
            __DIR__ . "/stubs/$stubName"
        );
        return $controllerNamespace . "\\" . $className;
    }

    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }

    /**
     * Controller path file
     * @return string
     */
    protected function getControllerPath($className, $api)
    {
        $path = $api ? "/Api/" . $className . 'ControllerApi' . ".php" : "/" . "$className" . 'Controller' . ".php";
        return app()->basePath() . "/app/Http/Controllers" . $path;
    }

}