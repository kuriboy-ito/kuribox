<!DOCTYPE html>
<html lang="ja">
    <meta charset="UTF-8">
<head><title>mission_5-1</title></head>
<body>
<body><strong style="font-size=24px">追加フォーム</strong></body>
<form action="" method="post">
        <input type="text" name="na" placeholder="氏名">
        <input type="text" name="com" placeholder="コメント">
        <input type="text" name="pass" placeholder="パスワード">
        <input type="submit" name="submit">
 </form><br>
 
 <body><strong style="font-size=24px">削除フォーム</strong></body>
    <form action="" method="post">
        <input type="number" name="num1" placeholder="削除する番号">
        <input type="text" name="pass1" placeholder="パスワード">
        <input type="submit" name="submit1">
    </form><br>
    
    <body><strong style="font-size=24px">編集フォーム</strong></body>
    <form action="" method="post">
        <input type="number" name="num2" placeholder="編集する番号"><br>
        <input type="text" name="na2" placeholder="編集後の氏名">
        <input type="text" name="com2" placeholder="編集後のコメント">
        <input type="text" name="pass2" placeholder="パスワード">
        <input type="submit" name="submit2">
    </form><br>

<?php 

	// DB接続設定
	$dsn = 'mysql:dbname=データベース名;host=localhost';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	
	//データベース内にテーブル作成
$sql= "CREATE TABLE IF NOT EXISTS tbtest(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name char(32),
    comment TEXT, 
    password TEXT, 
    date DATETIME)";
  $stmt=$pdo->query($sql);
	echo "<hr>";
  
$na=filter_input(INPUT_POST,"na");//新規コメント追加時の名前
$na2=filter_input(INPUT_POST,"na2");//編集後の名前
$com=filter_input(INPUT_POST,"com");//新規コメント
$com2=filter_input(INPUT_POST,"com2");//編集後のコメント
$num1=filter_input(INPUT_POST,"num1");//削除番号
$num2=filter_input(INPUT_POST,"num2");//編集番号
$date = date("Y-m-d H:i:s"); //日時
$pass=filter_input(INPUT_POST,"pass");//新規コメント追加時のパスワード
$pass1=filter_input(INPUT_POST,"pass1");//削除時のパスワード
$pass2=filter_input(INPUT_POST,"pass2");//編集時のパスワード


//データベースの編集
if(!empty($num2)&&!empty($na2)&&!empty($com2)&&!empty($pass2)){//編集する名前, コメント, パスワード入力されたら
    $id = $num2; //編集後の投稿番号
	$name =$na2;//編集後の名前
	$comment =$com2;//編集後のコメント
	$pa=$pass2;//編集時のパスワード
	
	$sql = 'UPDATE tbtest SET name=:name,comment=:comment,
	password=:password, date=:date WHERE id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->bindParam(':password', $pa, PDO::PARAM_STR);
	$stmt->bindParam(':date', $date, PDO::PARAM_STR);
	$stmt->execute();
	
	$sql = 'SELECT * FROM tbtest';//入力データの抽出
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){//データの表示
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['password'].',';
		echo $row['date'].','.'<br>';
	echo "<hr>";
}
}

//削除
if(!empty($num1)&&!empty($pass1)){//削除番号及びパスワード入力されたら
    $id = $num1;//削除番号
	$sql = 'delete from tbtest where id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
	$sql = 'SELECT * FROM tbtest';//データの抽出
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){//データの表示
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['password'].',';
		echo $row['date'].'<br>';
	echo "<hr>";
	}
}

//コメントの追加
if(!empty($com)&&!empty($na)&&!empty($pass)){//新規コメント, 名前, パスワードが入力されたら
	$name =$na;//新規の名前
	$comment =$com;//新規のコメント
	$pa=$pass;//新規のパスワード
    $sql = $pdo -> prepare("INSERT INTO tbtest 
    (name, comment, password, date)
    VALUES (:name, :comment, :password, :date)");
	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	$sql -> bindParam(':password', $pa, PDO::PARAM_STR);
	$sql -> bindParam(':date', $date, PDO::PARAM_STR);
	$sql -> execute();
	$sql = 'SELECT * FROM tbtest';//データの抽出
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){//データの表示
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['password'].',';
		echo $row['date'].','.'<br>';
	echo "<hr>";
	}
}

?>
</body></html>