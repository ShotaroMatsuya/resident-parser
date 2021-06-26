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

	public function loopNode()
	{
		$rateArray = array();
		$hospitalName = $this->getHospitalName();
		$rateArray['病院名'] = $hospitalName;
		for ($i = 0; $i < 13; $i++) {

			if ($i === 0) {

				$rateArray['総合点'] = $this->getTotalScore();
				continue;
			}
			if ($i <= 6) {
				$key = $this->getColumnTitle($i);
				$value = $this->outputRateNum($i + 1) ?: 'なし';
			}
			if ($i > 6 && $i <= 12) {
				$key = $this->getColumnTitle($i);
				$value = $this->getColumnValue($i - 7) ?: 'なし';
				$value = str_replace(array("\r", "\n", ","), '', $value);
			}
			$rateArray[$key] = $value;
		}
		return $rateArray;
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

		if ($nodeList->length !== 0) {
			for ($i = 0; $i < 5; $i++) {
				$className = $nodeList->item($i)->getAttribute('class');
				// echo $className;
				if (strpos($className, 'active') !== false) {
					return $i + 1;
				}
			}
		} else {
			return false;
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
		return $this->xpath->query("//div[@class='table__row']//span[@class='score']")->item(0)->nodeValue ?: 'なし';
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
