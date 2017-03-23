<?php
/**
 * Created by PhpStorm.
 * User: Belhassen
 * Date: 07/03/2017
 * Time: 00:07
 */
include_once ("../Crafting/User.php");

if( isset($_GET['request']) && ($_GET['request'] == 'add') ){

    $row = array(
        "name" => $_GET['name'],
        "email" =>$_GET['email'],
        "password" => $_GET['password'],
        "birthday" => $_GET['birthday']
    );

    $user = User::NewUser($row);
    $user->add();
    echo "code".$user->code;

}


if( isset($_GET['request']) && ($_GET['request'] == 'login_permission') ){

    $row = array(
        "email" =>$_GET['email'],
        "password" => $_GET['password']
    );

    $user = User::Login($row);
}

if(isset($_POST['request']) && ($_POST['request'] == 'store_list')){

    $user = new User();
    $user->code = $_POST['User'];
    $user->userStores();
}