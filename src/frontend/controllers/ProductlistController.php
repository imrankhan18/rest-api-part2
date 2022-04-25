<?php

use Phalcon\Mvc\Controller;

class ProductlistController extends Controller
{
    public function indexAction()
    {

        $url = "http://192.168.2.55:8080/api/productlist";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        $res = json_decode($response, true);
        foreach ($res as $key => $value) {
            print_r($value);

            $this->mongo->Frontend->products->insertOne(
                [
                    '_id' => new \MongoDB\BSON\ObjectId($value['_id']['$oid']),
                    'name' => $value['name'],
                    'price' => $value['price'],
                    'category' => $value['category'],
                    'quantity' => $value['quantity'],
                ]
            );
        }
    }
    public function listAction()
    {
        $login = $this->request->getPost();
        if (count($login) > 0) {
            if ($login['role'] == 'admin' && $login['email'] != '' && $login['password'] != '') {
                $list = $this->mongo->Frontend->products->find();
                foreach ($list as $k => $v) {
                    $val[] = json_decode(json_encode($v), true);
                }
                $this->view->productlist = $val;
            } else {
                echo "fill correct details";
            }
        }
    }
    public function updatestockAction()
    {
        $data = $this->request->getPost();


        $this->mongo->Frontend->products->updateOne(
            [
                '_id' => new \MongoDB\BSON\ObjectId($data['_id']['$oid'])
            ],
            [
                '$set' => [
                    'quantity' => $data['quantity'],
                ]
            ]

        );
    }
    public function addproductAction()
    {
        $productdata = $this->request->getPost();
        $this->mongo->Frontend->products->insertOne(
            [
                '_id' => new \MongoDB\BSON\ObjectId($productdata['_id']['$oid']),
                'name' => $productdata['name'],
                'price' => $productdata['price'],
                'category' => $productdata['category'],
                'quantity' => $productdata['quantity'],

            ]
        );
    }
}
