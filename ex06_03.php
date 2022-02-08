
<?php
	//=======================================================================
	// 注意・備考
	//-----------------------------------------------------------------------
	// HTMLのファイル指定：input type="file"
	// ファイル指定のvalue値に初期値の設定、入力エラー時などの再表示の設定を
	// 行ってもセキュリティ上の制限でブラウザ上には表示されない！
	//=======================================================================

	//----- 定数 -----
	define("MAX_FILE_SIZE",	(1024*70));	// ファイル最大サイズ：70KByte
	//----- 変数 -----
	$pflg			= 0;				// POSTフラグ
	$filename		= "";				// ファイル名 
	$image_folder	= "../img/";		// 保存フォルダ
	$comment		= "";				// コメント	
	$errmsg			= array();			// エラーメッセジ配列
	
	//===== ポスト：リクエスト処理  =====
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$pflg = 1;
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
					$errmsg[] = "ファイルのサイズオーバーです （MAX：". MAX_FILE_SIZE /1024 . " KByte）";
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
			// ファイル名分解
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
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>ex06_03.php</title>
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
	if (count($errmsg))
	{
		// エラーメッセージ表示
		foreach($errmsg as $val)
		{
			echo $val . "<br />";
		}
		echo "<br />";
	}
?>
</div>
<?php
	// 初回（GET）又は エラー有り（POST)
	if (!$pflg || count($errmsg))
	{
?>
	<form action="<?= $_SERVER["PHP_SELF"] ?>" method="post" enctype="multipart/form-data">
		<div>
			<input type="hidden" name="MAX_FILE_SIZE" value="<?= MAX_FILE_SIZE ?>" />
			アップロードする画像ファイル名とコメントを入力してください<br />
			<br />
			ファイル：<input type="file" name="uploadfile" size="60" /><br />
			<br />
			コメント：<input type="text" name="comment" value="<?= $comment ?>" size="30" />	<br />
			<br />
			<input type="submit" value="アップロード" />
		</div>
	</form>
<?php
	}
	// エラー無し：処理結果表示
	else
	{
		// エラー無し：アップロードファイルとコメント表示
		echo '<img width="50%" height="50%" src="' . $image_folder . $filename . '" /><br />';
		echo htmlspecialchars($comment, ENT_QUOTES);
	}
?>
</body>
</html>
