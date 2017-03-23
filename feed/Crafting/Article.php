<?php
/**
 * Created by PhpStorm.
 * User: Belhassen
 * Date: 26/12/2016
 * Time: 10:56
 */

include_once("../Crafting/Store.php");
include_once("../DataAccess/ArticleData.php");

class Article {
    public $code;
    public $name;
    public $price;
    public $sale_percentage;
    public $type;
    public $store;

    function __construct()
    {
    }

    public static function NewArticle(array $row)
    {
        $instance = new self();
        $instance->fill($row);
        return $instance;
    }
    public static function NewestArticles(){
        $data_access = new ArticleData();
        echo json_encode(array("data" => $data_access->get_newest()));
    }
    public static function FeaturedArticles(){
        $data_access = new ArticleData();
        echo json_encode(array("data" => $data_access->get_featured()));
    }

    public static function TrustedArticles(){
        $data_access = new ArticleData();
        echo json_encode(array("data" => $data_access->get_special()));
    }

    private function fill(array $row)
    {

    }

    public static function ArticleByName($name)
    {
        $instance = new self();
        $instance->name = $name;
        $data_access = new ArticleData($instance);
        echo json_encode(array("data" => $data_access->search()));
    }

    public static function ArticleBySalePercentage($sale_percentage)
    {
        $instance = new self();
        $instance->sale_percentage = $sale_percentage;
        $data_access = new ArticleData($instance);
        echo json_encode(array("data" => $data_access->searchBySalePercentage()));
    }

    public static function ArticleByStore($storeName)
    {
        $instance = new self();
        $instance->store = new Store();
        $instance->store->name = $storeName;
        $data_access = new ArticleData($instance);
        echo json_encode(array("data" => $data_access->searchByStore()));
    }

    public static function ArticleByType($type)
    {
        $instance = new self();
        $instance->store = new Store();
        $instance->type = $type;
        $data_access = new ArticleData($instance);
        echo json_encode(array("data" => $data_access->searchByType()));
    }

    public function add()
    {
        $data_access = new ArticleData($this);
        $flag['code'] = '';
        if($data_access->insertDB()){
            $flag['code'] = 1;
        }
        echo json_encode($flag);
    }
    /**
     * @return mixed
     */
    public function getStore()
    {
        $data_access = new ArticleData($this);
        return $this->store = $data_access->select_owned_store();
    }
    /**
     * @param mixed $store
     */
    public function setStore($store)
    {
        $this->store = $store;
    }
}