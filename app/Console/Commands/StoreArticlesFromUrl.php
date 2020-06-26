<?php

namespace App\Console\Commands;

use App\Article;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;

class StoreArticlesFromUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:store-from-url {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store articles from url';

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
     */
    public function handle()
    {
        $url = $this->argument('url');
        if (!$this->validateUrl($url)) {
            return;
        }

        $articlesJson = file_get_contents($url);
        if (!$this->validateJson($articlesJson)) {
            return;
        }

        $articles = json_decode($articlesJson, true);
        $this->storeArticles($articles);
    }

    /**
     * @param array $articles
     */
    private function storeArticles(array $articles) : void
    {
        $progressBar = $this->output->createProgressBar(count($articles));
        foreach ($articles as $article) {
            $article['content'] = $article['content'][0]['content'];
            try {
                Article::create($article);
            } catch (QueryException $exception) {
                $progressBar->clear();
                $this->handleQueryException($exception, $article['slug']);
                $progressBar->display();
            }

            $progressBar->advance();
        }
        $progressBar->finish();
    }

    /**
     * @param QueryException $exception
     * @param string $slug
     */
    private function handleQueryException(QueryException $exception, string $slug) : void
    {
        $errorCode = $exception->errorInfo[1];
        if ($errorCode == 1062) {
            $this->warn("An article already exists with the following slug: $slug");
        } else {
            $this->error($exception->getMessage());
        }
    }

    /**
     * @param $url
     * @return bool
     */
    private function validateUrl($url) : bool
    {
        $valid = filter_var($url, FILTER_VALIDATE_URL) !== false;
        if (!$valid) {
            $this->error('Url is not valid');
        }
        return $valid;
    }

    /**
     * @param $string
     * @return bool
     */
    private function validateJson($string) : bool
    {
        if (!$valid = isJson($string)) {
            $this->error('Articles are not valid JSON');
        }
        return $valid;
    }
}
