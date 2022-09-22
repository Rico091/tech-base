<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
    <style>
      form {
        margin-bottom: 20px;
      }
    </style>
</head>
<body>


   
   
<?php
//データベースへ接続 
    $dsn='mysql:dbname=tb240319db;host=localhost';
    $user = 'tb-240319';
    $password = '2vwSmk5PYR';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));

//テーブル作成
    $sql =
    "CREATE TABLE IF NOT EXISTS tb1"
    ."("
     ."id INT AUTO_INCREMENT PRIMARY KEY,"
     ."name char(32),"
     ."comment TEXT,"
     ."pass char(32),"
    ."date DATETIME"
   .");";
    $stmt = $pdo->query($sql);
    

  
  //編集実行機能
  if (isset($_POST['name']) && isset($_POST['comment'])) {
    if(!empty($_POST["editnumber"])){
    $id = $_POST["editnumber"];
    $edit_name = $_POST['name'];
    $edit_comment = $_POST['comment'];//変更したい名前、変更したいコメントは自分で決めること
  $pass=$_POST["password"];
   $edit_date = date("Y/m/d H:i:s");
    if($edit_comment != NULL && $edit_name != NULL&& $pass != NULL){
    $sql = 'UPDATE tb1 SET name=:edit_name, comment=:edit_comment, date=:edit_date WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':edit_name', $edit_name, PDO::PARAM_STR);
    $stmt -> bindParam(':edit_comment', $edit_comment, PDO::PARAM_STR);
    $stmt->bindParam(':edit_date', $edit_date, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
   }}
   
   
 //新規投稿 
 
elseif (isset($_POST["password"])){
//Mission_4-7,UPDATE文：入力されているデータレコードの内容を編集
//データ入力
    $name = $_POST['name'];
    $comment = $_POST['comment']; 
    //好きな名前、好きな言葉は自分で決めること
    $pass=$_POST["password"];
    $date = date ( "Y/m/d H:i:s" );
    if($comment != NULL && $name != NULL && $pass != NULL){
    $sql = $pdo -> prepare("INSERT INTO tb1 (name, comment,date,pass) VALUES (:name, :comment, :date,:pass)");
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql -> bindParam(':date', $date, PDO::PARAM_STR);
    $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    $sql -> execute();//実行する
    }}}
    //bindParamの引数名（:name など）はテーブルのカラム名に併せるとミスが少なくなります。最適なものを適宜決めよう。
 
 //削除機能
 if (isset($_POST['delete'])&&!empty($_POST["delpass"])){
 $delete = $_POST['delete'];
 $delpass=$_POST["delpass"];
  if($delete != NULL && $delpass != NULL){
    $sql = 'delete from tb1 where id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $delete, PDO::PARAM_INT);
    $stmt->execute();
}}


//編集選択機能
if (isset($_POST["edit"])&&!empty($_POST["edipass"])) {
    $id = $_POST["edit"];
    $edit_name=$_POST["edit_name"];
    $edit_comment=$_POST["edit_comment"];
    $edipass=$_POST["edipass"];
    //変更する投稿番号
    if($id != NULL && $edipass != NULL){
    $sql = 'SELECT * FROM tb1';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
   
    foreach ($results as $row){
        if($row["id"]==$id){
        //$rowの中にはテーブルのカラム名が入る
        $edit_name = $row['name'];
        $edit_comment= $row['comment'];
        $edipass=$row["pass"];
        $edit=$row['id'];
        }
    }
}}
  



?>

<form action="m05-01.php" method="post">
       <input type="text" name="name" placeholder="名前" value="<?php if(isset($_POST["edit_name"])&&!empty($_POST["edipass"])){echo $edit_name;} ?>"><br>
       <input type="text" name="comment" placeholder="コメント" value="<?php if(isset($_POST["edit_comment"])&&!empty($_POST["edipass"])) {echo $edit_comment;} ?>"><br>
       <input type="hidden" name="editnumber" value="<?php if(isset($_POST["edit"])&&!empty($_POST["edipass"])) {echo $edit;} ?>">
       <input id="pass" type="password" name="password" placeholder="パスワード" >
       <input type="submit" name="submit">
</form>
<form action="m05-01.php" method="post">
     <input type="number" name="delete" placeholder="削除対象番号" value=""><br>
     <input id="pass" type="password" name="delpass" placeholder="パスワード">
    <input type="submit" value="削除">
</form>
<form action="m05-01.php" method="post">
        <input type="number" name="edit" placeholder="編集対象番号">
        <input type="hidden" name="edit_name">
        <input type="hidden" name="edit_comment">
         <br>
        <input id="pass" type="password" name="edipass" placeholder="パスワード">
        <input type="submit" value="編集">
</form> 



<?php
    //続けて、4-6の SELECTで表示させる機能 も記述し、表示もさせる。
    //※ データベース接続は上記で行っている状態なので、その部分は不要
      $sql = 'SELECT * FROM tb1';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
//$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
         echo $row['date'].',';
         echo $row["pass"].'<br>';
        
    echo "<hr>";
    }
 
    
 
     
?>
</body>
</html>