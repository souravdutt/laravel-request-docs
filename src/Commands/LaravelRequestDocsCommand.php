<?php

namespace Rakutentech\LaravelRequestDocs\Commands;

use Illuminate\Console\Command;
use Rakutentech\LaravelRequestDocs\LaravelRequestDocs;
use Rakutentech\LaravelRequestDocs\LaravelRequestSwaggerDocs;
use File;

class LaravelRequestDocsCommand extends Command
{
    public $signature = 'lrd:generate';

    public $description = 'Generate request docs to HTML and update swagger json';

    private $laravelRequestDocs;
    private $laravelRequestSwaggerDocs;

    public function __construct(LaravelRequestDocs $laravelRequestDocs, LaravelRequestSwaggerDocs $laravelRequestSwaggerDocs)
    {
        $this->laravelRequestDocs        = $laravelRequestDocs;
        $this->laravelRequestSwaggerDocs = $laravelRequestSwaggerDocs;
        parent::__construct();
    }

    public function handle()
    {
        $destinationPath = config('request-docs.docs_path') ?? base_path('docs/request-docs/');

        $docs = $this->laravelRequestDocs->getDocs();
        $docs = $this->laravelRequestDocs->sortDocs($docs);

        if (! File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }
        File::put($destinationPath . '/index.html',
            view('request-docs::index')
                ->with(compact('docs'))
                ->render()
        );
        $this->comment("Static HTML generated: $destinationPath");
    }
}
