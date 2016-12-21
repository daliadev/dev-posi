<?php


// Fichiers requis pour le formulaire
require_once(ROOT.'models/dao/question_dao.php');
require_once(ROOT.'models/dao/reponse_dao.php');
require_once(ROOT.'models/dao/categorie_dao.php');
require_once(ROOT.'models/dao/degre_dao.php');

require_once(ROOT.'controls/services_admin_categorie.php');



class ServicesAdminQuestion extends Main
{
	
	private $questionDAO = null;
	private $reponseDAO = null;
	private $categorieDAO = null;
	private $degreDAO = null;
	
	private $servicesCategorie = null;
	
	
	
	public function __construct() 
	{
		$this->controllerName = "adminQuestion";
		
		$this->questionDAO = new QuestionDAO();
		$this->reponseDAO = new ReponseDAO();
		$this->categorieDAO = new CategorieDAO();
		$this->degreDAO = new DegreDAO();
		
		$this->servicesCategorie = new ServicesAdminCategorie();
	}

	
	
	
	public function getAllQuestions()
	{
		$resultset = $this->questionDAO->selectAll();
		
		// Traitement des erreurs de la requête
		$this->filterDataErrors($resultset['response']);
		
		if (!empty($resultset['response']['question']) && count($resultset['response']['question']) == 1)
		{ 
			$question = $resultset['response']['question'];
			$resultset['response']['question'] = array($question);
		}
		
		return $resultset;
	}
	

	public function getQuestions()
	{
		$resultset = $this->questionDAO->selectByPosi();
		
		// Traitement des erreurs de la requête
		$this->filterDataErrors($resultset['response']);
		
		if (!empty($resultset['response']['question']) && count($resultset['response']['question']) == 1)
		{ 
			$question = $resultset['response']['question'];
			$resultset['response']['question'] = array($question);
		}
		
		return $resultset;
	}
	
	
	
	
	public function getQuestion($id_question)
	{
		$resultset = $this->questionDAO->selectById($id_question);
		
		// Traitement des erreurs de la requête
		$this->filterDataErrors($resultset['response']);
		
		return $resultset;
	}

	
	
	
	
	public function getQuestionDetails($idQuestion)
	{
		$questionDetails = array();
		
		$questionDetails['num_ordre_question'] = "";
		$questionDetails['intitule_question'] = "";
		$questionDetails['type_question'] = "";
		$questionDetails['image_question'] = "";
		$questionDetails['audio_question'] = "";
		$questionDetails['video_question'] = "";
		$questionDetails['ref_degre'] = "";
		$questionDetails['ref_question_cat'] = "";
		$questionDetails['code_cat'] = "";
		$questionDetails['ref_question_cat2'] = "";
		$questionDetails['code_cat2'] = "";
		
		$resultsetQuestion = $this->questionDAO->selectById($idQuestion);
		
		// Traitement des erreurs de la requête
		if (!$this->filterDataErrors($resultsetQuestion['response']))
		{
			$questionDetails['num_ordre_question'] = $resultsetQuestion['response']['question']->getNumeroOrdre();
			$questionDetails['intitule_question'] = $resultsetQuestion['response']['question']->getIntitule();
			$questionDetails['type_question'] = $resultsetQuestion['response']['question']->getType();
			$questionDetails['image_question'] = $resultsetQuestion['response']['question']->getImage();
			$questionDetails['audio_question'] = $resultsetQuestion['response']['question']->getSon();
			$questionDetails['video_question'] = $resultsetQuestion['response']['question']->getVideo();
			$questionDetails['ref_degre'] = $resultsetQuestion['response']['question']->getRefDegre();


			$linkedCategories = $this->getQuestionCategories($idQuestion);

			if (is_array($linkedCategories))
			{
				if (count($linkedCategories) === 1)
				{
					$questionDetails['ref_question_cat'] = $linkedCategories[0]['id_question_cat'];
					$questionDetails['code_cat'] = $linkedCategories[0]['ref_cat'];
				}
				else if (count($linkedCategories) > 1)
				{
					$questionDetails['ref_question_cat'] = $linkedCategories[0]['id_question_cat'];
					$questionDetails['code_cat'] = $linkedCategories[0]['ref_cat'];
					$questionDetails['ref_question_cat2'] = $linkedCategories[1]['id_question_cat'];
					$questionDetails['code_cat2'] = $linkedCategories[1]['ref_cat'];
				}
			}
			
			
			if ($questionDetails['type_question'] == "qcm")
			{
				// On récupére le tableau des réponses correspondant à la question
				$reponses = $this->getReponses($idQuestion);
				if ($reponses)
				{
					$questionDetails['reponses'] = $reponses;
				}
				else 
				{
					$this->registerError("form_empty", "Il n'y a pas de réponses pour cette question.");
				}
			}
		}

		return $questionDetails;
	}
	
	
	
	
	
