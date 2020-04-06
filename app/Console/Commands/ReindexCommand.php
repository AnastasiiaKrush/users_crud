<?php

namespace App\Console\Commands;

use App\User;
use Elasticsearch\Client;
use Illuminate\Console\Command;

class ReindexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:reindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexes all users to Elasticsearch';

    /** @var Client */
    private $elasticsearch;

    /**
     * Create an instance of Client.
     */
    public function __construct(Client $elasticsearch)
    {
        parent::__construct();

        $this->elasticsearch = $elasticsearch;
    }

    /**
     * Users indexing.
     */
    public function handle()
    {
        $this->info('Indexing all users. This might take a while...');

        foreach (User::cursor() as $user) {
            $this->elasticsearch->index([
                'index' => $user->getSearchIndex(),
                'type' => $user->getSearchType(),
                'id' => $user->getKey(),
                'body' => $user->toSearchArray(),
            ]);

            $this->output->write('.');
        }

        $this->info("\nDone!");
    }
}
