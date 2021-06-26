<?php
class DomDocumentParser
{
	private $doc;
	private $xpath;

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
		$result_array = array();


		for ($i = 1; $i < 8; $i++) {

			if ($i === 1) {
				$result_array[$i - 1]['title'] = '総合点';
				$result_array[$i - 1]['rate'] = (int)$this->getTotalScore();
				continue;
			}
			$result_array[$i - 1]['title'] = $this->getTitle($i);
			$result_array[$i - 1]['rate'] = $this->outputRateNum($i);
		}
		return $result_array;
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
	private function getTitle($i)
	{
		return $this->xpath->query("//div[@class='table__row'][$i]/p")->item(0)->nodeValue;
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
}
