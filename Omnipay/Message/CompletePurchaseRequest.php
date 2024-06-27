<?php

namespace Omnipay\MercadoPago\Message;

class CompletePurchaseRequest extends AbstractRequest
{
    protected $liveEndpoint = 'https://api.mercadopago.com/collections/notifications/';
    protected $testEndpoint = 'https://api.mercadolibre.com/sandbox/collections/notifications/';


    public function getData()
    {
        //get information about collection
        $id = $this->httpRequest->query->get('collection_id');
        $url = $this->getEndpoint() . "$id?access_token=" . $this->getAccessToken();
        $httpRequest = $this->httpClient->request(
            'GET',
            $url,
            array(
                'Content-type' => 'application/json',
            )
        );
        $response = json_decode($httpRequest->getBody()->getContents(), true);
        return isset($response['collection']) ? $response['collection'] : null;
    }

    public function sendData($data)
    {
        return $this->createResponse($data);
    }

    protected function createResponse($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }

}

?>
