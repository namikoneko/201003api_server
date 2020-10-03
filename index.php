<?php
require_once 'idiorm.php';
ORM::configure('sqlite:./test.db');
ORM::configure('return_result_sets', true);
require 'flight/Flight.php';

// postlist ##################################################
Flight::route('/postlist', function(){
//Flight::route('/postlist', function(){
	$rows = ORM::for_table('test')->find_many();
	//echo var_dump($rows);
	$i = 0;
	foreach($rows as $row){
	    $str[$i]["id"] = $row["id"];
	    $str[$i]["title"] = $row["title"];
	    $str[$i]["text"] = $row["text"];
	    $i++;
	}
	//echo header("Content-Type: application/json; charset=utf-8");
	//echo $str;
	//$arr = json_encode($str, JSON_PRETTY_PRINT);
	$arr = Flight::json($str);


	//Flight::render('api', array('arr' => $arr));
        header("Content-Type: application/json; charset=utf-8");
        echo $arr;
});

// apiins ##################################################
Flight::route('/apiins', function(){
    //$row = ORM::for_table('hoge')->create();
    $row = ORM::for_table('test')->create();
    //$row->a = $_POST['text'];
    $row->text = $_POST['text'];
    $row->save();
    //echo "apiins";
    Flight::redirect('/postlist');
});

// apiupd ##################################################
Flight::route('/apiupd/@id', function($id){
//    $str[0]["id"] = $id;
//    $str[0]["title"] = "mytitle";
        $rows = ORM::for_table('test')->find_one($id);
//	$i = 0;
//	foreach($rows as $row){
//	    $str[$i]["id"] = $row["id"];
//	    $str[$i]["title"] = $row["title"];
//	    $i++;
//	}
    $str[0]["id"] = $rows->id;
    $str[0]["title"] = $rows->title;
    $str[0]["text"] = $rows->text;
        $arr = json_encode($str, JSON_PRETTY_PRINT);
        header("Content-Type: application/json; charset=utf-8");
        echo $arr;
});

// apiput ##################################################
Flight::route('/apiput', function(){
    /*
        $str["put"] = "myput";
        header("Content-Type: application/json; charset=utf-8");
        $arr = json_encode($str, JSON_PRETTY_PRINT);
        echo $arr;
    */

    $id = $_POST['id'];
    $row = ORM::for_table('test')->find_one($id);
    $row->text = $_POST['text'];
    $row->save();
    Flight::redirect('/postlist');
});

// apidel ##################################################
flight::route('/apidel/@id', function($id){
        $row = ORM::for_table('test')->find_one($id);
	$row->delete();
	flight::redirect('/postlist');
});

Flight::start();
