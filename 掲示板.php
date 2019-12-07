<html>
<head>
<meta name="viewport" content="width=320, height=480, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=yes">
<meta charset="utf-8">
<title></title>
</head>

<body>



<form action="掲示板.php" method="post">


	<div>
	   <input type="hidden" id="ed_num" name="ed_num" value="編集対象番号">
	</div>
	<!-- 編集（入力）フォームより先に編集番号を取得したい -->

	<div>
	<input type="hidden" id="pass3" name="pass3" value="パスワード">
	</div>
	<!-- 編集（入力）フォームより先に編集番号を取得したい -->


<?php

	$dsn = 'データベース名';//データベース名とMYSQLホスト名
	$user = 'ユーザー名';//ユーザ名
	$password = 'パスワード';//パスワード
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));


	//テーブルの削除。初期化の時どうぞ
	//$sql = 'DROP TABLE tbtest';
	//$results = $pdo -> query($sql);


	$sql = "CREATE TABLE IF NOT EXISTS tbtest"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "date DATETIME,"
	. "pass TEXT"
	.");";
	$stmt = $pdo->query($sql);


if(isset($_POST["submit3"])){
$pass3 = $_POST["pass3"];
 if($pass3 == "パスワード"){
  if(ctype_digit($_POST["ed_num"])){
	//中身があるとき実行、数字のみ実行
   $ed_num = $_POST["ed_num"];


	$stmt = $pdo->query("select name from tbtest where id = $ed_num");
	$stmt1 = $pdo->query("select comment from tbtest where id = $ed_num");
	//結果を表示
	$result = $stmt->fetch();
	$result1 = $stmt1->fetch();

	if($result['name'] == "" || $result1['comment'] == ""){
	echo "存在しない番号です。";
	}

  }
 }
}

?>




        <div>
            <input type="value" id="name" name="name" placeholder=<?php 
if(isset($_POST["submit3"])){
	if($pass3 == "パスワード"){
		if(ctype_digit($_POST["ed_num"])){
			if($result['name'] == ""){
			echo "名前"; 
			}elseif($result['name'] !== ""){echo $result['name'];}

		}else{echo "名前";}

	}else{echo "名前";}

}else{echo "名前";} ?>>

        </div>


        <div>
            <input type="value" id="comment" name="comment" placeholder=<?php 
if(isset($_POST["submit3"])){
	if($pass3 == "パスワード"){
		if(ctype_digit($_POST["ed_num"])){
			if($result1['comment'] == ""){
			echo "コメント";
			}elseif($result1['comment'] !== ""){echo $result1['comment'];}
 
		}else{echo "コメント";}

	}else{echo "コメント";}

}else{echo "コメント";} ?>>

        </div>

	<div>
	   <input type="hidden" id="ed_num2" name="ed_num2" value= <?php if(isset($_POST["submit3"])){if($pass3 == "パスワード"){if(preg_match('/^[0-9]+$/D',$_POST["ed_num"])){ echo $ed_num; }else{echo "";}}else{echo "";}}else{echo "";} ?>>
	</div>

	<div>
	<input type="value" id="pass1" name="pass1" placeholder="パスワード">
	</div>

        <input type="submit" name = "submit1" value="送信">


	<div>
	   <input type="value" id="d_num" name="d_num" placeholder="削除対象番号">
	</div>	

	<div>
	<input type="value" id="pass2" name="pass2" placeholder="パスワード">
	</div>

	<input type="submit" name="submit2" value="削除">


	<div>
	   <input type="value" id="ed_num" name="ed_num" placeholder="編集対象番号">
	</div>

	<div>
	<input type="value" id="pass3" name="pass3" placeholder="パスワード">
	</div>

	<input type="submit" name="submit3" value="編集">



</form>

<?php
if(isset($_POST["submit3"])){
  if($pass3 !== "パスワード"){echo "パスワードが違います". "<br>";}
}


if(isset($_POST["submit1"])){
if(isset($_POST["comment"])){
  if($_POST["ed_num2"] == ""){
	//中身があるとき実行、編集番号が空の時は投稿
  $pass1 = $_POST["pass1"];

	if($pass1 == "パスワード"){
	$name = $_POST["name"];
	$comm = $_POST["comment"];
	$date = date('Y-m-d H:i:s');
	$pass1 = $_POST["pass1"];

		if($comm !== "" && $name !== ""){
		echo $comm. "(送信内容)を受け付けました". "<br>";


		$sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
		$sql -> bindParam(':name', $name, PDO::PARAM_STR);
		$sql -> bindParam(':comment', $comm, PDO::PARAM_STR);
		$sql -> bindParam(':date', $date, PDO::PARAM_STR);
		$sql -> bindParam(':pass', $pass1, PDO::PARAM_STR);

		$sql -> execute();

		}
	}else{echo "パスワードが違います". "<br>";}
  }
  elseif($_POST["ed_num2"] !== ""){
  $pass1 = $_POST["pass1"];
  $name = $_POST["name"];
  $comm = $_POST["comment"];

	if($pass1 == "パスワード"){

		if($comm != "" && $name !== ""){
		$ed_num2 = $_POST["ed_num2"];
		$name2= $_POST["name"];
		$comm2= $_POST["comment"];
		$date = date('Y-m-d H:i:s');

		$id = $ed_num2; 
		$name = $name2;
		$comment = $comm2;
		$sql = 'update tbtest set name=:name,comment=:comment,date=:date,pass=:pass where id=:id';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':name', $name, PDO::PARAM_STR);
		$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->bindParam(':date', $date, PDO::PARAM_STR);
		$stmt->bindParam(':pass', $pass1, PDO::PARAM_STR);
		$stmt->execute();
		}

	}else{echo "パスワードが違います". "<br>";}
  }
}
}

elseif(isset($_POST["submit2"])){
$pass2 = $_POST["pass2"];
  if($pass2 == "パスワード"){

	if(preg_match('/^[0-9]+$/D',$_POST["d_num"])){

	$comm = $_POST["comment"];
	$name = $_POST["name"];
	$d_num = $_POST["d_num"];

	$id = $d_num;
	$sql = 'delete from tbtest where id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
	}

  }else{echo "パスワードが違います"."<br>";}
}


	$sql = 'SELECT * FROM tbtest';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){

		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['date'].',';
		echo $row['pass'].'<br>';
	echo "<hr>";
	}


?>


</body>
</html>
