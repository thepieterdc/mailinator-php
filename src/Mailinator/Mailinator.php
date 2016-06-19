<?php namespace Mailinator;

/**
 * Mailinator API interface
 * @package Mailinator
 * @license	http://opensource.org/licenses/MIT
 * @since 2015-06-20
 * @author	Pieter De Clercq <pieterdeclercq@outlook.com>
 * @author	Muntean Doru <munteandoru@gmail.com>
 */
class Mailinator {
	/**
	 * The user's token, used to authenticate with the Mailinator API. Can be obtained by creating a free account at http://www.mailinator.com/
	 * @var string
	 */
	private $token;

	/**
	 * Constructs a new Mailinator instance.
	 *
	 * @param string $token The user's token, used to authenticate with the Mailinator API. Can be obtained by creating a free account at http://www.mailinator.com/
	 */
	public function __construct($token) {
		$this->token = $token;
	}

	/**
	 * Makes a call to the Mailinator API.
	 *
	 * @param string $method The method to call
	 * @param array $params The parameters to send
	 * @return array The JSON decoded response from the Mailinator API
	 * @throws \Exception Any errors encountered
	 */
	private function call($method, $params)	{
		$ch = curl_init();

		$callback_parameters = http_build_query(array_merge($params, array('token' => $this->token)),'', '&');
		curl_setopt($ch, CURLOPT_URL, "https://api.mailinator.com/api/" . $method . '?' . $callback_parameters);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$exec = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);

		if($info["http_code"] == 200) {
			return json_decode($exec, true);
		} else {
			throw new \Exception('There was an error contacting the Mailinator API endpoint.');
		}
	}

	/**
	 * Gets the messages for a specified inbox.
	 *
	 * @param string $inbox The e-mailaddress to query
	 * @return Inbox The inbox
	 * @throws \Exception Any errors encountered
	 */
	public function inbox($inbox) {
		$query = $this->call('inbox', array('to' => $inbox));

		if(!isset($query["messages"])) {
			throw new \Exception('Missing messages data in response from Mailinator API.');
		}
		return new Inbox($query["messages"]);
	}

	/**
	 * Gets the message details for the specified message id.
	 *
	 * @param string $msgId The id of the message
	 * @return Message the message
	 * @throws \Exception Any errors encountered
	 */
	public function message($msgId) {
		$query = $this->call('email', array('id' => $msgId));

		if(!isset($query["data"])) {
			throw new \Exception('Missing data in response from Mailinator API.');
		}

		return new Message($query["data"]);
	}

	/**
	 * Deletes a specified message from the inbox
	 *
	 * @param string $msgId The id of the message to delete
	 * @return bool true if the message was deleted
	 * @throws \Exception Any errors encountered
	 */
	public function delete($msgId) {
		$query = $this->call('delete', array('id' => $msgId));

		if(!isset($query["status"])) {
			throw new \Exception("Missing result in response from Mailinator API.");
		}

		return $query["status"] == "ok";
	}
}

/**
 * Mailinator Inbox
 * @package Mailinator
 * @license	http://opensource.org/licenses/MIT
 * @since 2015-09-26
 * @author	Pieter De Clercq <pieterdeclercq@outlook.com>
 */
class Inbox {

	private $messages = array();

	/**
	 * Constructs a new inbox.
	 *
	 * @param array $returnData The data received from a call to the Mailinator API
	 */
	public function __construct($returnData) {
		foreach($returnData as $message) {
			$this->messages[] = new Message($message);
		}
	}

	/**
	 * Returns the messages in this inbox
	 *
	 * @return array The list of messages in this inbox as an array of Message objects
	 */
	public function messages() {
		return $this->messages;
	}

	/**
	 * Counts the amount of messages in this inbox.
	 *
	 * @return int The amount of messages in this inbox
	 */
	public function count() {
		return count($this->messages);
	}
}

/**
 * Mailinator Message
 * @package Mailinator
 * @license	http://opensource.org/licenses/MIT
 * @since 2015-09-26
 * @author	Pieter De Clercq <pieterdeclercq@outlook.com>
 */
class Message {
	private $body;
	private $fromEmail;
	private $fromName;
	private $headers = array();
	private $id;
	private $ip;
	private $read;
	private $subject;
	private $time;
	private $to;
	private $secondsAgo;

	/**
	 * Constructs a new message.
	 *
	 * @param array $returnData The data received from a call to the Mailinator API
	 */
	public function __construct($msgData) {
		if(isset($msgData["parts"]) && isset($msgData["parts"][0]) && isset($msgData["parts"][0]["body"])) {
			$this->body = $msgData["parts"][0]["body"];
		}
		$this->fromEmail = isset($msgData["fromEmail"]) ? $msgData["fromEmail"] : null;
		$this->fromName = isset($msgData["from"]) ? $msgData["from"] : null; ;
		if(isset($msgData["headers"])) {
			$this->headers = $msgData["headers"];
		}
		$this->id = $msgData["id"];
		$this->ip = $msgData["ip"];
		$this->read = isset($msgData["been_read"]) ? $msgData["been_read"] : null;
		$this->subject = $msgData["subject"];
		$this->time = $msgData["time"];
		$this->to = $msgData["to"];
		$this->secondsAgo = $msgData["seconds_ago"];
	}

	/**
	 * Returns the contents of this message.
	 * <b>This is only available if the message has been constructed from a call to Mailinator->message()</b>
	 *
	 * @return string The message contents
	 */
	public function body() {
		return $this->body;
	}

	/**
	 * Returns the sender's email.
	 *
	 * @return string The sender's email
	 */
	public function fromEmail() {
		return $this->fromEmail;
	}

	/**
	 * Returns the name of the sender.
	 *
	 * @return string The sender's name
	 */
	public function fromName() {
		return $this->fromName;
	}
	/**
	 * Returns the headers of this message.
	 * <b>This is only available if the message has been constructed from a call to Mailinator->message()</b>
	 *
	 * @return array The message headers
	 */
	public function headers() {
		return $this->headers;
	}

	/**
	 * Returns the id of this message.
	 *
	 * @return int The message id
	 */
	public function id() {
		return $this->id;
	}

	/**
	 * Returns the IP address of the mailserver this message is originating from.
	 *
	 * @return string The mailserver's IP address
	 */
	public function ip() {
		return $this->ip;
	}

	/**
	 * Returns wether or not this message has been read.
	 *
	 * @return bool true if this message has been read
	 */
	public function read() {
		return $this->read;
	}

	/**
	 * Returns the subject of this message.
	 *
	 * @return string The subject of this message
	 */
	public function subject() {
		return $this->subject;
	}

	/**
	 * Returns the time this message was sent.
	 *
	 * @return int The time this message was sent, in seconds since 01/01/1970
	 */
	public function time() {
		return $this->time;
	}

	/**
	 * Returns the name of the receiver of this message.
	 *
	 * @return string The name of the receiver of this message
	 */
	public function to() {
		return $this->to;
	}

	/**
	 * Returns the time this message was sent.
	 *
	 * @return int The time this message was sent, in seconds.
	 */
	public function secondsAgo(){
	    return $this->secondsAgo;
	}
}
