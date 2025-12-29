<?php

namespace App\Http\Controllers;

use App\Models\Contenido;
use App\Models\Portfolio;
use App\Models\portfoliodatos;
use App\Models\portfolioitem;
use App\Models\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    public function show($id)
    {
        try {
            $decryptedId = Crypt::decrypt($id);
            $portfolio = Portfolio::where('id', $decryptedId)->firstOrFail();
            $contenido = Contenido::where('spot_id', $portfolio->spot_id)->first();
            $seo = Seo::where('spot_id', $contenido->spot_id)->first();

            $imagenes = Portfolioitem::where('portfolio_id', $decryptedId)
                ->orderBy('orden')
                ->get();

            $datosTecnicos = Portfoliodatos::where('portfolio_id', $decryptedId)->first();
            
            // Datos SEO
            $descripcionSEO = $seo->seo_descripcion ?? $contenido->descripcion ?? $portfolio->descripcion ?? '';
            $keywordsSEO = $seo->seo_keyword ?? '';
            $robots = 'index, follow';
            $imagenOg = $imagenes->isNotEmpty() ? Storage::url($imagenes->first()->imagen) : null;
            $locale = 'es_ES';


            $titulo = $portfolio->titulo;


            return view('portfolio.vista', compact(
                'portfolio',
                'contenido',
                'imagenes',
                'datosTecnicos',
                'descripcionSEO',
                'keywordsSEO',
                'robots',
                'imagenOg',
                'locale'
            ));
        } catch (\Throwable $th) {
            abort(404);
        }
    }
}
