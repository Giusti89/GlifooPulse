<?php

namespace App\Http\Controllers;

use App\Models\Contenido;
use App\Models\Portfolio;
use App\Models\Portfoliodatos;
use App\Models\Portfolioitem;
use App\Models\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class PortfolioController extends Controller
{
    public function show($id)
    {
        try {
            $decryptedId = Crypt::decrypt($id);
            $portfolio = Portfolio::where('id', $decryptedId)->firstOrFail();
            $contenido = Contenido::where('spot_id', $portfolio->spot_id)->first();
            $seo = Seo::where('spot_id', $contenido->spot_id)->first();
            $grupo = Str::slug($tipopublicidad->grupo ?? 'basico');

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

            $ogUrl = request()->url();
            $ogType = ($grupo === 'catalogo') ? 'business.business' : 'profile';

            return view('portfolio.vista', compact(
                'portfolio',
                'contenido',
                'imagenes',
                'datosTecnicos',
                'descripcionSEO',
                'keywordsSEO',
                'robots',
                'imagenOg',
                'locale',
                'ogUrl',
                'ogType'
            ));
        } catch (\Throwable $th) {
            abort(404);
        }
    }
}
