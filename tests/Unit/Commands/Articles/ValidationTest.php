<?php

namespace Tests\Unit\Commands\Articles;

use App\Article;
use App\Console\Commands\StoreArticlesFromUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ValidationTest extends TestCase
{
    use RefreshDatabase;

    const VALID_URL = 'https://www.google.com';
    const INVALID_URL = 'test-invalid-url';
    const JSON_URL_WITH_200_ARTICLES = 'https://raw.githubusercontent.com/SeteMares/full-stack-test/master/feed.json';
    const NOT_JSON_URL = 'https://www.google.com';

    /**
     * @var bool
     */
    public $mockConsoleOutput = false;

    /**
     * @covers \App\Console\Commands\StoreArticlesFromUrl::validateUrl
     */
    public function testValidUrl()
    {
        Artisan::call('articles:store-from-url', [
            'url' => self::VALID_URL,
        ]);

        $output = Artisan::output();
        $this->assertStringNotContainsString(StoreArticlesFromUrl::URL_NOT_VALID_MESSAGE, $output);
    }

    /**
     * @covers \App\Console\Commands\StoreArticlesFromUrl::validateUrl
     */
    public function testInvalidUrl()
    {
        Artisan::call('articles:store-from-url', [
            'url' => self::INVALID_URL,
        ]);

        $output = Artisan::output();
        $this->assertStringContainsString(StoreArticlesFromUrl::URL_NOT_VALID_MESSAGE, $output);
    }

    /**
     * @covers \App\Console\Commands\StoreArticlesFromUrl::validateJson
     */
    public function testValidJsonUrl()
    {
        Artisan::call('articles:store-from-url', [
            'url' => self::JSON_URL_WITH_200_ARTICLES,
        ]);

        $output = Artisan::output();
        $this->assertStringNotContainsString(StoreArticlesFromUrl::JSON_URL_NOT_VALID_MESSAGE, $output);
        $this->assertTrue(count(Article::all()) === 200);
    }

    /**
     * @covers \App\Console\Commands\StoreArticlesFromUrl::validateJson
     */
    public function testInvalidJsonUrl()
    {
        Artisan::call('articles:store-from-url', [
            'url' => self::NOT_JSON_URL,
        ]);

        $output = Artisan::output();
        $this->assertStringContainsString(StoreArticlesFromUrl::JSON_URL_NOT_VALID_MESSAGE, $output);
    }
}
