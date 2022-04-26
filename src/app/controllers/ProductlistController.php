<?php

use Phalcon\Mvc\Controller;

class ProductlistController extends Controller
{
    public function indexAction()
    {
        $ip=$this->config->ip;
        $url = "http://192.168.2.55:8080/api/productlist";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        echo "<pre>";
        
        $res =json_decode($response, true);
        // print_r($res);
        // die;
        $this->view->productlist = $res['data'];
    }
    public function addAction()
    {
    }
    public function editproductAction()
    {
        $id = $this->request->getQuery();
        $this->view->edit = $id;
    }
    public function updateproductAction()
    {
        $update = $this->request->getPost('update');
        $id = $this->request->getPost('id');
        $this->mongo->rest_api->products->UpdateOne(
            [
                '_id' => new \MongoDB\BSON\ObjectId($id)
            ],
            [
                '$set' => [
                    'quantity' => $update,
                ]
            ]
        );
        $productup = $this->mongo->rest_api->products->findOne(
            [
                '_id' => new \MongoDB\BSON\ObjectId($id)
            ]
        );
        $this->eventsManager->fire('webhooks:webhooks', $this, $productup);
        $this->response->redirect('/productlist');
    }

    public function addproductAction()
    {
        $products = $this->request->getPost();
        $data = $this->mongo->rest_api->products->insertOne([
            'name' => $products['name'],
            'price' => $products['price'],
            'category' => $products['category'],
            'quantity' => $products['quantity'],
        ]);
        $id=json_decode(json_encode($data->getInsertedId()), 1);
        $productfind=$this->mongo->rest_api->products->findOne(['_id'=>new \MongoDB\BSON\ObjectId($id['$oid'])]);
        $res=$this->eventsManager->fire('webhooks:addproduct', $this, $productfind);
        $this->response->redirect('/frontend/productlist/list/');
    }
}
