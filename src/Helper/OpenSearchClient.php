<?php

namespace App\Helper;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OpenSearchClient
{
    private $client;
    private $parameterBag;

    public function __construct(HttpClientInterface $client, ParameterBagInterface $parameterBag)
    {
        $this->client = $client;
        $this->parameterBag = $parameterBag;
    }

    public function search(string $query, int $page = 1, int $perPage = 5, int $fuzziness = 2): array
    {
        $index = "hackday-demo-123";

        // Calculate the starting index for pagination
        $from = ($page - 1) * $perPage;

        try {

            $response = $this->client->request('POST', $this->openSearchEndpoint()."/".$index."/_search", [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode([
                    'from' => $from,
                    'size' => $perPage,
                    'query' => [
                        'multi_match' => [
                            'query' => $query,
                            'fields' => ['name'],
                            "fuzziness" => $fuzziness
                        ],
                    ]
                ]),
            ]);

        } catch (\Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface $e) {
            // Dump the response content for debugging
            dump($e->getResponse()->getContent(false));
            // Handle the exception as needed
        }

        // Process the response and extract the relevant data
        $data = $response->toArray();

        // Extract and return the results
        return $data['hits']['hits'] ?? [];
    }

    private function openSearchEndpoint(): string
    {
        return $this->parameterBag->get("openSearchEndpoint");
    }
}