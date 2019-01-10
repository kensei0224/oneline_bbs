<?php
  // ここにDBに登録する処理を記述する
  //登録処理
  //1.DB接続
$dsn='mysql:dbname=oneline_bbs;host=localhost';
// Date Source Name
//DB情報　どこに接続するか
$user='root';//誰が
$password='';//パスワードは何か
$dbh=new PDO($dsn,$user,$password);
//接続処理
//dbh
//Datebase handle
//データベースを扱うことができるやつ
$dbh->query('SET NAMES utf8');
//文字コード設定

  //2.SQL実装
if(!empty($_POST)){//POST送信かどうか
	$nickname =$_POST['nickname'];
	$comment =$_POST['comment'];
	//$_POSTは連想配列です
	$sql='INSERT INTO`posts`(`nickname`,`comment`,`created`)VALUES(?,?,NOW())';
	//?を使う理由
	//SQLインジェクション対策
	//NOW()はSQLの関数　現在日時を算出
	$date=[$nickname,$comment];
	//$date=array($nickname,$comment);
	$stmt=$dbh->prepare($sql);
	$stmt->execute($date);//ここで初めてSQLが実装される
}
//一覧表示
$sql='SELECT * FROM `posts`';
$stmt=$dbh->prepare($sql);
$stmt->execute();
//SQL文に?がないので$date渡す必要なし
$posts=[];//取得したデータを格納するための配列
while(true){
	$record=$stmt->fetch(PDO::FETCH_ASSOC);
	//1行ずつ処理
	if($record==false){
	//コードが存在しないときfalseになる
		break;
	}
	$posts[]=$record;
	//配列にレコードを追加
}

echo "<pre>";
var_dump($posts);
echo "</pre>";

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>セブ掲示版</title>
</head>
<body>
	<!--formタグにはmethodとactionが必須	-->
	<!--method 送信方法　どうアクセスするか
		action 送信先　アクセスする場所
		actionが空白の場合、自分自身に戻る -->
    <form method="post" action="">
    	<!--formタグ内のinputタグやtextareaタグのname属性が-->
      <p><input type="text" name="nickname" placeholder="nickname"></p>
      <p><textarea type="text" name="comment" placeholder="comment"></textarea></p>
      <p><button type="submit" >つぶやく</button></p>
    </form>
    <!-- ここにニックネーム、つぶやいた内容、日付を表示する -->
<!-- 投稿情報をすべて表示する　＝　1件ずつ繰り返し報じ処理をする
＄postsは配列なので、foreachが使える
forreach (配列名　as $任意の変数名)
foreach (複数形　as 単数名) -->
<?php foreach ($posts as $post): ?>
<p><?php echo  $post['nickname'];?>
</p>
<!-- 日付 -->

<p><?php echo $post['created']; ?>
</p>
<!-- コメント -->

<p><?php echo $post['comment']; ?>
</p>

<hr>

<?php endforeach; ?>
</body>
</html>