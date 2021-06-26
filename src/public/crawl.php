<?php
include("classes/DomDocumentParser.php");

$crawled = array();
$crawling = array();


function scanLink($url)
{
	global $crawled;
	global $crawling;

	$parser = new DomDocumentParser($url);

	// $linkList = $parser->getLinks();
	// $tableList = $parser->getStarList();
	// $tableTitle = $parser->getColumnName();
	// 
	$rateNum = $parser->loopNode();
	echo '<pre>';
	var_dump($rateNum);
	echo '</pre>';

	// echo $parser->outputRateNum($tableList);

	// for ($i = 0; $i < 5; $i++) {
	// 	echo '<pre>';
	// 	var_dump($tableList->item($i)->getAttribute('class'));
	// 	echo '</pre>';
	// }
	// for ($i = 0; $i < 7; $i++) {
	// 	echo '<pre>';
	// 	var_dump($tableTitle->item($i)->nodeValue);
	// 	echo '</pre>';
	// }


	// foreach ($tableList as $node) {
	// 	// $href = $link->getAttribute("href");
	// 	// if (strpos($href, 'archives') === false) {
	// 	// 	continue;
	// 	// }


	// 	// if (!in_array($href, $crawled)) {
	// 	// 	$crawled[] = $href;
	// 	// 	$crawling[] = $href;

	// 	// 	// insert $href
	// 	// }

	// 	var_dump($node->item(0));
	// 	// echo $node . "<br>";
	// }

}

$startUrl = "https://www.hokto.jp/archives/2593";
// $startUrl = "https://www.hokto.jp/?s=&etc=true";
scanLink($startUrl);
