<?php
/**
 * Created by PhpStorm.
 * User: Belhassen
 * Date: 26/12/2016
 * Time: 10:56
 */
include_once("../DataAccess/StoreData.php");
include_once("../Crafting/Article.php");
include_once("../Crafting/User.php");
class Store {
    public $code;
    public $date_added;
    public $name;
    public $address;
    public $description;
    public $link;
    public $website;
    public $type;
    public $promotag;
    public $user;
    public $data_access;

    function __construct()
    {
    }

    public static function NewStore(array $row)
    {
        $instance = new self();
        $instance->fill($row);
        return $instance;
    }


    /* retrieve store(s) by name */
    public static function StoreByName($name)
    {
        $instance = new self();
        $instance->name = $name;
        $data_access = new StoreData($instance);
        echo json_encode(array("data" => $data_access->search()));
    }

    public static function TrustedStores(){
        $data_access = new StoreData();
        echo json_encode(array("data" => $data_access->get_special()));
    }

    /* fill new instance of $this */
    protected function fill( array $row ) {
        // fill all properties from array
        //$this->user = new User();
        $this->code = uniqid('STORE_');
        $this->name = $row['name'];
        $this->link = $row['link'];
        $this->promotag = $row['promotag'];
        $this->user = $row['code'];
    }

    /* ordering data layer to insert new db line */
    function add(){
        $this->data_access = new StoreData($this);
        //$flag['code'] = "0";
        if($this->data_access->insertDB())
            echo json_encode($flag['code'] = 1);
        else
            echo json_encode($flag['code'] = 0);
    }

    /* restore all stores from db (no criteria) */
    function store_fetch_all(){
        $this->data_access = new StoreData();
        $rows =$this->data_access->search();
        echo json_encode($rows);
    }

    //create a data access object
    //send test request
    //wait for :
        //only one store data (requested)
        //false statement (none existent)
    function exist(){
        $data_access = new StoreData($this);
        if(!$data_access->checkLink())
            echo json_encode($flag['code'] = 0);
        else
            echo json_encode($flag['code'] = 1);
    }


    //get articles of this
    function get_this_articles()
    {
        $this->data_access = new StoreData($this);
        $row = $this->data_access->getArticles();
        echo json_encode(array("data" => $row));
    }

    public function getCode(){return $this->code;}

    public function getName(){return $this->name;}

    public function getAdress(){return $this->adress;}

    public function getDescription(){return $this->description;}

    public function getWebsite(){return $this->website;}

    public function getType(){return $this->type;}


}
