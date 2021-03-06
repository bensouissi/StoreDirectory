<?php
/**
 * Created by PhpStorm.
 * User: Belhassen
 * Date: 26/12/2016
 * Time: 10:55
 */
include_once("../Crafting/Article.php");
include_once("../Crafting/Store.php");
class ArticleData {
    private $cfg;
    private $dbh;
    private $article;
    private $store;
    function __construct(Article $article)
    {
        $this->cfg = parse_ini_file("../../config/app.ini");
        $this->dbh = new PDO("mysql:dbname=".$this->cfg['db_name'].";host=".$this->cfg['db_host'], $this->cfg['db_user'], $this->cfg['db_password']);
        $this->article = $article;
    }

    public function insertDB(){
        try{
            $store_access = new StoreData($this->article->store);
            $query = "INSERT INTO article (ukey,code,description,date_added,name,price,type,store) VALUES (:ukey,:code,:description,:date_added,:name,:price,:type,:store)";

            $q = $this->dbh->prepare($query);

            if($q->execute(array(
                    'ukey'       => $this->GUID(),
                    'code'       => $this->article->code,
                    'description'       => $this->article->name,
                    'date_added'      => $this->article->price,
                    'name'   => $this->article->sale_percentage,
                    'price' => $store_access->getUkey(),
                    'type' => $this->article->type,
                    'store' => $this->article->store
                )) == true)
                return true;
            else
                return false;
        }catch (PDOException $e){
            echo "Error: ".$e;
        }
    }

    public function select_owned_store()
    {
        try{
            $store_access = new StoreData($this->article->store);
            $query = $this->dbh->query("select * from store where ukey = '".$store_access->getUkey()."'");
            $row   = $query->fetch(PDO::FETCH_ASSOC);
            $store = new Store();

            $store->name        = $row['name'];
            $store->code        = $row['code'];
            $store->description = $row['description'];
            $store->website     = $row['website'];
            $store->lat         = $row['lat'];
            $store->longt       = $row['longt'];
            $store->type        = $row['type'];

            return $this->article->store = $store;
        }catch (PDOException $e){
            echo "Error: ".$e;
        }
    }
    
    

    public function getUkey(){
        try{
            $query=$this->dbh->query("select * from article where code= '".$this->article->code."'");
            $row = $query->fetchColumn(0);
            return $row;
        }catch (PDOException $e){
            echo "Error: ".$e;
        }
    }

    public function retrieveLatest(){
        $query  = $this->dbh->query("select * from article where date_added >= DATEADD(day, -7, GETDATE() )");

        $articles= array();
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $article= new Article();
            $article->name = $row['name'];
            $article->code = $row['code'];
            $article->price = $row['price'];
            $article->sale_percentage = $row['date_added'];
            $article->type = $row['type'];
            array_push($articles, $article);
            return $articles;
        }
    }



    function GUID(){
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