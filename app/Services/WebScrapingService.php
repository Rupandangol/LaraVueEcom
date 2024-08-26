<?php

namespace App\Services;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class WebScrapingService
{
    protected $client;
    public function __construct()
    {
        $this->client = new Client();
    }
    public function scrape($url)
    {
        $response = $this->client->request('get', $url);
        $htmlContent = $response->getBody()->getContents();
        $crawler = new Crawler($htmlContent);
        $titles = $crawler->filter('h1')->each(function (Crawler $node) {
            return $node->text();
        });
        return $titles;
    }
}
