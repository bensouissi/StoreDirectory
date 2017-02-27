<?php
/**
 * Created by PhpStorm.
 * User: Belhassen
 * Date: 26/12/2016
 * Time: 10:55
 */
include_once("../Crafting/Store.php");
include_once ("../Crafting/Article.php");
class StoreData {
    private $ukey;
    private $cfg;
    private $dbh;
    public  $store;
    private $articles = array();

    function __construct(Store $store)
    {
        $this->cfg = parse_ini_file("../../config/app.ini");
        $this->dbh = new PDO("mysql:dbname=".$this->cfg['db_name'].";host=".$this->cfg['db_host'], $this->cfg['db_user'], $this->cfg['db_password']);
        $this->store = $store;
    }

    public function insertDB(){
        try{
            $requet = "INSERT INTO store (ukey,code,name,description,website,lat,longt,type) VALUES (:ukey,:code,:name,:description,:website,:lat,:longt,:type)";

            $q = $this->dbh->prepare($requet);

            if($q->execute(array(
                    'ukey' => $this->GUID(),
                    'code' => $this->store->code,
                    'name' => $this->store->name,
                    'description' => $this->store->description,
                    'website' => $this->store->website,
                    'lat' => $this->store->lat,
                    'longt' => $this->store->longt,
                    'type' => $this->store->type

                )) == true)
                return true;
            else
                return false;

        }catch (PDOException $e){
            echo "Error: ".$e;
        }
    }


    public function search()
    {
        try{
            $query=$this->dbh->query("select * from store WHERE name LIKE '%".$this->store->name."%'");
            $searched_stores = array();
            
            while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $store= new Store();
                $store->name = $row['name'];
                $store->code = $row['code'];
                $store->description = $row['description'];
                $store->website = $row['website'];
                $store->lat = $row['lat'];
                $store->longt = $row['longt'];
                $store->type = $row['type'];
                array_push($searched_stores, $store);
            }

            return $searched_stores;
        }catch (PDOException $e){
            echo "Error: ".$e;
        }
        return $searched_stores;
    }

    /**
     * @return Store
     */
    public function getStoreByCode()
    {
        try{
            $query=$this->dbh->query("select * from store where code= '".$this->store->code."'");
            $store= new Store();

            $row = $query->fetch(PDO::FETCH_ASSOC);

            $store->name = $row['name'];
            $store->code = $row['code'];
            $store->description = $row['description'];
            $store->website = $row['website'];
            $store->lat = $row['lat'];
            $store->longt = $row['longt'];
            $store->type = $row['type'];
            return $this->store = $store;

        }catch (PDOException $e){
            echo "Error: ".$e;
        }
    }

    /**
     * @return mixed
     */
    public function getUkey()
    {
        try{
            $query=$this->dbh->query("select * from store where code= '".$this->store->code."'");

            $row = $query->fetchColumn(0);
            return $row;

        }catch (PDOException $e){
            echo "Error: ".$e;
        }
    }

    /**
     * @return mixed
     */
    public function getArticles()
    {
        try{
            $query=$this->dbh->query("select * from article where code= '".$this->store->code."'");

            while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $article= new Article();
                $article->name = $row['name'];
                $article->code = $row['code'];
                $article->price = $row['price'];
                $article->sale_percentage = $row['sale_per'];
                $article->type = $row['type'];
                array_push($this->articles, $article);
                return $this->articles;
            }
        }catch (PDOException $e){
            echo "ERROR: ".$e;
        }
    }


    public function GUID(){
        if (function_exists('com_create_guid')){
            return com_create_guid();
        }else{
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = chr(123)// "{"
                .substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12)
                .chr(125);// "}"
            return $uuid;
        }
    }
}