<?php
/**
 * Created by PhpStorm.
 * User: Belhassen
 * Date: 26/12/2016
 * Time: 11:10
 */
include_once("../Crafting/Store.php");


if( isset($_GET['request']) && ($_GET['request'] == 'get_all_stores') ){
    $store= new Store();
    $store->store_fetch_all();
}


if( isset($_POST['request']) && ($_POST['request'] == 'add') ){

    $row = array(
        "name" => $_POST['name'],
        "link" =>$_POST['link'],
        "promotag" => $_POST['promotag'],
        "code" => $_POST['username']
    );

    $store = Store::NewStore($row);
    $store->add();
}

if( isset($_GET['request']) && ($_GET['request'] == 'check_existence') ){
    //get request
    //send query and test
        //existent : response with store data
        //none : response 'false'
    $store = new Store();
    $store->link = $_GET['link'];
    $store->exist();
}


if( isset($_POST['request']) && ($_POST['request'] == 'check_link') ){
    $store = new Store();
    $store->link = $_POST['data'];
    $store->exist();
}

if( isset($_GET['request']) && ($_GET['request'] == 'retrieve_featured') ){
    $store = new Store();

}
if( isset($_POST['request']) && ($_POST['request'] == 'store_by_code') ){
    $store = new Store();
    $store->code = $_POST['data'];

}

