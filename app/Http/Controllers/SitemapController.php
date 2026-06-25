<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index()
    {
        $spots = Spot::publicos()->get();

        return response()
            ->view('seo.sitemap', compact('spots'))
            ->header('Content-Type', 'text/xml');
    }
}
