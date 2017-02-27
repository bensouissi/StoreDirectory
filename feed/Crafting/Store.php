<?php
/**
 * Created by PhpStorm.
 * User: Belhassen
 * Date: 26/12/2016
 * Time: 10:56
 */
include("../DataAccess/StoreData.php");
include("../Crafting/Article.php");
class Store {
    public $code;
    public $name;
    public $address;
    public $description;
    public $lat;
    public $longt;
    public $website;
    public $type;
    public $data_access;
    public $articles = array();

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

    /* fill new instance of $this */
    protected function fill( array $row ) {
        // fill all properties from array
        $this->code = uniqid(strtoupper($row['type']).'_');
        $this->name = $row['name'];
        $this->description = $row['description'];
        $this->lat = $row['lat'];
        $this->longt = $row['long'];
        $this->website = $row['website'];
        $this->type = $row['type'];
    }

    /* ordering data layer to insert new db line */
    function add(){
        $this->data_access = new StoreData($this);
        $flag['code'] = '';
        if($this->data_access->insertDB()){
            $flag['code'] = 1;
        }
        echo $this->name;
        echo $this->data_access->store->name;
        echo json_encode($flag);
    }

    /* restore all stores from db (no criteria) */
    function store_fetch_all(){
        $rows =$this->data_access->search();
        echo json_encode($rows);
    }

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

    public function getLat(){return $this->lat;}

    public function getLong(){return $this->long;}

    public function getWebsite(){return $this->website;}

    public function getType(){return $this->type;}


}
