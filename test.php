<?php
require 'Mailinator.php';

//Get your token at https://www.mailinator.com/settings.jsp (create an account first)//
$mailinator = new Mailinator('token');

var_dump($mailinator->fetchInbox('randomemailaddress'));

var_dump($mailinator->fetchMail('emailidgoeshere'));