	public function getQuestionCategories($idQuestion)
	{

		$questionCat = array();
		
		//$resultsetCategories = $this->categorieDAO->selectByQuestion($idQuestion);

		$resultsetQuestionCat = $this->servicesCategorie->getQuestionCategorie($idQuestion);
		
		// Traitement des erreurs de la requête
		if (!$this->filterDataErrors($resultsetQuestionCat['response']) && !empty($resultsetQuestionCat['response']['question_cat']))
		{

			if (count($resultsetQuestionCat['response']['question_cat']) == 1)
			{ 
				$queCat = $resultsetQuestionCat['response']['question_cat'];
				$resultsetQuestionCat['response']['question_cat'] = array($queCat);
			}

			$i = 0;

			foreach($resultsetQuestionCat['response']['question_cat'] as $questCat)
			{
				$questionCat[$i] = array();
				$questionCat[$i]['id_question_cat'] = $questCat->getId();
				$questionCat[$i]['ref_question'] = $questCat->getRefQuestion();
				$questionCat[$i]['ref_cat'] = $questCat->getCodeCat();
				
				$i++;
			}

			/*
			// On va chercher les categories
			$resultsetCategories = $this->categorieDAO->selectByQuestion($idQuestion);
		
			if (!$this->filterDataErrors($resultsetCategories['response']) && !empty($resultsetCategories['response']['categorie'])) 
			{
				if (count($resultsetCategories['response']['categorie']) == 1)
				{ 
					$categorie = $resultsetCategories['response']['categorie'];
					$resultsetCategories['response']['categorie'] = array($categorie);
				}

				$i = 0;

				foreach($resultsetCategories['response']['categorie'] as $cat)
				{
					$categories[$i] = array();
					$categories[$i]['code_cat'] = $cat->getCode();
					$categories[$i]['nom_cat'] = $cat->getNom();
					$categories[$i]['descript_cat'] = $cat->getDescription();
					
					$i++;
				}
			}
			*/
			/*
			$i = 0;
			foreach($resultsetCategories['response']['categorie'] as $cat)
			{
				$categories[$i] = array();
				
				$categories[$i]['code_cat'] = $cat->getCode();
				$categories[$i]['nom_cat'] = $cat->getNom();
				$categories[$i]['descript_cat'] = $cat->getDescription();

				
				
				$i++;
			}
			*/
		}

		
		return $questionCat;
	}
	
	
	
	
	
