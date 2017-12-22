<?php

namespace AppBundle\Service;

use AppBundle\Service\RestClientService;
use AppBundle\Entity\Bookmark;

/**
 * REST client service.
 */
class OembedService extends RestClientService
{
    private $httpClient;
    const FLICKR_PATTERN = "/flickr/";
    const VIMEO_PATTERN = "/vimeo/";
    const VIMEO_URL = "https://vimeo.com/api/oembed.json/";
    const FLICKR_URL = "https://www.flickr.com/services/oembed/";

    public function callOembed($url)
    {
        $result = ['error' => false];
        $apiUrl = self::FLICKR_URL;
        $type = Bookmark::PHOTO_TYPE;

        if(preg_match(self::VIMEO_PATTERN, $url)){
            $apiUrl = self::VIMEO_URL;
            $type = Bookmark::VIDEO_TYPE;
        }

        $this->setBaseUri($apiUrl);
        $result = $this->sendRequest("?".http_build_query(['format' => 'json', 'url' => $url]), 'GET');
        if(!$result['error']){
            $result['result']['type'] = $type;
        }

        return $result;
    }

}
