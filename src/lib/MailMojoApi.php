<?php
/**
 * Simple MailMojo API class.
 *
 * Currently supports subscribing and unsubscribing recipients for a MailMojo account.
 * Requires the PHP curl extension.
 *
 * Copyright 2011, Eliksir AS <http://e5r.no>
 */
class MailMojoApi {
	private $username;

	/**
	 * Instantiates this class for the given user. The curl extension must be available, else
	 * an exception is thrown.
	 *
	 * @param string $username MailMojo username to the account we will connect.
	 * @throws Exception       If the curl extension is unavailable.
	 */
	public function __construct ($username) {
		if (!function_exists('curl_init')) {
			throw new Exception('PHP curl extension is not installed or enabled.');
		}

		$this->username = $username;
	}

	/**
	 * Subscribes a recipient to the specified list.
	 *
	 * If a recipient with the specified email address already exists, that recipient is
	 * updated with the supplied data.
	 *
	 * @param string $lid   ID of list to subscribe contact to.
	 * @param string $email Valid email address of the recipient.
	 * @param string $name  Optional name of the recipient.
	 * @param mixed $tags   Array of tags or comma-separated string with tags to attach to
	 *                      the contact.
	 * @throws Exception    If list ID or email address is not specified.
	 * @return bool         TRUE on success, FALSE on failure.
	 */
	public function subscribe ($lid, $email, $name = null, $tags = null) {
		$this->validateArgs($lid, $email);

		$postData = "email=" . urlencode($email);
		if (!empty($name)) {
			$postData .= "&name=" . urlencode($name);
		}
		if (!empty($tags)) {
			$tagsArray = $this->buildTags((array)$tags);
			$postData .= '&tags[]=' . implode('&tags[]=', $tagsArray);
		}
		return $this->execute("$lid/s", $postData);
	}

	/**
	 * Unsubscribes the recipient with the specified email address.
	 *
	 * This gives no indication whether the recipient was indeed subscribed or not.
	 * Success only indicates that communication with the MailMojo server was successful.
	 *
	 * @param int $lid      ID of list to unsubscribe contact from.
	 * @param string $email Valid email address of the recipient.
	 * @throws Exception    If list ID or email address is not specified.
	 * @return bool         TRUE on success, FALSE on failure.
	 */
	public function unsubscribe ($lid, $email) {
		$this->validateArgs($lid, $email);
		$postData = "email=" . urlencode($email);
		return $this->execute("$lid/u", $postData);
	}

	/**
	 * Validates required arguments.
	 *
	 * @param number $lid   The numeric ID of a MailMojo list.
	 * @param string $email The email address of a recipient.
	 * @throws Exception    If any required arguments are empty or invalid.
	 */
	private function validateArgs ($lid, $email) {
		if (empty($lid)) {
			throw new Exception('List ID must be specified.');
		}
		if (!is_numeric($lid)) {
			throw new Exception('List ID should be an integer value.');
		}
		if (empty($email)) {
			throw new Exception('Email address must be specified.');
		}
	}

	/**
	 * Builds an URL encoded array of all tags supplied in an array.
	 *
	 * Each value in the array is expected to be a string. The string values can be
	 * comma-separated tag lists, which will be split into separate tag values in the returned
	 * array.
	 *
	 * @param array $tags The list of tag strings.
	 * @return array List of URL encoded tag values.
	 */
	private function buildTags ($tags) {
		$safeTags = array();
		foreach ($tags as $tagString) {
			$safeTags = array_merge($safeTags,
				array_map('urlencode', preg_split('/\s*,\s*/', $tagString)));
		}
		return $safeTags;
	}

	/**
	 * Executes a curl call with the specified data.
	 *
	 * @param string $target The URL path of target action to send a POST request to.
	 * @param string $data   URL encoded query string to send as data with the POST request.
	 * @return bool          TRUE on success, FALSE on failure.
	 */
	private function execute ($target, $data) {
		$request = curl_init($this->getActionUrl($target));
		curl_setopt($request, CURLOPT_RETURNTRANSFER, true); // Avoids output of return value
		curl_setopt($request, CURLOPT_POST, true);
		//curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);
		//curl_setopt($request, CURLOPT_MAXREDIRS, 1);
		curl_setopt($request, CURLOPT_POSTFIELDS, $data);

		$success = curl_exec($request);
		curl_close($request);
		return $success !== false;
	}

	/**
	 * Returns the action URL to use.
	 *
	 * @param string $target The URL path to the action.
	 * @return string        Complete URL for requesting target action on the user's
	 *                       MailMojo account.
	 */
	private function getActionUrl ($target) {
		return "http://{$this->username}.mailmojo.no/{$target}";
	}
}
