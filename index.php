<?php
require_once 'idiorm.php';
ORM::configure('sqlite:./test.db');
ORM::configure('return_result_sets', true);
require 'flight/Flight.php';

// posts ##################################################
Flight::route('/posts', function(){
	$rows = ORM::for_table('test')->order_by_desc('updated')->find_many();
	$i = 0;
	foreach($rows as $row){
	    $str[$i]["id"] = $row["id"];
	    $str[$i]["title"] = $row["title"];
	    $str[$i]["text"] = $row["text"];
	    $i++;
	}
        header("Content-Type: application/json; charset=utf-8");
	$arr = Flight::json($str);
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
    Flight::redirect('/posts');
});

// apiupd ##################################################
Flight::route('/apiupd/@id', function($id){
//    $str[0]["id"] = $id;
//    $str[0]["title"] = "mytitle";
        $rows = ORM::for_table('test')->find_one($id);
//    $str[0]["id"] = $rows->id;
//    $str[0]["title"] = $rows->title;
//    $str[0]["text"] = $rows->text;
      $str["id"] = $rows->id;
      $str["title"] = $rows->title;
      $str["text"] = $rows->text;
	$arr = Flight::json($str);
        header("Content-Type: application/json; charset=utf-8");
        echo $arr;
});

// testsingle ##################################################
Flight::route('/testsingle/@id', function($id){
        $row = ORM::for_table('test')->find_one($id);
    $str[0]["id"] = $row->id;
    $str[0]["title"] = $row->title;
    $str[0]["text"] = $row->text;
	$arr = Flight::json($str);
        header("Content-Type: application/json; charset=utf-8");
        echo $arr;
});

// testtags ##################################################
Flight::route('/testtags/@id', function($id){
        //$maps = ORM::for_table('map')->where('testid',$id)->find_many();
        $rows = ORM::for_table('map')->where('testid', $id)->find_many();
	foreach($rows as $row){
          $tagA[] = $row["tagid"];//使用タグのidを取得する
	}
        $rowsA = ORM::for_table('tag')->where_in('id', $tagA)->find_many();
        $rowsB = ORM::for_table('tag')->where_not_in('id', $tagA)->find_many();

	$i = 0;
	foreach($rowsA as $row){//配列を作成する
	    $strA[$i]["id"] = $row["id"];
	    $strA[$i]["title"] = $row["title"];
	    $i++;
	}

	$i = 0;
	foreach($rowsB as $row){//配列を作成する
	    $strB[$i]["id"] = $row["id"];
	    $strB[$i]["title"] = $row["title"];
	    $i++;
	}
//
//        $rowsB = ORM::for_table('tag')->find_many();//すべてのタグid
//	foreach($rowsB as $row){//配列を作成する
//	    $tagB[] = $row["id"];
//	}
//	$tagC = array_diff($tagB, $tagA);//配列の差を取得する
//	$tagD = array_values($tagC);//連想配列を配列にする
//
//        $rowsD = ORM::for_table('tag')->where_in('id', $tagD)->find_many();
//	$i = 0;//使用していないタグid
//	foreach($rowsD as $row){
//	    $strD[$i]["id"] = $row["id"];
//	    $strD[$i]["title"] = $row["title"];
//	    $i++;
//	}

	$strE[] = $strA;//使用しているタグを0,不使用のタグを1に入れた配列
	//$strE[] = $strD;
	$strE[] = $strB;

        $arr = Flight::json($strE);
        header("Content-Type: application/json; charset=utf-8");
        echo $arr;
});

// apiput ##################################################
Flight::route('/apiput', function(){
    $id = $_POST['id'];
    $row = ORM::for_table('test')->find_one($id);
    $row->text = $_POST['text'];
    $row->save();
    Flight::redirect('/posts');
});

// apidel ##################################################
flight::route('/apidel/@id', function($id){
        $row = ORM::for_table('test')->find_one($id);
	$row->delete();
	flight::redirect('/posts');
});

// title_up ##################################################
Flight::route('/apiup/@id', function($id){
	$row = ORM::for_table('test')->find_one($id);
	$row->updated = time();
	$row->save();
	flight::redirect('/posts');
});

// tags ##################################################
Flight::route('/tags', function(){
	$rows = ORM::for_table('tag')->order_by_desc('updated')->find_many();
	$i = 0;
	foreach($rows as $row){
	    $str[$i]["id"] = $row["id"];
	    $str[$i]["title"] = $row["title"];
	    $i++;
	}
        header("Content-Type: application/json; charset=utf-8");
	$arr = Flight::json($str);
        echo $arr;
});

// tagins ##################################################
Flight::route('/tagins', function(){
    $row = ORM::for_table('tag')->create();
    $row->title = $_POST['text'];
    $row->save();
    Flight::redirect('/tags');
});

// tagput ##################################################
Flight::route('/tagput', function(){
    $id = $_POST['id'];
    $row = ORM::for_table('tag')->find_one($id);
    $row->title = $_POST['title'];
    $row->save();
    Flight::redirect('/tags');
});

// tagdel ##################################################
flight::route('/tagdel/@id', function($id){
        $row = ORM::for_table('tag')->find_one($id);
	$row->delete();
	flight::redirect('/tags');
});

// tag_up ##################################################
Flight::route('/tagup/@id', function($id){
	$row = ORM::for_table('tag')->find_one($id);
	$row->updated = time();
	$row->save();
	flight::redirect('/tags');
});

// test ##################################################
Flight::route('/teststr', function(){
        //$str[] = "teststr";
        //$maps = ORM::for_table('map')->where('testid',62)->find_many();
    //ここにraw_queryを書く
    //$rows = ORM::for_table('map')->raw_query('SELECT * FROM map')->find_many();
    $rows = ORM::for_table('map')->raw_query('SELECT id FROM tag except select tagid from map where testid=62')->find_many();
	$i = 0;
	foreach($rows as $row){
	    $str[$i]["id"] = $row["id"];
//	    $str[$i]["tagid"] = $row["tagid"];
//	    $str[$i]["testid"] = $row["testid"];
	    $i++;
	}
//        header("Content-Type: application/json; charset=utf-8");
//	$arr = Flight::json($str);
//        echo $arr;

//        $str[] = "teststr";
        header("Content-Type: application/json; charset=utf-8");
	$arr = Flight::json($str);
        echo $arr;
});

Flight::start();
