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
        $client = new Client();

        $response = $client->request('GET', 'https://news.ycombinator.com/');

        $html = $response->getBody()->getContents();

        $crawler = new Crawler($html);

        $articles = $crawler->filter('tr.athing');

        if ($articles->count() > 0) {
            $articles->each(function (Crawler $node, $i) {
                $titleNode = $node->filter('td.title > span.titleline > a');
                $title = $titleNode->text();
                $link = $titleNode->attr('href');

                if (DeletedArticle::where('title', $title)->exists()) {
                    $this->info("Article with title '$title' was deleted from database. Skipping...");
                    return; // Skip this article
                }

                $scoreNode = $node->nextAll()->filter('tr')->eq(0)->filter('span.score');
                $score = $scoreNode->count() > 0 ? (int) $scoreNode->text() : 0;

                $this->info("Title: $title");
                $this->info("Link: $link");
                $this->info("Score: $score\n");

                Articles::updateOrCreate(
                    ['link' => $link],
                    ['title' => $title, 'points' => $score]
                );

                $this->info("Inserted article with title '$title' into the database.");
            });
        } else {
            $this->info('No articles found on Hacker News.');
        }
        $this->info('Scraping completed successfully.');
    }

}
