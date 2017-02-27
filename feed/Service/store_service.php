<?php
/**
 * Created by PhpStorm.
 * User: Belhassen
 * Date: 26/12/2016
 * Time: 11:10
 */
include("../Crafting/Store.php");


if( isset($_GET['request']) && ($_GET['request'] == 'get_all_stores') ){
    $store= new Store();
    $store->store_fetch_all();
}

if( isset($_GET['request']) && ($_GET['request'] == 'search') ){
    $store= Store::StoreByName($_GET['name']);
}

if( isset($_GET['request']) && ($_GET['request'] == 'add') ){

    $row = array(
        "name" => $_POST['name'],
        "description" =>$_POST['description'],
        "adress" => $_POST['adress'],
        "website" => $_POST['website'],
        "lat" => $_POST['lat'],
        "long" => $_POST['long'],
        "type" => $_POST['type']
    );
    $row = array(
        "code" => "ds12257dsq6qffs",
        "name" => "test",
        "description" =>"testing insert",
        "adress" => "localhost",
        "website" => "www.test-insert.com",
        "lat" => "qsd",
        "long" => "qqsd",
        "type" => "dev"
    );

    $store = Store::NewStore($row);
    $store->add();
    echo "code".$store->code;

}

