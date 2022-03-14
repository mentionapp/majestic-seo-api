<?php

namespace Nticaric\Majestic;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class MajesticAPIService
{
    private string $apiKey;

    private string $endpoint = 'http://api.majestic.com/api/';

    private string $responseType = 'json';

    public function __construct(string $apiKey, bool $sandbox = false)
    {
        if ($sandbox === true) {
            $this->endpoint = "http://developer.majestic.com/api";
        }

        $this->apiKey = $apiKey;
    }

    public function setResponseType(string $type): void
    {
        $this->responseType = $type;
    }

    /**
     * @param string|array $items
     */
    public function executeCommand(string $name, $items, array $params = []): Response
    {
        $command = ucfirst($name);

        if (is_string($items)) {
            $params['item'] = $items;
        } elseif (is_array($items)) {
            $counter = 0;

            foreach ($items as $url) {
                $params['item' . $counter] = $url;
                $counter++;
            }

            $params['items'] = $counter;
        }

        return $this->execute($command, $params);
    }

    private function execute(string $command, array $params = []): Response
    {
        $client = new Client;

        $params["cmd"]         = $command;
        $params["app_api_key"] = $this->apiKey;

        return $client->get($this->endpoint ."/". $this->responseType, [
            'query' => $params
        ]);
    }
}
