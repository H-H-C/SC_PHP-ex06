

<?php 
  $errmsg = [];
  if ($_SERVER['REQUEST_METHOD'] = 'post')
  {
    if (strlen($_FILES['upfile']['name']) <= 0)
      $errmsg[] = "ファイルが指定されていません";
    else 
    {
      $filename = $_FILES['upfile']['name'];
      $fileinfo = pathinfo($filename);
      $ext = strtolower($fileinfo['expension']);
      if ($ext != 'txt' && $ext !='ini')
      $errmsg[] = 'textかiniのdファイルを指定してください';
      elseif ($_FILES['upfile']['size'] ==0)
      $errmsg[] = '指定されたファイルが存在しないか空です';
      else 
      {

      }
    }
  }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>ex06_01.php</title>
<style>
<!--
	#err {color:red;}
-->
</style>
</head>
<body>
<h2>[ファイル・アップロード]</h2>
<pre>
<?php
	// アップロードファイル情報表示
	print_r($_FILES);
	echo "<br />";
	// ファイル名表示
	// 注意・備考 ： PHP 5.3 ～ 5.6 のバグ！ ※ PHP7.0 以降はバグが修正されている。
	//   pathinfo関数はファイル先頭文字に日本語・全角（マルチバイト）が利用できない（化ける）！
	print_r(pathinfo($_FILES["uploadfile"]["name"]));
?>
</pre>
</body>
</html>
