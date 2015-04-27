<?php 

/**
 * Description of tools
 *
 * @author Nicolas Beurion
 */

class MailSender
{

	private $to = '';
	private $subject = null;
	private $message = '';
	private $from = null;
	private $headers = array();

	private $canBeSend = false;
	private $isSend = false;



	public function __construct($to, $from, $subject)
	{
		if (is_array($to))
		{
			foreach ($to as $destinataire) 
			{
				$this->to .= $destinataire.', ';
			}
		}
		else
		{
			$this->to = $to;
		}
		
		$this->from = $from;
		$this->subject = $subject;
	}


	public function getCanBeSend()
	{
		return $this->canBeSend;
	}

	public function getIsSend()
	{
		return $this->isSend;
	}



	public function setHeader($mimeVersion = '1.0', $contentType = 'text/html', $charset = 'utf-8', $cc = null, $bcc = null)
	{
		$this->headers[] = 'MIME-Version: '.$this->$mimeVersion;
		$this->headers[] = 'Content-type: '.$contentType.'; charset='.$charset;
		$this->headers[] = 'From: '.$this->from;
		if ($cc !== null)
		{
			$this->headers[] = 'Cc: '.$cc;
		}
		if ($bcc !== null)
		{
			$this->headers[] = 'Bcc: '.$bcc;
		}
	}


	public function setMessage($messageBody, $messageType = "html", $title = null, $style = null)
	{

		$this->canBeSend = true;

		switch ($messageType) 
		{
			case 'text':
				$this->message = $messageBody;
				break;

			case 'html':
				$this->message = '<html>';
				$this->message .= '<head>';
				$this->message .= '<title>'.$title.'</title>';
				$this->message .= '<style>'.$style.'</style>';
				$this->message .= '</head>';
				$this->message .= '<body>';
				$this->message .= $messageBody;
				$this->message .= '</body>';
				$this->message .= '</html>';
				break;

			default :
				$this->canBeSend = false;
				break;
		}
	}


	public function send()
	{
		if ($this->canBeSend) {
			echo 'mail('.$this->to.', '.$this->subject.', '.$this->message.', '.implode('/r/n', $this->headers).')';
			exit;

			//$sending = mail($this->to, $this->subject, $this->message, implode('/r/n', $this->headers));

			/*
			if ($sending)
			{
				$this->isSend = true;
				return true;
			}
			else
			{
				$this->isSend = false;
			}
			*/
		}
		
		return false;
	}
	
}


?>