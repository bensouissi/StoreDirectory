<?php
/**
 * Created by PhpStorm.
 * User: Belhassen
 * Date: 26/12/2016
 * Time: 10:55
 */
include_once("../Crafting/Store.php");
include_once ("../Crafting/Article.php");
include_once ("../Crafting/User.php");
include_once ("UserData.php");
class StoreData {
    private $ukey;
    private $cfg;
    private $dbh;
    public $store;
    private $articles = array();

    function __construct(Store $store)
    {
        $this->cfg = parse_ini_file("../../config/app.ini");
        $this->dbh = new PDO("mysql:dbname=".$this->cfg['db_name'].";host=".$this->cfg['db_host'], $this->cfg['db_user'], $this->cfg['db_password']);
        $this->store = $store;
    }

    public function insertDB(){
        try{
            $user = new User();
            $user->code = $this->store->user;
            $user_data = new UserData($user);

            $requet = "INSERT INTO store (ukey,code,date_added,name,link,promotag,account_ukey) VALUES (:ukey,:code,:date_added,:name,:link,:promotag,:account_ukey)";

            $q = $this->dbh->prepare($requet);

            if($q->execute(array(
                    'ukey' => $this->GUID(),
                    'code' => $this->store->code,
                    'date_added' => date("Y-m-d H:i:s"),
                    'name' => $this->store->name,
                    'link' => $this->store->link,
                    'promotag' => $this->store->promotag,
                    'account_ukey' => $user_data->getUser()

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

    //return true if the requested store is existent and fill the requested store with data
    public function  checkStore(){

        try{
            $query=$this->dbh->query("select * from store where link= '".$this->store->link."'");

            if($row = $query->fetch()){
                $this->store->code = $row['code'];
                $this->store->address = $row['adress'];
                $this->store->date_added = $row['date_added'];
                $this->store->name = $row['name'];
                $this->store->website = $row['website'];
                $this->store->promotag = $row['promotag'];
                return true;
            }
            else{
                return false;
            }
        }
        catch (PDOException $e){
            echo "ERROR: ".$e;
        }
    }

    public function checkLink(){
        $query=$this->dbh->query("select * from store where link= '".$this->store->link."'");
        if($row = $query->fetch())
            return true;
        else
            return false;
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