	public function filterQuestionData(&$formData, $postData)
	{
		$dataQuestion = array();
		$dataQuestion['questions_cat'] = array();

		$dataReponses = array();
	
		/*** Récupèration des categorie ***/

		$formData['code_cat'] = $this->validatePostData($postData['code_cat_cbox'], "code_cat_cbox", "integer", true, "Aucune catégorie n'a été sélectionnée.", "La catégorie n'est pas correctement sélectionnée.");
		$dataQuestion['questions_cat'][0]['code_cat'] = $formData['code_cat'];

		$formData['ref_question_cat'] = $this->validatePostData($postData['ref_question_cat'], "ref_question_cat", "integer", false, "Aucune catégorie n'a été sélectionnée.", "La catégorie n'est pas correctement sélectionnée.");
		$dataQuestion['questions_cat'][0]['id_question_cat'] = $formData['ref_question_cat'];

		$formData['code_cat2'] = $this->validatePostData($postData['code_cat_cbox2'], "code_cat_cbox2", "integer", false, "Aucune catégorie secondaire n'a été sélectionnée.", "La catégorie secondaire n'est pas correctement sélectionnée.");
		$dataQuestion['questions_cat'][1]['code_cat'] = $formData['code_cat2'];

		$formData['ref_question_cat2'] = $this->validatePostData($postData['ref_question_cat2'], "ref_question_cat2", "integer", false, "Aucune catégorie secondaire n'a été sélectionnée.", "La catégorie n'est pas correctement sélectionnée.");
		$dataQuestion['questions_cat'][1]['id_question_cat'] = $formData['ref_question_cat2'];


		/*** Récupération de la référence de la question ***/
		
		if (isset($formData['ref_question']) && !empty($formData['ref_question']))
		{
			$dataQuestion['ref_question'] = $formData['ref_question'];

			$dataQuestion['questions_cat'][0]['ref_question'] = $dataQuestion['ref_question'];
			$dataQuestion['questions_cat'][1]['ref_question'] = $dataQuestion['ref_question'];
		}
		
		
		/*** Récupèration de la référence du degré d'aptitude ***/
			
		if (isset($postData['ref_degre']) && !empty($postData['ref_degre']) && $postData['ref_degre'] != "aucun")
		{
			$formData['ref_degre'] = $postData['ref_degre'];
			$dataQuestion['ref_degre'] = $formData['ref_degre'];
		}
		else
		{
			$formData['ref_degre'] = "aucun";
			$dataQuestion['ref_degre'] = null;
		}


		/*** Récupèration de l'intitule de la question ***/

		$formData['intitule_question'] = $this->validatePostData($postData['intitule_question'], "intitule_question", "string", true, "Aucun intitulé n'a été saisi.", "L'intitulé n'a été correctement saisi.");
		$dataQuestion['intitule_question'] = $formData['intitule_question'];


		/*** Récupèration du numero d'ordre de la question ***/

		$formData['num_ordre_question'] = $this->validatePostData($postData['num_ordre_question'], "num_ordre_question", "integer", true, "Aucun numéro d'ordre n'a été saisi.", "Le numéro d'ordre est incorrecte.");
		$dataQuestion['num_ordre_question'] = $formData['num_ordre_question'];


		/*** Traitement du type de question ***/

		// Test pour savoir quel est le type de question
		if (isset($postData['type_question']) && !empty($postData['type_question']))
		{
			if ($postData['type_question'] == "qcm")
			{
				$formData['type_question'] = "qcm";
			}
			else if ($postData['type_question'] == "champ_saisie")
			{
				$formData['type_question'] = "champ_saisie"; 
			}

			$dataQuestion['type_question'] = $formData['type_question'];
		}
		else 
		{
			$this->registerError("form_empty", "Aucun type de question n'a été saisi.");
		}


		/*** Traitement des réponses du type qcm ***/

		if ($formData['type_question'] == "qcm")
		{
			if (isset($postData['intitules_reponses']) && is_array($postData['intitules_reponses']) && count($postData['intitules_reponses']) > 0)
			{
				$estCorrect = 0;
				$dataReponses = array();
				
				for ($i = 0; $i < count($postData['intitules_reponses']); $i++)
				{
					$dataReponses[$i]['ref_question'] = $formData['ref_question'];

					$formData['reponses'][$i]['num_ordre_reponse'] = $i + 1;
					$dataReponses[$i]['num_ordre_reponse'] = $formData['reponses'][$i]['num_ordre_reponse'];

					if (isset($postData['ref_reponses'][$i]) && !empty($postData['ref_reponses'][$i]))
					{
						$formData['reponses'][$i]['ref_reponse'] = $postData['ref_reponses'][$i];
						$dataReponses[$i]['ref_reponse'] = $formData['reponses'][$i]['ref_reponse'];
					}

					if (isset($postData['intitules_reponses'][$i]))
					{
						$intituleReponse = $this->filterData($postData['intitules_reponses'][$i], "string");

						if ($intituleReponse != "empty" && $intituleReponse != false)
						{
							$formData['reponses'][$i]['intitule_reponse'] = $intituleReponse;
						}
						else 
						{
							$formData['reponses'][$i]['intitule_reponse'] = "";
						}
						$dataReponses[$i]['intitule_reponse'] = $formData['reponses'][$i]['intitule_reponse'];
					}

					if (isset($postData['correct']) && $postData['correct'] == $dataReponses[$i]['num_ordre_reponse'])
					{
						$estCorrect = 1;
						$formData['reponses'][$i]['est_correct'] = 1;
						$dataReponses[$i]['est_correct'] = $formData['reponses'][$i]['est_correct'];
					}
					else 
					{
						$formData['reponses'][$i]['est_correct'] = 0;
						$dataReponses[$i]['est_correct'] = 0;
					}
				}
				
				$dataQuestion['data_reponses'] = $dataReponses;
						
				if ($estCorrect === 0)
				{
					$this->registerError("form_empty", "Vous n'avez pas sélectionné la bonne réponse.");
				}
			}
			else 
			{
				$this->registerError("form_empty", "Vous devez saisir au moins 1 réponse.");
			}
		}



		/*** Traitement de l'image ***/

		$maxSize = $postData['MAX_FILE_SIZE'];

		if (isset($_FILES['image_file']['name']) && !empty($_FILES['image_file']['name']))
		{
			$mimeType = str_replace("image/", "", $_FILES['image_file']['type']);

			if ($_FILES['image_file']['error'] > 0)
			{
				$this->registerError("form_valid", 'Une erreur s\'est produite lors du transfert du fichier image.');
			}
			else if ($_FILES['image_file']['size'] > $maxSize)
			{
				$this->registerError("form_valid", 'La taille du fichier image dépasse la limite autorisée (20 Mo).');
			}
			else 
			{
				if ($mimeType == "jpeg" || $mimeType == "jpg" || $mimeType == "png") 
				{
					$formData['image_type'] = $mimeType;
					$formData['image_upload'] = true;
					$formData['image_question'] = "";
					$dataQuestion['image_question'] = null;
				}
				else
				{
					$this->registerError("form_valid", 'Le format de l\'image est incorrect (format ".jpg" ou ".png" uniquement).');
				}
			}
		}
		else if (isset($postData['image_question']) && !empty($postData['image_question']))
		{
			$formData['image_upload'] = false;
			$formData['image_question'] = $postData['image_question'];
			$dataQuestion['image_question'] = $formData['image_question'];
		}
		else 
		{
			$formData['image_upload'] = false;
			$formData['image_question'] = "";
			$dataQuestion['image_question'] = null;
		}



		/*** Traitement du son ***/

		if (isset($_FILES['audio_file']['name']) && !empty($_FILES['audio_file']['name']))
		{
			$mimeType = str_replace("audio/", "", $_FILES['audio_file']['type']);

			if ($mimeType == "mp3" || $mimeType == "mpeg" || $mimeType == "mpeg3")
			{
				$formData['audio_type'] = $mimeType;
				$formData['audio_upload'] = true;
				$formData['audio_question'] = "";
				$dataQuestion['audio_question'] = null;
			}
			else
			{
				$this->registerError("form_empty", 'Le format du son est incorrect (format ".mp3" uniquement).');
			}
		}
		else if (isset($postData['audio_question']) && !empty($postData['audio_question']))
		{
			$formData['audio_upload'] = false;
			$formData['audio_question'] = $postData['audio_question'];
			$dataQuestion['audio_question'] = $formData['audio_question'];
		}
		else 
		{
			if (isset($postData['video_suppr']) && !empty($postData['video_suppr'])) 
			{
				$this->deleteMedia(ROOT.VIDEO_PATH, $postData['video_suppr']);
			}
			$formData['audio_upload'] = false;
			$formData['audio_question'] = "";
			$dataQuestion['audio_question'] = null;
		}



		/*** Traitement de la vidéo ***/

		if (isset($_FILES['video_file']['name']) && !empty($_FILES['video_file']['name']))
		{
			$mimeType = str_replace("video/", "", $_FILES['video_file']['type']);

			if ($mimeType == "mp4"  || $mimeType == "mpeg4" || $mimeType == "mpeg")
			{
				$formData['video_type'] = $mimeType;
				$formData['video_upload'] = true;
				$formData['video_question'] = "";
				$dataQuestion['video_question'] = null;
			}
			else
			{
				$this->registerError("form_empty", 'Le format de la vidéo est incorrect (format ".mp4" uniquement).');
			}
		}
		else if (isset($postData['video_question']) && !empty($postData['video_question']))
		{
			$formData['video_upload'] = false;
			$formData['video_question'] = $postData['video_question'];
			$dataQuestion['video_question'] = $formData['video_question'];
		}
		else 
		{
			$formData['video_upload'] = false;
			$formData['video_question'] = "";
			$dataQuestion['video_question'] = null;
		}

		//var_dump($dataQuestion);

		return $dataQuestion;

	}
	
	
	
	
	
