<?php

/**
 * Created by PhpStorm.
 * User: Belhassen
 * Date: 06/03/2017
 * Time: 05:00
 */
include_once ("../DataAccess/UserData.php");
class User
{
    public $code;
    public $description;
    public $date_added;
    public $password;
    public $name;
    public $subtype;
    public $birthday;
    public $email;
    public $data_access;

    function __construct()
    {

    }

    public static function NewUser(array $row)
    {
        $instance = new self();
        $instance->fill($row);
        return $instance;
    }


    /* retrieve store(s) by name */
    public static function Login(array $row)
    {
        $instance = new self();
        $instance->email = $row['email'];
        $instance->password = $row['password'];
        $data_access = new UserData($instance);
        
        echo json_encode($data_access->login_check());
    }

    /* fill new instance of $this */
    protected function fill( array $row ) {
        // fill all properties from array
        $this->code = uniqid();
        $this->email = $row['email'];
        $this->name = $row['name'];
        $this->password = $row['password'];
        $date = date_create($row['birthday']);
        $this->birthday = $date;
    }

    /* ordering data layer to insert new db line */
    function add(){
        $this->data_access = new UserData($this);
        $flag['code'] = '';
        if($this->data_access->insertDB()){
            $flag['code'] = 1;
        }
        echo $this->name;
        echo json_encode($flag);
    }


    function getUser(){
        $data_access = new UserData($this);
        return $data_access->getUser();
    }
    function userStores(){
        $data_access = new UserData($this);
        echo json_encode(array("data" => $data_access->getStores()));
    }

}