<?php

namespace App\Http\Controllers;

use App\Services\WebScrapingService;
use Illuminate\Http\Request;

class WebScraperController extends Controller
{
    protected $webScraperService;

    public function __construct(WebScrapingService $webScraperService)
    {
        $this->webScraperService = $webScraperService;
    }

    public function scrape(Request $request)
    {
        $url = $request->query('url', 'https://en.wikipedia.org/wiki/Laptop');
        $data = $this->webScraperService->scrape($url);

        return response()->json([
            'status'=>'success',
            'message'=>'Web Scrape completed',
            'data' => $data
        ]);
    }
}
