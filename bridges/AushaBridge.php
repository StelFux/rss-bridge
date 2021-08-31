<?php
class AushaBridge extends FeedExpander {

	const MAINTAINER = 'Stelfux';
	const NAME = 'Ausha bridge';
	const URI = 'https://www.ausha.co';
	const DESCRIPTION = 'The Ausha podcast platform';
	const CACHE_TIMEOUT = 3600;

	const PARAMETERS = array(array(
		'url' => array(
			'name' => 'Original feed url',
			'type' => 'text',
			'required' => true,
			'pattern' => '(https:\/\/feed\.ausha\.co\/)?\w{12}',
			'title' => 'https://feed.ausha.co/ID-OF-FEED or ID-OF-FEED',
			'exampleValue' => 'https://feed.ausha.co/oakmehzx9Dp2',
		))
	);

	public function collectData() {
		$url = $this->getInput('url');
		if (strlen($url) === 12)
			$url = 'https://feed.ausha.co/' . $url;

		$this->collectExpandableDatas($url);
	}

	protected function parseItem($feedItem) {

		$item = $this->parseRSS_2_0_Item($feedItem);
		foreach($item['enclosures'] as $enclosure) {
			parse_str(parse_url($enclosure, PHP_URL_QUERY), $proxycast);
			parse_str(parse_url($proxycast['media_url'], PHP_URL_QUERY), $soundcast);

			if (empty($soundcast['podcastUrl'])) {
				$enclosures[] = $enclosure;
			} else {
				$enclosures[] = $soundcast['podcastUrl'];
			}
		}

		$item['enclosures'] = $enclosures;
		return $item;
	}
}
