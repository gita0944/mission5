<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission5-1</title>
    </head>
    <body>
        <!--投稿フォーム-->
        <form action=""method="post">
        <input type="text"name="name"placeholder="名前">
        <input type="text"name="comment"placeholder="コメント">
        <input type="text"name="pass"placeholder="パスワード">
        <input type="hidden"name="edit"value="">
        <input type="submit"name="submit"value="投稿">
        </form>
        <!--削除フォーム-->
        <form action=""method="post">
        <input type="number"name="delete"placeholder="削除番号">
        <input type="text"name="deletepass"placeholder="パスワード">
        <input type="submit"name="submit"value="削除">
        </form>
        <!--編集フォーム-->
        <form action=""method="post">
        <input type="number"name="editnumber"placeholder="編集番号">
        <input type="text"name="editpass"placeholder="パスワード">
        <input type="submit"name="submit"value="編集">
        </form>
        <?php
        // データベースへの接続
    $dsn = 'mysql:dbname=データベース;host=localhost';
    $user = 'ユーザ名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
 
// CREATE文でデータベース内にテーブルを作成
    $sql="CREATE TABLE IF NOT EXISTS mission51"
         ."("
         ."postnumber INT AUTO_INCREMENT PRIMARY KEY,"
         ."name char(32),"
         ."comment TEXT,"
         ."pass VARCHAR(255),"
         ."postdate DATETIME"
         .");";
    $stmt=$pdo->query($sql);    
// SHOW TABLESでデータベースのテーブル一覧を表示
    $sql='SHOW TABLES';
    $result=$pdo->query($sql);
    foreach($result as $row){
        echo$row[0];
        echo'<br>';
    }
    echo"<hr>";
// SHOW CREATE TABLE 文で、作成したテーブル内の構成詳細を確認する
    $sql='SHOW CREATE TABLE mission51';
    $result=$pdo->query($sql);
    foreach($result as $row){
        echo$row[1];
    }
    echo"<hr>";
// INSERT文でデータを入力(データレコードの挿入)
  if(isset($_POST["name"])&&isset($_POST["comment"])){
    var_dump($_POST);
    $name=$_POST["name"];
    $comment=$_POST["comment"];
    $pass=$_POST["pass"];
    $postdate=date("Y/m/d H:i:s");
    $sql=$pdo->prepare("INSERT INTO mission51(name,comment,pass,postdate)VALUES(:name,:comment,:pass,:postdate)");
    $sql->bindParam(':name',$name,PDO::PARAM_STR);
    $sql->bindParam(':comment',$comment,PDO::PARAM_STR);
    $sql->bindParam(':pass',$pass,PDO::PARAM_INT);
    $sql->bindParam(':postdate',$postdate,PDO::PARAM_STR);
    $sql->execute();
  }

     //DELETE文で、入力したデータレコードを削除
 if(isset($_POST["delete"])){
    $deletenumber=$_POST["delete"];
    $deletepass=$_POST["deletepass"];
    $sql="DELETE FROM mission51 WHERE postnumber=:postnumber AND pass=:deletepass";
    $stmt=$pdo->prepare($sql);
    $stmt->bindParam(':postnumber',$deletenumber,PDO::PARAM_INT);
    $stmt->bindParam(':deletepass',$deletepass,PDO::PARAM_INT);
    $stmt->execute();
    }
          //編集  
    if(isset($_POST["editnumber"])&&isset($_POST["editpass"])){
        $editnumber=$_POST["editnumber"];
        $editpass=$_POST["editpass"];
        // 編集番号と投稿番号が一致するレコードを表示
        $sql='SELECT*FROM mission51 WHERE postnumber=:editnumber AND pass=:editpass';
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':editnumber',$editnumber,PDO::PARAM_INT);
        $stmt->bindParam(':editpass',$editpass,PDO::PARAM_STR);
        $stmt->execute();
        $result=$stmt->fetch(PDO::FETCH_ASSOC);
        if($result){
            echo'<form action=""method="post">';
            echo'<input type="text"name="editname"value="'.$result["name"].'">';
            echo'<input type="text"name="editcomment"value="'.$result["comment"].'">';
            echo'<input type="hidden"name="edit"value="'.$editnumber.'">';
            echo'<input type="text"name="pass"value="'.$editpass.'">';
            echo'<input type="submit"name="editsubmit"value="編集完了">';
            echo'</form>';
        }
    }
        // UPDATEで編集内容を更新 
        if(isset($_POST["editsubmit"])&&isset($_POST["edit"])&&isset($_POST["editname"])){
            var_dump($_POST);
            $editnumber=$_POST["edit"];
            $editname=$_POST["editname"];
            $editcomment=$_POST["editcomment"];
        $sql="UPDATE mission51 SET comment=:editcomment,name=:editname WHERE postnumber=:editnumber";
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':editname',$editname,PDO::PARAM_STR);
        $stmt->bindParam(':editcomment',$editcomment,PDO::PARAM_STR);
        $stmt->bindParam(':editnumber',$editnumber,PDO::PARAM_INT);
        $stmt->execute();
    }
    // SELECT文で、入力したデータレコードを抽出し表示
    $sql='SELECT*FROM mission51';
    $stmt=$pdo->query($sql);
    $results=$stmt->fetchALL();
    foreach($results as$row){
        echo$row['postnumber'].'<br>';
        echo$row['name'].'<br>';
        echo$row['comment'].'<br>';
        echo$row['postdate'].'<br>';
        echo"<hr>";
    }
    
    ?>
    </body>
</html>