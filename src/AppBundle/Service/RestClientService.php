<?php

namespace AppBundle\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\SerializerService;

/**
 * REST client service.
 */
class RestClientService
{
    private $serializer;
    private $baseUri;
    function __construct()
    {
        $this->setSerializer();
    }

    public function call(Request $request, $name, $method, $data=array())
    {
        $this->setBaseUri($request->getScheme().'://'.$request->getHttpHost().$request->getBaseUrl()."/");
        return $this->sendRequest($name, $method, $data);
    }

    protected function sendRequest($name, $method, $data=array()){
        $result = ['error' => false];

        $client = new Client(['base_uri' => $this->getBaseUri()]);

        try {
            $res = $client->request($method, $name, [
                'json' => $this->getSerializer()->normalize($data)
            ]);
            $result['result'] = json_decode($res->getBody(), true);

        } catch(RequestException $e){
            $result['error'] = true;
            $result['result'] = $e->getMessage();
        }
        return $result;
    }

    private function setSerializer()
    {
        $this->serializer = new SerializerService();
    }

    private function getSerializer()
    {
        return $this->serializer;
    }

    public function setBaseUri($baseUri)
    {
        $this->baseUri = $baseUri;
    }

    public function getBaseUri()
    {
        return $this->baseUri;
    }
}
