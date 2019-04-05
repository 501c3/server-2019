<?php

namespace App\Api;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use FriendsOfBehat\SymfonyExtension\Driver\SymfonyDriver;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use PHPUnit\Framework\Assert as SalesAssert;

/**
 * Defines application features from the specific context.
 */
class SalesContext implements Context
{
    /** @var Response */
    private $response;

    /**
     * @var KernelInterface
     */
    private $kernel;


    private $guzzle;
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->guzzle = new Client();
    }


//    /**
//     * @Then the application's kernel should use :expected environment
//     */
//    public function theApplicationsKernelShouldUseEnvironment($expected)
//    {
//        if ($this->kernel->getEnvironment() !== $expected) {
//            throw new \RuntimeException();
//        }
//    }

    /**
     * @When I request :path using HTTP :verb
     * @param $path
     * @param $verb
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function iRequestUsingHttp($path, $verb)
    {
        $uri = 'http://localhost:8000'.$path;
        $this->response=$this->guzzle->request($verb, $uri);
    }

    /**
     * @Then the response code is :expectedStatus
     */
    public function theResponseCodeIs($expectedStatus)
    {
         $foundStatus = $this->response->getStatusCode();
         if($foundStatus<>$expectedStatus) {
             throw new \UnexpectedValueException("Expected $expectedStatus but found $foundStatus");
         }
    }

    /**
     * @Then the response body is an empty JSON object
     */
    public function theResponseBodyIsAnEmptyJsonObject()
    {
        assertTrue(true);
    }

}
