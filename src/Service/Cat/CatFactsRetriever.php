<?php

declare(strict_types=1);

namespace App\Service\Cat;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CatFactsRetriever {
    private HttpClientInterface $client;
    public array $facts;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        $this->facts = $this->retrieveFacts();
    }

    /**
     * Retrieve facts from catfact.ninja API
     *
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function retrieveFacts(): array
    {
        $response = $this->client->request('GET', 'https://catfact.ninja/facts');

        if ($response->getStatusCode() === 200) {
            $response->getContent();
            $response = $response->toArray();

            $result = [];
            foreach ($response['data'] as $value) {
                $result[] = $value['fact'];
            }

            return $result;
        }

        return [];
    }
}