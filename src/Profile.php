<?php

namespace Choccybiccy\GranTurismoSport;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface;

class Profile
{
    const API_PROFILE_URL = 'https://www.gran-turismo.com/gb/api/gt7sp/profile/';

    /**
     * @var int
     */
    protected $id;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * Profile constructor.
     * @param int $id
     * @param ClientInterface $httpClient
     */
    public function __construct(int $id, ClientInterface $httpClient = null)
    {
        $this->id = $id;
        if (!$httpClient) {
            $httpClient = new HttpClient;
        }
        $this->httpClient = $httpClient;
        $this->load();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        throw new \Exception("Unknown profile property $name");
    }

    /**
     * Load profile data.
     *
     * @return void
     */
    protected function load()
    {
        $response = $this->httpClient->post(self::API_PROFILE_URL, [
            'form_params' => [
                'job' => 3,
                'user_no' => $this->id,
            ]
        ]);

        $this->data = json_decode($response->getBody()->getContents(), true)['stats'];
    }
}

