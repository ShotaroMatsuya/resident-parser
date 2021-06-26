<?php
include("classes/DomDocumentParser.php");

$crawled = array();
$crawling = array();

function getDetail($url)
{
	$parser = new DomDocumentParser($url);
	$array = $parser->loopNode();
	// // echo '<pre>';
	// // var_dump($array);
	// // echo '</pre>';
	$fp = fopen("finish.csv", "a");
	// fwrite($fp, "病院名, 総合点, 学歴フィルター, 忙しさ, 研修スタイル, 研修医の裁量権, 指導医の面倒見の良さ, 女性の働きやすさ, 希望順位登録者/定員, 強い科, 上級医の主な出身大学, 病床数, 給与, 救急指定");
	fwrite($fp,	 $array["病院名"] . ", ");
	fwrite($fp,	 $array["総合点"] . ", ");
	fwrite($fp,	 $array["学歴フィルター"] . ", ");
	fwrite($fp,	 $array["忙しさ"] . ", ");
	fwrite($fp,	 $array["研修スタイル"] . ", ");
	fwrite($fp,	 $array["研修医の裁量権"] . ", ");
	fwrite($fp,	 $array["指導医の面倒見の良さ"] . ", ");
	fwrite($fp,	 $array["女性の働きやすさ"] . ", ");
	fwrite($fp,	 $array["希望順位登録者/定員"] . ", ");
	fwrite($fp,	 $array["強い科"] . ", ");
	fwrite($fp,	 $array["上級医の主な出身大学"] . ", ");
	fwrite($fp,	 $array["病床数"] . ", ");
	fwrite($fp,	 $array["給与"] . ", ");
	fwrite($fp,	 $array["救急指定"] . "\n");
	fclose($fp);
}


function scanLink($url)
{
	global $crawled;
	global $crawling;

	$parser = new DomDocumentParser($url);

	$linkList = $parser->getLinks();



	$fp = fopen("finish.csv", "a");
	fwrite($fp, "病院名, 総合点, 学歴フィルター, 忙しさ, 研修スタイル, 研修医の裁量権, 指導医の面倒見の良さ, 女性の働きやすさ, 希望順位登録者/定員, 強い科, 上級医の主な出身大学, 病床数, 給与, 救急指定 \n");
	fclose($fp);
	foreach ($linkList as $link) {
		$href = $link->getAttribute("href");
		if (strpos($href, 'archives') === false) {
			continue;
		}

		if (!in_array($href, $crawled)) {
			$crawled[] = $href;
			$crawling[] = $href;
			// insert $href
			// $fp = fopen("text.txt", "a");
			// fwrite($fp,	$href . "\n");
			getDetail($href);
		}

		// echo $href . "<br>";
	}
}

// $startUrl = "https://www.hokto.jp/archives/4666";
$startUrl = "https://www.hokto.jp/?s=&etc=true";
scanLink($startUrl);
// getDetail($startUrl);
