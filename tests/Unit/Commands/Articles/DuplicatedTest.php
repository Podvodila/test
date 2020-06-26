<?php

namespace Tests\Unit\Commands\Articles;

use App\Console\Commands\StoreArticlesFromUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class DuplicatedTest extends TestCase
{
    use RefreshDatabase;

    const JSON_ARTICLES_URL
        = 'https://raw.githubusercontent.com/SeteMares/full-stack-test/master/feed.json';

    /**
     * @var bool
     */
    public $mockConsoleOutput = false;

    /**
     * We need to have link to temp file while test is not finished
     * in order to avoid auto-unlink
     */
    private $tempFile;

    /**
     * @covers \App\Console\Commands\StoreArticlesFromUrl::handleQueryException
     */
    public function testDuplicatedSlug()
    {
        $url = $this->getUrlWithDuplicatedSlug();
        Artisan::call('articles:store-from-url', [
            'url' => $url,
        ]);

        $output = Artisan::output();
        $this->assertStringContainsString(StoreArticlesFromUrl::DUPLICATED_SLUG_MESSAGE, $output);
    }

    private function getUrlWithDuplicatedSlug()
    {
        $articlesJson = file_get_contents(self::JSON_ARTICLES_URL);
        $articles = json_decode($articlesJson, true);
        $tempFile = $this->tempFile = tmpfile();
        fwrite($tempFile, json_encode([$articles[0], $articles[0]]));

        return stream_get_meta_data($tempFile)['uri'];
    }
}
