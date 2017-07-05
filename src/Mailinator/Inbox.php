<?php

namespace Mailinator;

/**
 * Mailinator Inbox
 * @package Mailinator
 * @license	http://opensource.org/licenses/MIT
 * @since 2015-09-26
 * @author	Pieter De Clercq <pieterdeclercq@outlook.com>
 */
class Inbox
{
    private $messages = array();

    /**
     * Constructs a new inbox.
     *
     * @param array $returnData The data received from a call to the Mailinator API
     */
    public function __construct($returnData)
    {
        foreach ($returnData as $message) {
            $this->messages[] = new Message($message);
        }
    }

    /**
     * Returns the messages in this inbox
     *
     * @return array The list of messages in this inbox as an array of Message objects
     */
    public function messages()
    {
        return $this->messages;
    }

    /**
     * Counts the amount of messages in this inbox.
     *
     * @return int The amount of messages in this inbox
     */
    public function count()
    {
        return count($this->messages);
    }
}
