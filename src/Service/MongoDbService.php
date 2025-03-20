<?php

namespace App\Service;

use DateTimeImmutable;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MongoDbService
{
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function insertVisit($pageName)
    {
        $this->httpClient->request('POST', 'https://us-east-2.aws.neurelo.com/rest/visits/__one', [
            'headers' => [
                'X-API-KEY' => 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6ImFybjphd3M6a21zOnVzLWVhc3QtMjowMzczODQxMTc5ODQ6YWxpYXMvYjJjYWNlYWItQXV0aC1LZXkifQ.eyJlbnZpcm9ubWVudF9pZCI6ImYxMjVmZTUyLWU0MGUtNDg4YS05ODIzLWE0NWY2MjFmMzA5YyIsImdhdGV3YXlfaWQiOiJnd19iMmNhY2VhYi0yYTRlLTQ3YzYtOTlkZS1iNDM3M2I4NWE2MjIiLCJwb2xpY2llcyI6WyJSRUFEIiwiV1JJVEUiLCJVUERBVEUiLCJERUxFVEUiLCJDVVNUT00iXSwiaWF0IjoiMjAyNS0wMy0yMFQxNjowMjozNi42NTM3NDYwNTBaIiwianRpIjoiYTNiYzNmZWItODU5ZS00MmMyLWFkNWUtNDIzODUwYzgyNWQ0In0.0sCBZRHmRBv_pg450Ib7v2I5D1dcHsMqEekCcIBp7eNfAzNJXvKCmh1jdyDnZiL1ubq7Hz_RlPtC5m23elASrfvmWG6eeBbS3QIQ1fOOLJTP80JHoJskWKUab_vyLkjw5qN5mQ4E_gEb9Le4jjnn62_FLwLDNcUYKl4_dlYi96jsP0l6XdbMR6myjaYA8vkN6EDVR4EBCmbutB3YoQfHhjKYo28_H2vHsujN7U2FxrRALUgFPeh7QGo4wIRAwWCcA3SPFO5I92AqcZ6XUQFKpwc2yg2HIw6opb8K5zCIw1F0AQQgIkKCw_97JsHqytqSlb7Cw1DkvR_P9GH5TNr1aQ',
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'pageName' => $pageName,
                'visitedAt' => (new DateTimeImmutable())->format('Y-m-d H:i:s'),
            ]
        ]);

    }
}
