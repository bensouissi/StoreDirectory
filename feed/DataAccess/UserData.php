<?php

/**
 * Created by PhpStorm.
 * User: Belhassen
 * Date: 07/03/2017
 * Time: 08:02
 */
include_once ("../Crafting/User.php");
include_once ("../Crafting/Store.php");
class UserData
{
    private $ukey;
    private $cfg;
    private $dbh;
    public  $user;
    private $stores = array();

    function __construct(User $user)
    {
        $this->cfg = parse_ini_file("../../config/app.ini");
        $this->dbh = new PDO("mysql:dbname=".$this->cfg['db_name'].";host=".$this->cfg['db_host'], $this->cfg['db_user'], $this->cfg['db_password']);
        $this->user = $user;
    }

    public function insertDB(){
        try{
            $request = "INSERT INTO account (ukey,code,date_added,email,username,userpass,subtype,birthday) 
                        VALUES (:ukey,:code,:date_added,:email,:username,:userpass,:subtype,:birthday)";
            $q = $this->dbh->prepare($request);

            if($q->execute(array(
                    'ukey' => $this->GUID(),
                    'code' => $this->user->code,
                    'date_added' => date("Y-m-d H:i:s"),
                    'email' => $this->user->email,
                    'username' => $this->user->name,
                    'userpass' => $this->user->password,
                    'subtype' => 'regular',
                    'birthday' => date_format($this->user->birthday, 'Y-m-d H:i:s')

                )) == true)
                return true;
            else
                return false;

        }catch (PDOException $e){
            echo "Error: ".$e;
        }
    }

    public function login_check(){
        try{
            //check user existance
            $query=$this->dbh->query("select * from account where email= '".$this->user->email."' and userpass= '".$this->user->password."'");
            $flag['code'] = '0';
            //if it exists
            if ($row = $query->fetch()) {
                //return fetched user
                $this->user->name = $row['username'];
                $this->user->birthday = $row['birthday'];
                $this->user->code = $row['code'];
                $this->user->date_added = $row['date_added'];

                $flag['code'] = 1;
                $flag['data'] = $this->user;
                return $flag ;
            }//if not
            else
            {
                $flag['code'] = 0;
                return $flag;
            }

        }catch (PDOException $e){
            echo "ERROR: ".$e;
        }
    }

    public function getUser(){

        if($this->user->code != null)
            try{
                $query=$this->dbh->query("select ukey from account where code= '".$this->user->code."'");
                $row = $query->fetchColumn(0);
                return $row;

            }catch(PDOException $e){
                echo "ERROR: ".$e;
            }

        /*if($this->user->email != null)
            try{
                $query=$this->dbh->query("select ukey from account where email= '".$this->user->email."'");
                if($query->fetch())
                    return $query->fetchColumn();

            }catch(PDOException $e){
                echo "ERROR: ".$e;
            }*/
    }

    public function getStores(){
        try{
            $query=$this->dbh->query("select code,date_added,name,link from store where account_ukey= '".$this->user->getUser()."'");
            while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $store= new Store();
                $store->name = $row['name'];
                $store->code = $row['code'];
                $store->date_added = $row['date_added'];
                $store->link = $row['link'];
                array_push($this->stores, $store);
            }

            return $this->stores;

        }catch(PDOException $e){
            echo "ERROR: ".$e;
        }
    }
   /* public function search()
    {
        try{
            $query=$this->dbh->query("select * from account WHERE name LIKE '%".$this->user->name."%'");
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
    }*/

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