<?php
namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class AbstractWebTestCase extends PantherTestCase
{
    /** @var KernelBrowser */
    protected $client;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->client = null;
    }
  
    /**
     * @param bool $decodeAsArray
     * @return null|\stdClass|array
     */
    protected function getDecodedResponse(bool $decodeAsArray = true)
    {
        $response = $this->client->getResponse()->getContent();
        if (!$this->client->getResponse()->isOk()) {
            return null;
        }
        $decodedResponse = json_decode($response, $decodeAsArray);
        return $decodedResponse;
    }
}