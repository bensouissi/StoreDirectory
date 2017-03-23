<?php
/**
 * Created by PhpStorm.
 * User: Belhassen
 * Date: 12/01/2017
 * Time: 11:35
 */
include_once("../Crafting/Article.php");

if( isset($_GET['request']) && ($_GET['request'] == 'add') ){

}

if( isset($_GET['request']) && ($_GET['request'] == 'rate_article') ){

}

if( isset($_GET['request']) && ($_GET['request'] == 'retrieve_all') ){

}

if( isset($_GET['request']) && ($_GET['request'] == 'retrieve_newest') ){
    Article::NewestArticles();
}

if( isset($_GET['request']) && ($_GET['request'] == 'retrieve_featured') ){
    Article::NewestArticles();
}
