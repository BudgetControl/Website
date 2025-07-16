<?php

if (!function_exists('path_to')) {
    function path_to($path, $to)
    {
        return str_replace($_ENV['WORDPRESS_URL'], "$to", $path);
    }
}

if (!function_exists('response')) {
    function response(array $dataResponse, int $statusCode = 200, array $headers = []): \Psr\Http\Message\ResponseInterface
    {
        $response = new \GuzzleHttp\Psr7\Response();

        // Codifica i dati in JSON e scrivi nel corpo della risposta
        $jsonData = json_encode($dataResponse);
        if ($jsonData === false) {
            // Gestione degli errori durante la codifica JSON
            // In questo esempio, restituisci una risposta di errore
            $errorResponse = new \GuzzleHttp\Psr7\Response();
            $errorResponse->getBody()->write('Errore nella codifica JSON dei dati');
            return $errorResponse->withStatus(500);
        }

        $response->getBody()->write($jsonData);

        foreach ($headers as $key => $value) {
            $response = $response->withHeader($key, $value);
        }

        // Aggiungi intestazione Content-Type e stato alla risposta
        $response = $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);

        return $response;
    }
}

if (!function_exists('env')) {
    function env($key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }
}

if (!function_exists('invoke')) {
    function invoke($url, $method = 'GET', $data = [], $headers = []) : array
    {
        $client = new \GuzzleHttp\Client();
        $headers = [
            'X_API_SECRET' => env('HTTP_X_API_SECRET'),
            'Content-Type' => 'application/json'
        ];
        $body = json_encode($data);
        $request = new \GuzzleHttp\Psr7\Request($method, $url, $headers, $body);
        $res = $client->sendAsync($request)->wait();

        if(!$res->getStatusCode() || $res->getStatusCode() !== 200) {
            return [
                'error' => true,
                'http_code' => $res->getStatusCode(),
                'response' => (string) $res->getBody()
            ];
        }
        
        return [
            'error' => false,
            'http_code' => $res->getStatusCode(),
            'response' => (string) $res->getBody()
        ];
    }
}
