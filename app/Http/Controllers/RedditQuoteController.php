<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;

class RedditQuoteController extends Controller
{
    protected $link = 'https://www.reddit.com/r/literature.json';

    public function test()
    {
        $client = new Client;
        $response = $client->get($this->link);

        dd(json_decode($response->getBody()->getContents()));
    }
}
