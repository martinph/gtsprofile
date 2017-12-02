<?php

namespace Choccybiccy\GranTurismoSport;

use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class ProfileTest extends TestCase
{
    public function test_getId_returns_id()
    {
        $id = mt_rand(1, 999);
        $profile = $this->getMockProfile(null, $id);
        self::assertEquals($id, $profile->getId());
    }

    public function test_load_fetches_profile_data()
    {
        $id = mt_rand(1, 999);
        $client = $this->getMockHttpClient(['post']);
        $client->expects($this->once())
            ->method('post')
            ->with(Profile::API_PROFILE_URL, ['form_params' => ['job' => 3, 'user_no' => $id]])
            ->willReturn(
            );
        $profile = $this->getMockProfile(null, $id, $client);
        $profile->load();
    }

    /**
     * @param array $methods
     * @param int|null $id
     * @param ClientInterface|null $httpClient
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|Profile
     */
    protected function getMockProfile(array $methods = null, int $id = null, ClientInterface $httpClient = null)
    {
        $id = $id ?: mt_rand(1, 99999);
        $httpClient = $httpClient ?: $this->getMockHttpClient();
        return $this->getMockBuilder(Profile::class)
            ->setMethods($methods)
            ->setConstructorArgs([$id, $httpClient])
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|ClientInterface
     */
    protected function getMockHttpClient($methods = [])
    {
        return $this->getMockBuilder(ClientInterface::class)
            ->setMethods($methods)
            ->getMockForAbstractClass();
    }
}
