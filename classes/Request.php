<?php

class Request {

	/**
	 * Place to store cookies.
	 */
	public $cookieTemp = '/tmp/cookieFogis';

	/**
	 * Stores the login get page url.
	 */
	public $loginGetUrl;

	/**
	 * Stores the login post url.
	 */
	public $loginPostUrl;

	/**
	 * Stores the page where the real content is.
	 */
	public $finalGetUrl;

	/**
	 * Stores an array of parameters to be sent in post request during login.
	 */
	public $loginParams;

	/**
	 * The username for the page.
	 */
	public $username;

	/**
	 * The password for the page.
	 */
	public $password;

	/**
	 * Stores the result of the request.
	 */
	public $result;

	/**
	 * Stores the parsed result events.
	 */
	public $events;

	/**
	 * Runs the actual request.
	 */
	public function send() {
		// Sets login params
		$this->setParams();

		// Prepare curl request
		$ch = curl_init();  

		// Set options for login post request.
		curl_setopt($ch, CURLOPT_URL, $this->loginPostUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false); 
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->loginParamsString()); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookieTemp);
			
		// Executes the login
		curl_exec($ch);

		// Sets options for next request, where we will get the desired content.
		curl_setopt($ch, CURLOPT_URL, $this->finalGetUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false); 
		curl_setopt($ch, CURLOPT_POST, 0);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookieTemp);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

		// Save results in variable to be returned
		$this->result = curl_exec($ch);

		// Close connection
		curl_close($ch);
	}

	/**
	 * Parses a result and puts the resulting events in the events array.
	 */
	public function parseResult() {
		// Put everything inside the tbody in $matches[0]
		preg_match('/<tbody.*?>(.*?)<\/tbody>/ims', $this->result, $matches);

		// Then, find all table rows inside.
		preg_match_all('/<tr >(.*?)<\/tr>/ims', $matches[0], $matches2);

		// For each row, we will pick all columns and wrap them in CalendarEvents
		$events = array();
		foreach ($matches2[0] as $val) {
			if (is_array($val))
				continue;

			// Find all columns
			preg_match_all('/<td.*?>(.*?)<\/td>/ims', $val, $matches3);
			if (array_key_exists(0, $matches3) && array_key_exists(0, $matches3[0]) && count($matches3[0]) > 5) {
				// Check if AD or HD game
				$adhd = 'AD';
				if (preg_match('/\(Dom\) Jonas Dahl/', $matches3[1][6])) {
					$adhd = 'HD';
				}

				// Create the event
				$event = new CalendarEvent;
				$event->start = new DateTime($matches3[1][0]);
				$event->length = 2*3600;
				$event->id = strip_tags($matches3[1][3]);
				$event->location = trim(strip_tags($matches3[1][5]));
				$event->summary = strip_tags($adhd . ': ' . $matches3[1][4] . ' (' . $matches3[1][1] . ')');
				$event->description = strip_tags(
					'Uppdrag: ' . $adhd . '\n' . 
					'Match: ' . $matches3[1][4] . '\n' . 
					'Serie: ' . $matches3[1][1] . '\n' . 
					'Domare: ' . $matches3[1][6]
				);
				$events[] = $event;
			}
		}

		$this->events = $events;
	}

	/**
	 * Gets parameters stored on the login page that are necessary for login post request.
	 */
	private function setParams() {
		$html = file_get_contents($this->loginGetUrl);
		preg_match('/input.*name="__VIEWSTATE".*value="(.*)"/', $html, $vs);
		preg_match('/input.*name="__VIEWSTATEGENERATOR".*value="(.*)"/', $html, $vsg);
		preg_match('/input.*name="__EVENTVALIDATION".*value="(.*)"/', $html, $ev);

		$this->loginParams = array(
			'btnLoggaIn' => 'Logga in', 
			'tbAnvandarnamn' => $this->username, 
			'tbLosenord' => $this->password,
			'__VIEWSTATE' => trim($vs[1]),
			'__VIEWSTATEGENERATOR' => trim($vsg[1]),
			'__EVENTVALIDATION' => trim($ev[1])
		);
	}

	/**
	 * Takes $this->loginParams and creates a key1=value1&key2=value2... string for post http requests.
	 */
	private function loginParamsString() {
		$postData = '';
		// Create name : value pairs seperated by &
		foreach($this->loginParams as $k => $v) { 
			$postData .= urlencode($k) . '=' . urlencode($v) . '&'; 
		}
		rtrim($postData, '&'); // Remove the last &

		return $postData;
	}
}