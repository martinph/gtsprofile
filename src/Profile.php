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
     * @return string
     */
    public function getMannerPointLevel()
    {
        $mannerPoint = (int) $this->data['manner_point'];
        switch(true) {
            case 1 <= $mannerPoint && $mannerPoint <= 9:
                return 'E';
            case 10 <= $mannerPoint && $mannerPoint <= 19:
                return 'D';
            case 20 <= $mannerPoint && $mannerPoint <= 39:
                return 'C';
            case 40 <= $mannerPoint && $mannerPoint <= 64:
                return 'B';
            case 65 <= $mannerPoint && $mannerPoint <= 79:
                return 'A';
            case 80 <= $mannerPoint && $mannerPoint <= 99:
                return 'S';
            default:
                return '-';
        }
    }

    /**
     * @return string
     */
    public function getDriverPointLevel()
    {
        switch ((int) $this->data['driver_class']) {
            case 1:
                return 'E';
            case 2:
                return 'D';
            case 3:
                return 'C';
            case 4:
                return 'B';
            case 5:
                return 'A';
            case 6:
                return 'S';
            default:
                return '-';
        }
    }

    /**
     * Load profile data.
     *
     * @return void
     */
    public function load()
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