	public function setQuestionProperties($previousMode, $dataQuestion, &$formData)
	{

		$dataReponses = array();
		$dataQuestionsCat = array();

		// On commence par extraire les réponses (si elles existent) des données de la question
		if (isset($dataQuestion['data_reponses']) && !empty($dataQuestion['data_reponses']))
		{
			$dataReponses = $dataQuestion['data_reponses'];
			unset($dataQuestion['data_reponses']);
		}

		if (isset($dataQuestion['questions_cat']) && !empty($dataQuestion['questions_cat']))
		{
			$dataQuestionsCat = $dataQuestion['questions_cat'];
			unset($dataQuestion['questions_cat']);
		}
		
		
		if ($previousMode == "new")
		{
			// On test pour savoir si le numéro d'ordre de la question à enregistrer existe déjà
			$numsOrdreList = $this->getNumsOrdreList();

			$questionExist = false;
			$shiftOrdre = false;

			//if (!$numsOrdreList || $numsOrdreList == 0) 
			//{
				for ($i = 0; $i < count($numsOrdreList); $i++) 
				{
					if ($numsOrdreList[$i] == $formData['num_ordre_question'])
					{
						// S'il est réservé, on décale les numéros d'ordre avec n+1 pour toutes les questions supérieures à la question active (shift = décaler);
						$shiftOrdre = $this->shiftNumsOrdre($formData['num_ordre_question'], 1);
						
						$questionExist = true;
						break;
					}
				}
			//}
			
			if ($shiftOrdre || !$questionExist)
			{
				// Insertion des médias
				if ($formData['image_upload'])
				{
					$formData['image_question'] = $this->setMedia("image", $_FILES, $formData['num_ordre_question'], $formData['image_type'], array("jpg", "jpeg", "png"));
					$dataQuestion['image_question'] = $formData['image_question'];
				}
				
				if ($formData['audio_upload'])
				{
					$formData['audio_question'] = $this->setMedia("audio", $_FILES, $formData['num_ordre_question'], $formData['audio_type'], array("mp3", "mpeg", "mpeg3"));
					$dataQuestion['audio_question'] = $formData['audio_question'];
				}

				if ($formData['video_upload'])
				{
					$formData['video_question'] = $this->setMedia("video", $_FILES, $formData['num_ordre_question'], $formData['video_type'], array("mp4", "mpeg4"));
					$dataQuestion['video_question'] = $formData['video_question'];
				}


				// Insertion de la question dans la bdd
				$resultsetQuestion = $this->setQuestion("insert", $dataQuestion);


				if (isset($resultsetQuestion['response']['question']['last_insert_id']) && !empty($resultsetQuestion['response']['question']['last_insert_id']))
				{
					// Insertion des réponses si le type est QCM
					$formData['ref_question'] = $resultsetQuestion['response']['question']['last_insert_id'];
					$dataQuestion['ref_question'] = $formData['ref_question'];

					if ($formData['type_question'] == "qcm")
					{
						if (!empty($dataReponses) && count($dataReponses) > 0)
						{
							for ($i = 0; $i < count($dataReponses); $i++)
							{
								$dataReponses[$i]['ref_question'] = $formData['ref_question'];
							}

							$resultsetReponses = $this->setReponses($dataReponses, $formData['ref_question']);

						}
						else
						{
							$this->registerError("form_valid", "Vous devez saisir au moins 1 réponse.");
						}
					}

					// Insertion des catégories
					if (!empty($dataQuestionsCat)) 
					{
						for ($i = 0; $i < count($dataQuestionsCat); $i++) { 

							$resultsetQuestionCat = $this->servicesCategorie->setQuestionCategorie("insert", null, $dataQuestion['ref_question'], $dataQuestionsCat[$i]['code_cat']);
						
							if (!$resultsetQuestionCat)
							{
								$this->registerError("form_request", "La catégorie liée à la question n'a pas été enregistrée.");
								break;
							}
						}

					}

				}
				else 
				{
					$this->registerError("form_valid", "L'enregistrement de la question a échoué.");
				}
				
			}
		}
		else if ($previousMode == "edit" || $previousMode == "save")
		{

			if (isset($dataQuestion['ref_question']) && !empty($dataQuestion['ref_question']))
			{
				$formData['ref_question'] = $dataQuestion['ref_question'];

				// Insertion des médias
				if ($formData['image_upload'])
				{
					$formData['image_question'] = $this->setMedia("image", $_FILES, $formData['num_ordre_question'], $formData['image_type'], array("jpg", "jpeg", "png"));
					$dataQuestion['image_question'] = $formData['image_question'];
				}
				
				if ($formData['audio_upload'])
				{
					$formData['audio_question'] = $this->setMedia("audio", $_FILES, $formData['num_ordre_question'], $formData['audio_type'], array("mp3", "mpeg", "mpeg3"));
					$dataQuestion['audio_question'] = $formData['audio_question'];
				}

				if ($formData['video_upload'])
				{
					$formData['video_question'] = $this->setMedia("video", $_FILES, $formData['num_ordre_question'], $formData['video_type'], array("mp4", "mpeg4"));
					$dataQuestion['video_question'] = $formData['video_question'];
				}
				/*
				else if ($formData['video_suppr'] == "true") 
				{
					$this->getMedia($type, $numOrdreQuestion);
				}
				*/
				

				// Mise à jour de la question
				$resultsetQuestion = $this->setQuestion("update", $dataQuestion);

				if ($resultsetQuestion)
				{
					// Mises à jour des réponses
					if ($formData['type_question'] == "qcm")
					{
						if (!empty($dataReponses) && count($dataReponses) > 0)
						{
							$resultsetReponses = $this->setReponses($dataReponses, $formData['ref_question']);
						}
						else
						{
							$this->registerError("form_empty", "Vous devez saisir au moins 1 réponse.");
						}
					}
					else if ($formData['type_question'] == "champ_saisie")
					{
						// On efface les réponses s'il y en a
						$this->deleteReponses($formData['ref_question']);
					}

					//var_dump($dataQuestionsCat);
					//exit();
				
					// Mise à jour ou insertion de la catégorie
					if (!empty($dataQuestionsCat)) 
					{
						$modeQuestionCat = null;

						for ($i = 0; $i < count($dataQuestionsCat); $i++) { 

							if (isset($dataQuestionsCat[$i]['id_question_cat']) && !empty($dataQuestionsCat[$i]['id_question_cat'])) 
							{
								if ($dataQuestion['ref_question'] && !empty($dataQuestion['ref_question']) && $dataQuestionsCat[$i]['code_cat'] && !empty($dataQuestionsCat[$i]['code_cat'])) 
								{
									$modeQuestionCat = "update";
								}
								else
								{
									$modeQuestionCat = "delete";
								}
							}
							else
							{
								$dataQuestionsCat[$i]['id_question_cat'] = null;
								$modeQuestionCat = "insert";
							}

							//var_dump($modeQuestionCat, $dataQuestionsCat[$i]['id_question_cat'], $dataQuestion['ref_question'], $dataQuestionsCat[$i]['code_cat']);

							$resultsetQuestionCat = $this->servicesCategorie->setQuestionCategorie($modeQuestionCat, $dataQuestionsCat[$i]['id_question_cat'], $dataQuestion['ref_question'], $dataQuestionsCat[$i]['code_cat']);
						
							if (!$resultsetQuestionCat)
							{
								$this->registerError("form_request", "La catégorie liée à la question n'a pas été enregistrée.");
								break;
							}

							//var_dump($resultsetQuestionCat);
						}

					}
				}
				else 
				{
					$this->registerError("form_valid", "L'enregistrement de la question a échoué.");
				}
			}
		}
		else
		{
			header("Location: ".SERVER_URL."erreur/page404");
			exit();
		}

	}
	
	
	
	
	
