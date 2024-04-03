<?php

namespace Tests;

use App\Models\DeletedArticle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    public function testScrapeHackerNewsCommand()
    {
        print_r("Starting the test\n");

        print_r("Running the scraper command...\n");
        $this->artisan('scrape:hacker-news');

        print_r("Asserting that articles were inserted into the database...\n");
        $this->assertDatabaseCount('articles', 30);
        print_r("Articles were inserted into the database.\n");

        print_r("Deleting an article...\n");
        $deletedArticle = DeletedArticle::factory()->create(['title' => 'Deleted Article']);
        print_r("Deleted article: {$deletedArticle->title}\n");
        
        print_r("Running the scraper command again...\n");
        $this->artisan('scrape:hacker-news');

        print_r("Asserting that articles were inserted into the database again...\n");
        $this->assertDatabaseCount('articles', 30);
        print_r("Articles were inserted into the database again.\n");

        print_r("Asserting that the deleted article was not inserted again...\n");
        $this->assertDatabaseMissing('articles', ['title' => 'Deleted Article']);
        print_r("Deleted article was not inserted again.\n");

        print_r("Test completed.\n");
    }
}
