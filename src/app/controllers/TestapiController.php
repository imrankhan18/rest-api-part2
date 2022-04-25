<?php
require_once '../../vendor/autoload.php';

use Phalcon\Mvc\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class TestapiController extends Controller
{
    public function indexAction()
    {
    }
    /**
     * test api by adding  order
     *
     * @return void
     */
    public function orderAction()
    {
        $order = $this->request->getPost();
        // echo "<pre>";
        // print_r($order);
        // die;
        $url = "http://192.168.2.55:8080/";
        $client = new Client(
            [


                'base_uri' => $url,
                'headers' => [
                    'Token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vZXhhbXBsZS5vcmciLCJhdWQiOiJodHRwOi8vZXhhbXBsZS5jb20iLCJpYXQiOjEzNTY5OTk1MjQsIm5iZiI6MTM1NzAwMDAwMCwibmFtZSI6IkltcmFuIEtoYW4iLCJlbWFpbCI6ImlraWtAZ21haWwuY29tIiwiaWQiOiI2MjYyNmRhZDE1MzEyMjRmYTEwNjAxZDIiLCJyb2xlIjoiYWRtaW4ifQ.62zVeZBEom2FVW5bnAZV2mn50T8Up0gwQv5HlmwQdVg'
                ]

            ]

        );
        $response = $client->request('POST', '/api/addorder', ['form_params' => $order]);
        $response = json_decode($response->getBody()->getContents(), true);
        $this->response->redirect('/orderlist/list/');
    }
    /**
     * update order status
     *
     * @return void
     */
    public function updatestatusAction()
    {
        $orderstatus = $this->request->getPost();
        // print_r($orderstatus);
        // die;
        $url = "http://192.168.2.55:8080/";
        $client = new Client(
            [
                'base_uri' => $url,
            ]

        );
        $response = $client->request('PUT', '/api/order/update', ['form_params' => $orderstatus]);
        $response = json_decode($response->getBody()->getContents(), true);
        echo "Order Status Updated Sucessfuly!!!";
    }
    /**
     * add products
     *
     * @return void
     */
    public function addproductAction()
    {
        $product = $this->request->getPost();
        // print_r($orderstatus);
        // die;
        $url = "http://192.168.2.55:8080/";
        $client = new Client(
            [
                'base_uri' => $url,
            ]

        );
        $response = $client->request('POST', '/api/products/add', ['form_params' => $product]);
        $response = json_decode($response->getBody()->getContents(), true);
        echo "Product Added Sucessfuly!!!";
    }
    public function updateproductAction()
    {
        $productup = $this->request->getPost();
       
        $url = "http://192.168.2.55:8080/";
        $client = new Client(
            [
                'base_uri' => $url,
            ]

        );
        $response = $client->request('POST', '/api/products/update', ['form_params' => $productup]);
        echo "<pre>";
        // print_r($response);
        // die;
        $response = json_decode($response->getBody()->getContents(), true);
        $this->response->redirect('/productlist');
        echo "Product Updated Sucessfuly!!!";
    }
}
