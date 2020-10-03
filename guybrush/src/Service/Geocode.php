<?php


namespace App\Service;


use App\Entity\Poi;

class Geocode
{
    private string $apiKey;
    private string $urlTemplate;

    public function __construct(string $apiKey, string $urlTemplate)
    {
        $this->apiKey = $apiKey;
        $this->urlTemplate = $urlTemplate;
    }

    /**
     * @param Poi $p
     * @return array
     */
    public function callWebservice(Poi $p)
    {
        $url = sprintf($this->urlTemplate, $this->apiKey, $p->getLat(), $p->getLon());
        $res = curl_init($url);
        curl_setopt($res, CURLOPT_RETURNTRANSFER, true);
        $output = json_decode(curl_exec($res), JSON_OBJECT_AS_ARRAY);
        curl_close($res);

        return $output;
    }
}
