<?php
class DomDocumentParser
{
	private $doc;
	private $xpath;
	public $results = array();

	public function __construct($url)
	{
		$options = array(
			'http' => array('method' => "GET", 'header' => "User-Agent: SmatBot/0.1\n")
		);
		$context = stream_context_create($options);

		$this->doc = new DOMDocument();
		@$this->doc->loadHTML(file_get_contents($url, false, $context));
		$this->xpath = new DOMXPath($this->doc);
	}

	public function getLinks()
	{
		return $this->doc->getElementsByTagName("a");
	}

	/**
	 *
	 * @return array
	 */

	// $results = 
	// [
	// 	'〇〇病院' =>[
	// 		'総合点' => 1,
	// 		'〇〇' =>2,
	// 		'〇〇' =>1,
	// 	],
	// 	'△△病院' =>[
	// 		'総合点' => 1,
	// 		'〇〇' =>2,
	// 		'〇〇' =>1,
	// 	]
	// 	];

	public function loopNode()
	{

		$hospitalName = $this->getHospitalName();
		$rateArray = array();

		for ($i = 0; $i < 14; $i++) {

			if ($i === 0) {
				$rateArray['総合点'] = (int)$this->getTotalScore();
				continue;
			}
			if ($i < 7) {
				$key = $this->getColumnTitle($i);
				$value = $this->outputRateNum($i + 1);
			}
			if ($i >= 7 && $i < 14) {
				$key = $this->getColumnTitle($i);
				$value = $this->getColumnValue($i - 7);
			}
			$rateArray[$key] = $value;
		}
		$this->results[$hospitalName] = $rateArray;
		return $this->results;
	}

	/**
	 * 5段階評価で置き換え
	 * @param int 
	 * 
	 * @return int
	 */
	private function outputRateNum($j)
	{
		$nodeList = $this->xpath->query("//div[@class='table__row'][$j]//span[contains(@class,'rating__dot')]");
		for ($i = 0; $i < 5; $i++) {
			$className = $nodeList->item($i)->getAttribute('class');
			// echo $className;
			if (strpos($className, 'active') !== false) {
				return $i + 1;
			}
		}
	}
	/**
	 * タイトルの取得
	 * 
	 * @param int $i
	 * @return string
	 */
	private function getColumnTitle($i)
	{
		return $this->xpath->query("//p[@class='table__row__title']")->item($i)->nodeValue;
	}
	/**
	 * 値の取得
	 * 
	 * @param int $i
	 * @return string
	 */
	private function getColumnValue($i)
	{
		return $this->xpath->query("//p[@class='table__row__text']")->item($i)->nodeValue;
	}

	/**
	 * 総合点
	 * 
	 * @return string
	 */
	private function getTotalScore()
	{
		return $this->xpath->query("//div[@class='table__row']//span[@class='score']")->item(0)->nodeValue;
	}
	/**
	 * 病院名
	 * @return string
	 */
	private function getHospitalName()
	{
		return $this->xpath->query("//h1[@class='hospital-top__container__title'][1]")->item(0)->nodeValue;
	}
}
