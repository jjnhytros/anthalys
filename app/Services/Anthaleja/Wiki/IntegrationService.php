<?php

namespace App\Services\Anthaleja\Wiki;

use Illuminate\Support\Facades\Http;

class IntegrationService
{
    /**
     * Integra informazioni da un'API esterna.
     *
     * @param string $apiUrl
     * @return array
     */
    public function fetchExternalData($apiUrl)
    {
        // Implementazione fittizia: usare Http per fare richieste a un'API
        $response = Http::get($apiUrl);

        return $response->json();
    }
}
