
<?php
	$errmsg			= array();			// エラーメッセジ配列
	$image_folder	= "../img/";		// 保存フォルダ
	
	//===== ポスト(POST)：リクエスト処理  =====
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		// コメント
		$comment = $_POST["comment"];
		//--- 入力チェック ---
		while(true)
		{
			// アップロードファイル：入力チェック
			if (strlen($_FILES["uploadfile"]["name"]) <= 0)
			{
				$errmsg[] = "ファイルが指定されていません";
				break;
			}
			// ファイル名
			$filename = $_FILES["uploadfile"]["name"];
			// アップロードファイル：サイズチェック
			if ($_FILES["uploadfile"]["error"] !== 0)
			{
				if ($_FILES["uploadfile"]["error"] == 2)
				{
					$errmsg[] = "ファイルサイズのオーバーです （MAX：". ($_POST["MAX_FILE_SIZE"] /1024) . " KByte）";	
				}
				else
				{
					$errmsg[] = "アップロードエラー発生";
				}
				break;
			}
			if ($_FILES["uploadfile"]["size"] == 0)
			{
				$errmsg[] = "指定されたファイルが存在しないか空です（０）です";
				break;
			}
			// ファイル名
			$fileinfo = pathinfo($filename);
			// 拡張子の取得・チェック
			$ext = strtolower($fileinfo["extension"]);
			if ($ext != "gif" && $ext != "jpg" && $ext != "jpeg" && $ext != "bmp")
			{
				$errmsg[] = "gif か jpeg か bmp 形式のファイルを指定してください";
			}
			break;
		}

		//--- アップロードファイル保存  ---
		if( !count($errmsg) )
		{
			$move_filename = $filename;
			// PHP Ver7.X 以前（Ver 5.X）の文字コード変換
			if( (int)PHP_VERSION < 7 )
			{
				// ＯＳ別の保存フォルダ・ファイル設定
				// PHP_OS : AIX / Darwin(Mac OS X) / Linux / SunOS / WIN32 / WINNT / Windows
				if (strncmp(strtoupper(PHP_OS), "WIN", 3) == 0)
				{				
					// MS-Windows：シフトＪＩＳ(SJIS)
					// 保存ファイル名：ＵＴＦ－８ ---> シフトＪＩＳ
					$move_filename = mb_convert_encoding($filename, "SJIS", "UTF-8");
				}
			}
			// アップロードファイルの移動：一時ディレクトリ・ファイル ---> 保存フォルダ・ファイル
			$movepath = $image_folder . $move_filename;
			$moveok = move_uploaded_file($_FILES["uploadfile"]["tmp_name"], $movepath);
			// 移動結果判断
			if (!$moveok)
			{
				$errmsg[] = "アップロードに失敗しました";
			}
		}
	}
	//===== ゲット(GET)：リクエスト処理  =====
	else
	{
		$errmsg[] = "正しいリンク元からお越しください";
	}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>ex06_02.php</title>
<style>
<!--
	#err {color:red;}
-->
</style>
</head>
<body>
<h2>[ファイル・アップロード]</h2>
<div id="err">
<?php
	// エラーメッセージ表示
	foreach($errmsg as $val)
	{
		echo $val . "<br />";
	}
?>
</div>
<?php
	//--- アップロード結果の表示 ---
	if (count($errmsg))
	{
		// エラー有り：アップロード画面へのリンク
		echo '<br /><div><a href="ex06_02.html">アップロード指定に戻る</a></div>';
	}
	else
	{
		// エラー無し：アップロードファイルとコメント表示
		echo '<img width="50%" height="50%" src="' . $image_folder . $filename . '" /><br />';
		echo $comment;
	}
?>
</body>
</html>
