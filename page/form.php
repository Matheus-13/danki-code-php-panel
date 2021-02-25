<?php
	require_once '../config.php';

	$subject = 'Assunto do email.';
	$body = '';
	foreach ($_POST as $key => $value) {
		$body .= ucfirst($key).": ".$value;
		$body .= "<hr/>";
	}

	$email = new Email('smtp.gmail.com', 'ramalholiveira13@gmail.com',
			'toruna178000', 'Naruto');
	$email->addAdress('ramalholiveira13@gmail.com', 'Sasuke');
	$email->formatEmail(array('subject'=>$subject, 'body'=>$body));

	if ($email->sendEmail()) {
		$data['success'] = true;
	} else {
		$data['success'] = false;
	}

	die(json_encode($data));
?>