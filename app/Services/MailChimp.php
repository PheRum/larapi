<?php

namespace App\Services;

use App\Exceptions\MailChimpException;
use Cache;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

class MailChimp
{
    /** @var \GuzzleHttp\Client */
    protected $client;

    /**
     * MailChimp constructor.
     *
     * @throws \App\Exceptions\MailChimpException
     */
    public function __construct()
    {
        if (empty(config('services.mailchimp.key'))) {
            throw new MailChimpException('API Key is required');
        }

        [$key, $dc] = explode('-', config('services.mailchimp.key'));

        $this->client = new Client([
            'base_uri' => "https://$dc.api.mailchimp.com/3.0/",
            'auth' => ['apikey', $key],
        ]);
    }

    /**
     * GET request
     *
     * @param $uri
     * @param array $options
     * @return mixed
     * @throws \App\Exceptions\MailChimpException
     */
    public function getData($uri, array $options = [])
    {
        try {
            $response = Cache::remember('mail-chimp-' . $uri, 5, function () use ($uri, $options) {
                return json_decode($this->client->get($uri, $options)->getBody()->getContents());
            });

            return $response;
        } catch (ClientException $e) {
            throw new MailChimpException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * ANY request
     *
     * @param $method
     * @param string $uri
     * @param array $options
     * @return mixed
     * @throws \App\Exceptions\MailChimpException
     */
    public function request($method, $uri = '', array $options = [])
    {
        try {
            return json_decode($this->client->request($method, $uri, $options)->getBody()->getContents());
        } catch (GuzzleException $e) {
            throw new MailChimpException($e->getMessage(), $e->getCode());
        }
    }
}
