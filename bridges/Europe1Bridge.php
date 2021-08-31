<?php
class Europe1Bridge extends FeedExpander {

	const MAINTAINER = 'Stelfux';
	const NAME = 'Europe1 bridge';
	const URI = 'https://www.europe1.fr';
	const DESCRIPTION = 'Les podcast de la radio Europe1';
	const CACHE_TIMEOUT = 3600;

	const PARAMETERS = array(array(
		'url' => array(
			'name' => 'Original feed url',
			'type' => 'text',
			'required' => true,
			'pattern' => '(https:\/\/www\.europe1\.fr\/rss\/podcasts\/)?[a-z\-]+(.xml)?',
			'title' => 'URL of the feed to be cleaned',
			'exampleValue' => 'https://www.europe1.fr/rss/podcasts/avant-demain.xml',
		))
	);

	public function collectData() {
		$url = $this->getInput('url');
		if (!str_starts_with($url, 'https://'))
			$url = 'https://www.europe1.fr/rss/podcasts/' . $url . '.xml';

		$this->collectExpandableDatas($url);
	}

	protected function parseItem($feedItem) {
		$item = $this->parseRSS_2_0_Item($feedItem);
		$p = '/(https:\/\/[\w\.]+)\/podcast\/mp3\/itunes-\d+\/(\d+)\/podcast\.mp3/';

		foreach($item['enclosures'] as $enclosure) {
			$url = preg_replace($p, '$1/v2/data/replays/$2', $enclosure);
			$json = json_decode(file_get_contents($url), true);

			$src = $json['data']['current']['soundFile']['src'];
			$enclosures[] = empty($src) ? $enclosure : $src;
		}

		$item['enclosures'] = $enclosures;
		return $item;
	}
}
