<?php
	use PHPMailer\PHPMailer\Exception;
	use PHPMailer\PHPMailer\PHPMailer;
	
	class Email
	{
		private $mailer;

		public function __construct($host, $username, $password, $name) {
			$this->mailer = new PHPMailer;
			$this->mailer->isSMTP();             // Set mailer to use SMTP
			$this->mailer->SMTPDebug = 0;        /* Enable SMTP debugging
			0 = off (for production use)
			1 = client messages
			2 = client and server messages */

			$this->mailer->Host = $host;         // Specify main and backup SMTP servers
			$this->mailer->SMTPAuth = true;      // Enable SMTP authentication
			$this->mailer->Username = $username; // SMTP username
			$this->mailer->Password = $password; // SMTP password

			$this->mailer->SMTPSecure = 'tls';   // Enable TLS encryption, `ssl` also accepted
			$this->mailer->Port = 587;           // TCP port to connect to
			$this->mailer->setFrom($username, $name);
			$this->mailer->isHTML(true);         // Set email format to HTML
			$this->mailer->CharSet = 'UTF-8';

		}

		public function addAdress($email, $name) {
			$this->mailer->addAddress($email, $name);
		}

		public function formatEmail($message) {
			$this->mailer->Subject = $message['subject'];
			$this->mailer->Body    = $message['body'];
			$this->mailer->AltBody = strip_tags($message['body']);
		}

		public function sendEmail() {
			if ($this->mailer->send()) {
				return true;
			} else {
				return false;
			}
		}
	}

?>