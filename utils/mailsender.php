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


	public function createHeaders($mimeVersion = '1.0', $contentType = 'text/html', $charset = 'utf-8', $cc = null, $bcc = null)
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


	public function createMessage($messageBody, $messageType = "html", $title = null)
	{

	}


	public function send()
	{
		echo 'mail('.$this->to.', '.$this->subject.', '.$this->message.', '.implode('/r/n', $this->headers).')';
		exit;

		//mail($this->to, $this->subject, $this->message, implode('/r/n', $this->headers));
	}
	
}


?>