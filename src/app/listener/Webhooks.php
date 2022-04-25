<?php

namespace App\Listener;

use Phalcon\Di\Injectable;
use Phalcon\Events\Event;
use GuzzleHttp\Client;

class Webhooks extends Injectable
{
    public function webhooks(Event $event)
    {
        $update = json_decode(json_encode($event->getData()), true);

        $url = "http://192.168.2.55:8080/";
        $client = new Client(
            [
                'base_uri' => $url,
            ]

        );
        $response = $client->request('POST', '/frontend/productlist/updatestock', ['form_params' => $update]);
        echo $response->getBody();
    }
    public function addproduct(Event $event)
    {
        $addproduct = json_decode(json_encode($event->getData()), true);
        // echo "<pre>";
        // print_r($addproduct);
        // die;
        $url = "http://192.168.2.55:8080/";
        $client = new Client(
            [
                'base_uri' => $url,
            ]

        );
        $response = $client->request('POST', '/frontend/productlist/addproduct', ['form_params' => $addproduct]);
        echo $response->getBody();
        // die;

    }
}
