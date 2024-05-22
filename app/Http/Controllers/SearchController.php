<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class SearchController extends Controller
{
    public function index()
    {
        return view('search');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        try {
            $results = $this->searchGoogleShopping($query);
            if (!$results) {
                throw new \Exception("No results found");
            }
            return view('results', ['results' => $results]);
        } catch (\Exception $e) {
            return view('results', ['results' => null, 'error' => $e->getMessage()]);
        }
    }

    private function searchGoogleShopping($query)
    {
        $client = new Client();
        $response = $client->get('https://www.google.com/search?tbm=shop&q=' . urlencode($query));
        $html = (string) $response->getBody();
    
        // Extraer URLs de los resultados
        $links = $crawler->filter('a')->each(function (Crawler $node) {
            $href = $node->attr('href');
            if (strpos($href, '/shopping/product/') !== false) {
                return 'https://www.google.com' . $href;
            }
            return null;
        });

        // Filtrar y obtener datos de "La Casa del Electrodoméstico"
        foreach ($links as $link) {
            if ($link && strpos($link, 'lacasadelelectrodomestico.com') !== false) {
                $offersPageUrl = strtok($link, '?');
                $productId = $this->extractProductId($link);
                return [
                    'google_shopping_url' => $offersPageUrl,
                    'product_id' => $productId
                ];
            }
        }

        return null;
    }

    private function extractProductId($url)
    {
        // Extraer el ID de producto de la URL
        preg_match('/IDArticulo~(\d+)/', $url, $matches);
        return $matches[1] ?? null;
    }
}


