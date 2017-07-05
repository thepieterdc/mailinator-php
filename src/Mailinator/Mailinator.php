<?php

namespace Mailinator;

use Exception;
use Inbox;
use Message;

/**
 * Mailinator API interface
 * @package Mailinator
 * @license	http://opensource.org/licenses/MIT
 * @since 2015-06-20
 * @author	Pieter De Clercq <pieterdeclercq@outlook.com>
 * @author	Muntean Doru <munteandoru@gmail.com>
 */
class Mailinator
{
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
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Makes a call to the Mailinator API.
     *
     * @param string $method The method to call
     * @param array $params The parameters to send
     * @return array The JSON decoded response from the Mailinator API
     * @throws Exception Any errors encountered
     */
    private function call($method, $params)
    {
        $ch = curl_init();

        $callback_parameters = http_build_query(array_merge($params, array('token' => $this->token)), '', '&');
        curl_setopt($ch, CURLOPT_URL, "https://api.mailinator.com/api/" . $method . '?' . $callback_parameters);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $exec = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if ($info["http_code"] == 200) {
            return json_decode($exec, true);
        } else {
            throw new Exception('There was an error contacting the Mailinator API endpoint.');
        }
    }

    /**
     * Gets the messages for a specified inbox.
     *
     * @param string $inbox The e-mailaddress to query
     * @return Inbox The inbox
     * @throws Exception Any errors encountered
     */
    public function inbox($inbox)
    {
        $query = $this->call('inbox', array('to' => $inbox));

        if (!isset($query["messages"])) {
            throw new Exception('Missing messages data in response from Mailinator API.');
        }
        return new Inbox($query["messages"]);
    }

    /**
     * Gets the message details for the specified message id.
     *
     * @param string $msgId The id of the message
     * @return Message the message
     * @throws Exception Any errors encountered
     */
    public function message($msgId)
    {
        $query = $this->call('email', array('id' => $msgId));

        if (!isset($query["data"])) {
            throw new Exception('Missing data in response from Mailinator API.');
        }

        return new Message($query["data"]);
    }

    /**
     * Deletes a specified message from the inbox
     *
     * @param string $msgId The id of the message to delete
     * @return bool true if the message was deleted
     * @throws Exception Any errors encountered
     */
    public function delete($msgId)
    {
        $query = $this->call('delete', array('id' => $msgId));

        if (! isset($query["status"])) {
            throw new Exception("Missing result in response from Mailinator API.");
        }

        return $query["status"] == "ok";
    }
}
