<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class XmlImporter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:xml-importer {client_domain}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import products from xml for a client';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client_domain = $this->argument('client_domain');
        
    }
}
