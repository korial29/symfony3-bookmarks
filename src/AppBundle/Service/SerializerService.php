<?php

namespace AppBundle\Service;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * REST client service.
 */
class SerializerService
{
    private $serializer;
    const CIRCULAR_REF_LIMIT = 2;

    function __construct()
    {
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(self::CIRCULAR_REF_LIMIT);
        // Add Circular reference handler
        $normalizer->setCircularReferenceHandler(function ($object) {
        	return $object->getId();
        });
        $this->serializer = new Serializer(array($normalizer), array(new JsonEncoder()));
    }

    public function serialize($data){
        return $this->serializer->serialize($data, 'json');
    }

    public function normalize($data){
        return $this->serializer->normalize($data);
    }
}