	public function setQuestion($modeRequete, $dataQuestion)
	{

		if (!empty($dataQuestion) && is_array($dataQuestion))
		{
			$success = false;
			
			if ($modeRequete == "insert")
			{
				
				$resultset = $this->questionDAO->insert($dataQuestion);

				// Traitement des erreurs de la requête
				if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['question']['last_insert_id']) && !empty($resultset['response']['question']['last_insert_id']))
				{
					return $resultset;
				}
				else 
				{
					$this->registerError("form_request", "La question n'a pu être insérée.");
				}
			}
			else if ($modeRequete == "update")
			{

				if (!empty($dataQuestion['ref_question']))
				{
					
					$resultset = $this->questionDAO->update($dataQuestion);
					
					// Traitement des erreurs de la requête
					if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['question']['row_count']))
					{
						return $resultset;
					} 
					else 
					{
						$this->registerError("form_request", "La question n'a pu être mise à jour.");
					}
				}
				else 
				{
					$this->registerError("form_request", "L'identifiant de la question est manquant.");
				}
			}
		}
		else 
		{
			$this->registerError("form_request", "Insertion de la question non autorisée.");
		}
			
		return false;
	}
	
	
	
	
	
	public function deleteQuestion($refQuestion)
	{
		// On commence par sélectionner les réponses associèes à la question
		$resultsetSelect = $this->questionDAO->selectById($refQuestion);
		
		if (!$this->filterDataErrors($resultsetSelect['response']))
		{ 
			$question = $resultsetSelect['response']['question'];
			$resultsetDelete = $this->questionDAO->delete($refQuestion);

			if (!$this->filterDataErrors($resultsetDelete['response']))
			{
				// On supprime les fichiers médias   
				if ($question->getImage())
				{
					$this->deleteMedia(ROOT.IMG_PATH, $question->getImage());
					$this->deleteMedia(ROOT.THUMBS_PATH, "thumb_".$question->getImage());
				}

				if ($question->getSon())
				{
					$this->deleteMedia(ROOT.AUDIO_PATH, $question->getSon());
				}

				if ($question->getVideo())
				{
					$this->deleteMedia(ROOT.VIDEO_PATH, $question->getVideo());
				}

				// On décale toutes les questions qui suivent d'un cran
				$shiftOrdre = $this->shiftNumsOrdre($question->getNumeroOrdre(), -1);
				
				if ($shiftOrdre)
				{
					return true;
				}
				else
				{
					$this->registerError("form_request", "Le décalage des questions a échoué.");
				}
			}
			else 
			{
				$this->registerError("form_request", "Impossible de supprimer la question.");
			}
		}
		else
		{
		   $this->registerError("form_request", "Cette question n'existe pas."); 
		}

		return false;
	}
	
	





	
	public function getReponses($refQuestion)
	{
		$reponses = array();
		
		$resultsetReponses = $this->reponseDAO->selectByQuestion($refQuestion);

		// Traitement des erreurs de la requête
		if (!$this->filterDataErrors($resultsetReponses['response']))
		{
			if (isset($resultsetReponses['response']['reponse']) && !empty($resultsetReponses['response']['reponse']))
			{
				if (count($resultsetReponses['response']['reponse']) == 1)
				{
					$reponse = $resultsetReponses['response']['reponse'];
					$resultsetReponses['response']['reponse'] = array($reponse);
				}

				$i = 0;
				foreach($resultsetReponses['response']['reponse'] as $reponse)
				{
					$reponses[$i] = array();
					$reponses[$i]['ref_reponse'] = $reponse->getId();
					$reponses[$i]['ref_question'] = $reponse->getRefQuestion();
					$reponses[$i]['num_ordre_reponse'] = $reponse->getNumeroOrdre();
					$reponses[$i]['intitule_reponse'] = $reponse->getIntitule();
					$reponses[$i]['est_correct'] = $reponse->getEstCorrect();
					
					$i++;
				}
				
				return $reponses;
			}

		}
		
		return false;
	}
	
	
	
	
	public function setReponses($dataReponses, $refQuestion)
	{
				
		if (!empty($dataReponses) && is_array($dataReponses) && count($dataReponses) > 0)
		{
				
			// on commence par chercher les réponses déjà existantes et on les supprime
			$existReponses = $this->getReponses($refQuestion);
			
			if ($existReponses)
			{
				for ($i = 0; $i < count($existReponses); $i++)
				{
					if (!isset($dataReponses[$i]['intitule_reponse']) || empty($dataReponses[$i]['intitule_reponse']))
					{
						$this->deleteReponse($existReponses[$i]['ref_reponse']);
						
					}
				}
			}


			$successCount = 0;
			$countReponses = 0;
			
			for ($i = 0; $i < count($dataReponses); $i++)
			{
				if (isset($dataReponses[$i]) && !empty($dataReponses[$i]))
				{
					$dataReponse = $dataReponses[$i];

					if (!empty($dataReponse['intitule_reponse']) && strlen($dataReponse['intitule_reponse']) > 0 && !empty($dataReponse['num_ordre_reponse']) && (!empty($dataReponse['est_correct']) || $dataReponse['est_correct'] == 0))
					{
						$countReponses++;

						if (empty($dataReponse['ref_reponse']))
						{
							// Insertion de la réponse
							$resultset = $this->reponseDAO->insert($dataReponse);

							// Traitement des erreurs de la requête
							if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['reponse']['last_insert_id']) && !empty($resultset['response']['reponse']['last_insert_id']))
							{
								$successCount++;
							}
						}
						else
						{
							// Mise à jour de la réponse
							$resultset = $this->reponseDAO->update($dataReponse);

							// Traitement des erreurs de la requête
							if (!$this->filterDataErrors($resultset['response']))
							{
								$successCount++;
							}  
						}
					}
					else
					{
						unset($dataReponses[$i]);
					}
				}
			} 


			if ($successCount == $countReponses)
			{
				return true;
			}
			else if ($successCount > 0)
			{
				$this->registerError("form_valid", "Toutes les réponses n'ont pas pu être sauvegardées.");
			}
			else
			{
				$this->registerError("form_valid", "Aucune réponse n'a pu être sauvegardée.");
			}
			
		}
		
		
		return false;
	}
	



	
	public function deleteReponses($refQuestion)
	{
		// On commence par sélectionner les réponses associèes à la question
		$resultset = $this->reponseDAO->selectByQuestion($refQuestion);

		$success = 0;
		
		// Traitement des erreurs de la requête
		if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['reponse']) && !empty($resultset['response']['reponse']))
		{  
			$reponses = $resultset['response']['reponse'];
			
			if (is_array($reponses) && count($reponses) > 0)
			{
				foreach ($reponses as $reponse)
				{
					$refReponse = $reponse->getId();
					$resultsetDelete = $this->reponseDAO->delete($refReponse);

					if (!$this->filterDataErrors($resultsetDelete['response']))
					{
						$success++;
					}
				}
			}
			else 
			{
				$refReponse = $reponses->getId();
				$resultsetDelete = $this->reponseDAO->delete($refReponse);
				 
				if (!$this->filterDataErrors($resultsetDelete['response']))
				{
				   $success++;
				}
			}

			if ($success == count($reponses))
			{
				return true;
			}
		}
		
		return false;
	}
	
	
	public function deleteReponse($refReponse)
	{
		
		$resultsetDelete = $this->reponseDAO->delete($refReponse);
		if (!$this->filterDataErrors($resultsetDelete['response']))
		{
			return true;
		}
		
		return false;
	}

	
	
	

	public function getMedia($type, $numOrdreQuestion)
	{

		$resultsetQuestion = $this->questionDAO->selectByOrdre($numOrdreQuestion);

		// Filtrage des erreurs de la requête
		if (!$this->filterDataErrors($resultsetQuestion['response']) && count($resultsetQuestion['response']) > 0 )
		{
			// Si le résultat est unique
			if (!empty($resultsetQuestion['response']['question']) && count($resultsetQuestion['response']['question']) == 1)
			{ 
				$question = $resultsetQuestion['response']['question'];
				$resultsetQuestion['response']['question'] = array($question);
			}

			if ($type == "image")
			{
				return $resultsetQuestion['response']['question'][0]->getImage();
			}
			else if ($type == "audio")
			{
				return $resultsetQuestion['response']['question'][0]->getSon();
			}
			else if ($type == "video")
			{
				return $resultsetQuestion['response']['question'][0]->getVideo();
			}
		}

		return false;
	}





	public function setMedia($type, &$files, $numOrdreQuestion, $mediaExt, $authorizedFormats)
	{
		$oldMediaName = $this->getMedia($type, $numOrdreQuestion);


		if ($type == "image" && $files['image_file']['error'] == 0 && isset($files['image_file']['name']) && !empty($files['image_file']['name']))
		{
			if ($oldMediaName)
			{
				$this->deleteMedia(ROOT.IMG_PATH, $oldMediaName);
				$this->deleteMedia(ROOT.THUMBS_PATH, "thumb_".$oldMediaName);
			}

			if ($mediaExt == "jpeg") 
			{
				$mediaExt = "jpg";
			}

			$mediaName = "img_".$numOrdreQuestion."_".uniqid(); //.".".$mediaExt;

			$imageFile = ROOT.IMG_PATH.$mediaName.".".$mediaExt;
			$thumbFile = ROOT.THUMBS_PATH."thumb_".$mediaName.".".$mediaExt;
			
			$this->uploadMedia($files['image_file'], "image", $authorizedFormats, ROOT.IMG_PATH, $mediaName, $mediaExt);

			if (!empty($this->errors))
			{
				$this->registerError("form_valid", "L'image n'a pas pu être enregistrée.");
				$this->deleteMedia(ROOT.IMG_PATH, $mediaName.".".$mediaExt);
				$this->deleteMedia(ROOT.THUMBS_PATH, "thumb_".$mediaName.".".$mediaExt);
			}

			return $mediaName.".".$mediaExt;
		}
		else if ($type == "audio" && $files['audio_file']['error'] == 0 && isset($files['audio_file']['name']) && !empty($files['audio_file']['name']))
		{
			if ($oldMediaName)
			{
				$this->deleteMedia(ROOT.AUDIO_PATH, $oldMediaName);
			}

			if ($mediaExt == "mpeg" || $mediaExt == "mpeg3") 
			{
				$mediaExt = "mp3";
			}

			$mediaName = "audio_".$numOrdreQuestion."_".uniqid(); //.".".$mediaExt;

			$soundFile = ROOT.AUDIO_PATH.$mediaName.".".$mediaExt;

			$this->uploadMedia($_FILES['audio_file'], "son", $authorizedFormats, ROOT.AUDIO_PATH, $mediaName, $mediaExt);

			if (!empty($this->errors)) 
			{
				$this->registerError("form_valid", "Le son n'a pas pu être enregistré.");
				$this->deleteMedia(ROOT.AUDIO_PATH, $mediaName.".".$mediaExt);
			}

			return $mediaName.".".$mediaExt;
		}
		else if ($type == "video" && $files['video_file']['error'] == 0 && isset($files['video_file']['name']) && !empty($files['video_file']['name']))
		{
			if ($oldMediaName)
			{
				$this->deleteMedia(ROOT.VIDEO_PATH, $oldMediaName);
			}

			if ($mediaExt == "mpeg4" || $mediaExt == "mpeg") 
			{
				$mediaExt = "mp4";
			}

			$mediaName = "video_".$numOrdreQuestion."_".uniqid(); //.".".$mediaExt;

			$videoFile = ROOT.VIDEO_PATH.$mediaName.".".$mediaExt;

			$this->uploadMedia($_FILES['video_file'], "video", $authorizedFormats, ROOT.VIDEO_PATH, $mediaName, $mediaExt);

			if (!empty($this->errors)) 
			{
				$this->registerError("form_valid", "La vidéo n'a pas pu être enregistré.");
				$this->deleteMedia(ROOT.VIDEO_PATH, $mediaName.".".$mediaExt);
			}

			return $mediaName.".".$mediaExt;
		}
		else 
		{
			$this->registerError("form_valid", "Une erreur s'est produite, le média n'a pas pu être enregistré.");
			return false;
		}

		
	}





	public function deleteMedia($path, $mediaName)
	{
		if (file_exists($path.$mediaName))
		{
			unlink($path.$mediaName);
		}
	}
	
	



	public function getNumsOrdreList()
	{
		$resultset = $this->questionDAO->selectAll();
		
		$numsOrdreList = array();
		
		if (!empty( $resultset['response']['question']))
		{
			if (!$this->filterDataErrors($resultset['response']))
			{
				if (is_array($resultset['response']['question']))
				{
					$questions = $resultset['response']['question'];
					foreach ($questions as $question)
					{
						$numsOrdreList[] = $question->getNumeroOrdre();
					}
				}
				else 
				{
					$numsOrdreList[] = $resultset['response']['question']->getNumeroOrdre();
				}
				
				
				return $numsOrdreList;
			}
		}

		return false;
	}
	
	
	
	
	
	public function getLastNumOrdre()
	{
		$resultset = $this->questionDAO->selectAll();
		
		if (!empty( $resultset['response']['question']))
		{
			$this->filterDataErrors($resultset['response']);

			$questions = $resultset['response']['question'];
			
			$i = 0;
			$lastNum = 0;
				
			if (is_array($resultset['response']['question']))
			{
				while ($i < count($questions))
				{
					$lastNum = $questions[$i]->getNumeroOrdre();
					$i++;
				}
			} 
			else 
			{
				$lastNum = $questions->getNumeroOrdre();
			}
		}
		else 
		{
			$lastNum = 0;
		}

		return $lastNum;
	}

	
	
	
	
	/**
	 * Créer un décalage de $offset de toutes les questions à partir de la question sélectionnée jusqu'à la dernière.
	 * 
	 * @param int $numOrdre Position dans la série des numéros
	 * 
	 */
	public function shiftNumsOrdre($numOrdre, $offset)
	{
		$lastNum = $this->getLastNumOrdre();
		
		$erreur = false;


		if ($offset > 0)
		{
			for ($i = $lastNum; $i >= $numOrdre; $i--)
			{
				$newNumOrdre = $i + $offset;

				$oldImageName = $this->getMedia("image", $i);
				$oldThumbName = "thumb_".$oldImageName;
				$oldAudioName = $this->getMedia("audio", $i);
				$oldVideoName = $this->getMedia("video", $i);

				$newImageName = null;
				$newAudioName = null;
				$newVideoName = null;

				if (!empty($oldImageName) && $oldImageName != false) 
				{
					$newImageName = "img_".$newNumOrdre."_".uniqid().".jpg";
					$newThumbName = "thumb_".$newImageName;
					rename(ROOT.IMG_PATH.$oldImageName, ROOT.IMG_PATH.$newImageName);
					rename(ROOT.THUMBS_PATH.$oldThumbName, ROOT.THUMBS_PATH.$newThumbName);
				}
				
				if (!empty($oldAudioName) && $oldAudioName != false) 
				{
					$newAudioName = "audio_".$newNumOrdre."_".uniqid().".mp3";
					rename(ROOT.AUDIO_PATH.$oldAudioName, ROOT.AUDIO_PATH.$newAudioName);
				}

				if (!empty($oldVideoName) && $oldVideoName != false) 
				{
					$newVideoName = "video_".$newNumOrdre."_".uniqid().".mp4";
					rename(ROOT.VIDEO_PATH.$oldVideoName, ROOT.VIDEO_PATH.$newVideoName);
				}

				$resultset = $this->questionDAO->shiftOrder($i, $offset, $newImageName, $newAudioName, $newVideoName);
				
				if ($this->filterDataErrors($resultset['response']) || empty($resultset['response']['question']['row_count']))
				{
					$erreur = true;
					break;
				}
			}
		}
		else if ($offset < 0)
		{
			for ($i = ($numOrdre + 1); $i <= $lastNum; $i++)
			{
				$newNumOrdre = $i + $offset;

				$oldImageName = $this->getMedia("image", $i);
				$oldThumbName = "thumb_".$oldImageName;
				$oldAudioName = $this->getMedia("audio", $i);
				$oldVideoName = $this->getMedia("video", $i);

				$newImageName = null;
				$newAudioName = null;
				$newVideoName = null;

				if (!empty($oldImageName) && $oldImageName != false) 
				{
					$newImageName = "img_".$newNumOrdre."_".uniqid().".jpg";
					$newThumbName = "thumb_".$newImageName;
					rename(ROOT.IMG_PATH.$oldImageName, ROOT.IMG_PATH.$newImageName);
					rename(ROOT.THUMBS_PATH.$oldThumbName, ROOT.THUMBS_PATH.$newThumbName);
				}
				
				if (!empty($oldAudioName) && $oldAudioName != false) 
				{
					$newAudioName = "audio_".$newNumOrdre."_".uniqid().".mp3";
					rename(ROOT.AUDIO_PATH.$oldAudioName, ROOT.AUDIO_PATH.$newAudioName);
				}

				if (!empty($oldVideoName) && $oldVideoName != false) 
				{
					$newVideoName = "video_".$newNumOrdre."_".uniqid().".mp4";
					rename(ROOT.VIDEO_PATH.$oldVideoName, ROOT.VIDEO_PATH.$newVideoName);
				}

				/*
				rename(ROOT.IMG_PATH.$oldImageName, ROOT.IMG_PATH.$newImageName);
				rename(ROOT.THUMBS_PATH.$oldThumbName, ROOT.THUMBS_PATH.$newThumbName);
				rename(ROOT.AUDIO_PATH.$oldAudioName, ROOT.AUDIO_PATH.$newAudioName);
				rename(ROOT.VIDEO_PATH.$oldVideoName, ROOT.VIDEO_PATH.$newVideoName);
				*/
				
				$resultset = $this->questionDAO->shiftOrder($i, $offset, $newImageName, $newAudioName, $newVideoName);

				if ($this->filterDataErrors($resultset['response']) || empty($resultset['response']['question']['row_count']))
				{
					$erreur = true;
					break;
				}
			}
		}
		
		if ($erreur)
		{
			$this->registerError("form_request", "Erreur lors du décalage des médias.");
			return false;
		}
		else 
		{
			return true;
		}
	}


	
	
	public function uploadMedia($file, $mediaType, $allowFormat, $path, $name, $ext)
	{
		$media_question = null;
				
		if ($file['error'] == 0)
		{
			// Récupération du suffix du fichier
			//$ext = strtolower(substr($file['name'], -3));

			if (in_array($ext, $allowFormat))
			{
				$completeName = $name.".".$ext;

				// Déplacement du fichier de sa position temp vers sa destination finale
				if (move_uploaded_file($file['tmp_name'], $path.$completeName))
				{
					if ($mediaType == "image")
					{
						require_once(ROOT."utils/image_uploader.php");
						
						/*
						// On recréé l'image au bon format
						ImageUploader::create($path.$completeName, $path, $name, $ext, true, 750, 420);

						// On créé la vignette de l'image
						ImageUploader::create($path.$completeName, ROOT.THUMBS_PATH, "thumb_".$name, $ext, false, 112, 70);
						*/

						// On recréé l'image au bon format
						ImageUploader::create($path.$completeName, $path, $name, $ext, true, null, null);

						// On créé la vignette de l'image
						ImageUploader::create($path.$completeName, ROOT.THUMBS_PATH, "thumb_".$name, $ext, false, 112, 70);
					}

					$media_question = $completeName;

				}
				else 
				{
					$this->registerError("form_data", "Aucun média ".$mediaType." n'a pu être chargée");
				}
			}
			else 
			{
				$this->registerError("form_data", "Le format du fichier n'est pas autorisé ou n'est pas de type ".$mediaType.".");
			}
		}
		else 
		{
			$this->registerError("form_empty", "Prise en charge impossible du fichier ".$mediaType.".");
		}
		

		return $media_question;
	}
	
  
	
}


?>
