<?php

namespace Mailinator;

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
        $this->fromName = isset($msgData["from"]) ? $msgData["from"] : null;
        if(isset($msgData["headers"])) {
            $this->headers = $msgData["headers"];
        }
        $this->id = $msgData["id"];
        $this->ip = isset($msgData["ip"]) ? $msgData["ip"] : null;
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