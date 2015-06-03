<?php
class Mailinator
{
	private $token;
	private $apiEndpoint = "https://api.mailinator.com/api/";
	private $inboxCount = 0;
	
	public function __construct($token)
	{
		$this->token = $token;
	}
	
	private function call($method, $params)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->apiEndpoint . $method);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array_merge($params, array('token' => $this->token)));
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$exec = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		
		if($info["http_code"] == 200)
		{
			return json_decode($exec, true);
		}
		else
		{
			throw new Exception('There was an error contacting the mailinator API endpoint.')
		}
	}
	
	public function fetchInbox($inbox)
	{
		$query = $this->call('inbox', array('to' => $inbox));
		$this->inboxCount = count($query["messages"]);
		return $query["messages"];
	}
	
	public function fetchMail($msgId)
	{
		$query = $this->call('email', array('id' => $msgId));
		$message = $query["data"];
		var_dump($message);
	}
}

?>
