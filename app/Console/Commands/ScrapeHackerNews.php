<?php

namespace App\Console\Commands;

use App\Models\DeletedArticle;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Articles;

class ScrapeHackerNews extends Command
{
    protected $signature = 'scrape:hacker-news';

    protected $description = 'Scrape articles from Hacker News';

    public function handle()
    {
        // Create a Guzzle HTTP client
        $client = new Client();

        // Send a GET request to Hacker News
        $response = $client->request('GET', 'https://news.ycombinator.com/');

        // Get the HTML content
        $html = $response->getBody()->getContents();

        // Parse HTML content using Symfony DomCrawler
        $crawler = new Crawler($html);

        // Extract titles, links, and scores of articles
        $articles = $crawler->filter('tr.athing');

        // Check if there are articles to scrape
        if ($articles->count() > 0) {
            // Loop through each article and extract the title, link, and score
            $articles->each(function (Crawler $node, $i) {
                // Extract title
                $titleNode = $node->filter('td.title > span.titleline > a');
                $title = $titleNode->text();
                $link = $titleNode->attr('href');

                // Check if article title was deleted from database
                if (DeletedArticle::where('title', $title)->exists()) {
                    $this->info("Article with title '$title' was deleted from database. Skipping...");
                    return; // Skip this article
                }

                // Extract score
                $scoreNode = $node->nextAll()->filter('tr')->eq(0)->filter('span.score');
                $score = $scoreNode->count() > 0 ? (int) $scoreNode->text() : 0;

                // Output the title, link, and score
                $this->info("Title: $title");
                $this->info("Link: $link");
                $this->info("Score: $score\n");

                // Insert the article into the database
                Articles::updateOrCreate(
                    ['link' => $link],
                    ['title' => $title, 'points' => $score]
                );

                // Optionally, you can log the insertion here if needed
                $this->info("Inserted article with title '$title' into the database.");
            });
        } else {
            // No articles found
            $this->info('No articles found on Hacker News.');
        }

        // Output success message
        $this->info('Scraping completed successfully.');
    }

}
