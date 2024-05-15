<?php

namespace AkmalRiyadi\LaravelBackendGenerator\Commands;

use illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use AkmalRiyadi\LaravelBackendGenerator\CreateFile;

class MakeServiceAkm extends Command
{
    protected $signature = 'make:serviceakm
    {name : The name of service}
    {--api : Create service api}
    ';

    /**
     * The console command description
     * @var string
     */
    protected $description = 'Create a new service class';

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
        $name = str_replace('Service', "", $this->argument("name"));

        $api = $this->option("api");

        $className = Str::studly($name);
        $arr = explode("/", $className);
        $className = end($arr);
        $path = $this->getServicePath($className, $api);

        if (!$this->files->exists($path)) {
            $this->makeDirectory(dirname($path));
            $this->createService($className, $api);
            $this->info("File : {$path} created");
        } else {
            $this->error("File : {$path} already exits");
        }
    }

    /**
     * Create service class
     * @param string $className
     * @return string
     */
    public function createService(string $className, $api = false)
    {
        $serviceNamespace = $api ? "App\Services\Api" : "App\Services";

        $serviceName = $api ? $className . 'ServiceApi' : $className . 'Service';
        $stubProperties = [
            "{namespace}" => $serviceNamespace,
            "{serviceName}" => $serviceName,
            "{ModelName}" => $className
        ];

        $stubName = $api ? "make-service-api.stub" : "make-service.stub";
        $servicePath = $this->getServicePath($className, $api);
        new CreateFile(
            $stubProperties,
            $servicePath,
            __DIR__ . "/stubs/$stubName"
        );
        return $serviceNamespace . "\\" . $className;
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
     * Service path file
     * @return string
     */
    protected function getServicePath($className, $api)
    {
        $path = $api ? "/Api/" . $className . 'ServiceApi' . ".php" : "/" . "$className" . 'Service' . ".php";
        return app()->basePath() . "/app/Services" . $path;
    }
}