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



	public function __construct($for, $from, $subject)
	{
		if (is_array($for))
		{
			foreach ($for as $destinataire) 
			{
				$this->to .= $destinataire.',';
			}

			$this->to = substr($this->to, 0, strlen($this->to) - 1);
		}
		else
		{
			$this->to = $for;
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
		//$this->headers[] = 'From: '.$this->from;
		$this->headers[] = 'MIME-version: '.$mimeVersion;
		$this->headers[] = 'Content-Type: '.$contentType.'; charset='.$charset;
		
		if ($cc !== null)
		{
			$this->headers[] = 'Cc: '.$cc;
		}
		if ($bcc !== null)
		{
			$this->headers[] = 'Bcc: '.$bcc;
		}
	}


	public function setTemplate() {

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
				$this->message .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
				$this->message .= '<html xmlns="http://www.w3.org/1999/xhtml">';
				$this->message .= '<head>';
				$this->message .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
				$this->message .= '<meta name="viewport" content="initial-scale=1.0"/>';
				$this->message .= '<title>'.$title.'</title>';
				//$this->message .= '<style>'.$style.'</style>';
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
		if ($this->canBeSend) 
		{
			//echo 'mail('.$this->to.', '.$this->subject.', '.$this->message.', '.implode('\r\n', $this->headers).')';

			$header = implode("\r\n", $this->headers);
			$header .= $this->from."\r\n".$header;
			$header .= "\r\n";
			
			$sending = mail($this->to, $this->subject, $this->message, $header);

			if ($sending)
			{
				$this->isSend = true;
				return true;
			}
			else
			{
				$this->isSend = false;
			}
			
		}
		
		return false;
	}
}


?>