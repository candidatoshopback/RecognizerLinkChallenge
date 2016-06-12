<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\Client;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use ShopBack\XmlImporter\Manager\XmlImporterManager;

/**
 * Class XmlImporterJob
 * @package App\Jobs
 */
class XmlImporterJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /** @var string $client_id */
    private $client_id;
    /**
     * Create a new job instance.
     *
     * @param int $clientId
     *
     * @return void
     */
    public function __construct($clientId)
    {
        $this->client_id = $clientId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $xmlImporterManager = new XmlImporterManager($this->client_id);
        $xmlImporterManager->import();
    }
}
