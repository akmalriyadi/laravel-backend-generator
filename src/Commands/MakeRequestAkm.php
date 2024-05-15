<?php

namespace AkmalRiyadi\LaravelBackendGenerator\Commands;

use illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use AkmalRiyadi\LaravelBackendGenerator\CreateFile;

class MakeRequestAkm extends Command
{
    protected $signature = 'make:requestakm
    {name : The name of request}
    ';

    /**
     * The console command description
     * @var string
     */
    protected $description = 'Create a new Request store and update class';

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
        $name = str_replace('Request', "", $this->argument("name"));
        $name = Str::studly($name);

        $className = Str::studly($name);
        $arr = explode("/", $className);
        $className = end($arr);
        $types = [
            'Update',
            'Store'
        ];

        foreach ($types as $type) {
            $path = $this->getRequestPath($className, $type);
            if (!$this->files->exists($path)) {
                $this->makeDirectory(dirname($path));
                $this->createRequest($className, $type);
                $this->info("File : {$path} created");
            } else {
                $this->error("File : {$path} already exits");
            }
        }
    }

    /**
     * Create Request
     *
     * @param string $className
     * @param string $type
     * @return string
     */
    public function createRequest(string $className, $type)
    {
        $RequestNamespace = "App\Http\Requests" . "\\" . $className;

        $RequestName = $type . $className . 'Request';
        $stubProperties = [
            "{namespace}" => $RequestNamespace,
            "{RequestName}" => $RequestName,
        ];

        $stubName = "make-request.stub";
        $RequestPath = $this->getRequestPath($className, $type);
        new CreateFile(
            $stubProperties,
            $RequestPath,
            __DIR__ . "/stubs/$stubName"
        );
        return $RequestNamespace . "\\" . $className;
    }

    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }

    private function getRequestPath($className, $type)
    {
        $path = "/" . "$className" . "/" . "$type" . "$className" . "Request" . ".php";
        // $path = "/" . "$className";
        return app()->basePath() . "/app/Http/Requests" . $path;
    }
}