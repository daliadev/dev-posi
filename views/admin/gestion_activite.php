<?php




// S'il y a des valeurs déjà existantes pour le formulaire, on remplace les valeurs par défaut par ces valeurs
if (isset($response['form_data']) && !empty($response['form_data']))
{      
    foreach($response['form_data'] as $key => $value)
    {
        if (is_array($response['form_data'][$key]))
        {
            for ($i = 0; $i < count($response['form_data'][$key]); $i++)
            {
                $formData[$key][$i] = $response['form_data'][$key][$i];
            }
        }
        else 
        {
            $formData[$key] = $value;
        }
    }
}

$form_url = $response['url'];


?>


<?php

$user = samtest;

$bdd = positionnement;

$passwd  = sE6CgH44e8;

// Connexion au serveur
mysql_connect($host, $user,$passwd) or die("erreur de connexion au serveur");

mysql_select_db($bdd) or die("erreur de connexion a la base de donnees");


$request="SELECT * FROM competence";
$result = mysql_query($request);
$competences=array();

$request_act="SELECT * FROM activite  ORDER BY nom_activite ASC";
$result_act = mysql_query($request_act);
$activites=array();

while($row = mysql_fetch_assoc($result)){
	$competences[]=$row;
	}
while($row2 = mysql_fetch_assoc($result_act)){
	$activites[]=$row2;
	}	
	

//var_dump($activites);
?>

<script type="text/javascript">
//<![CDATA[ 
$(window).load(function(){
$(":checkbox").on("change", function() {
    var that = this;
    $(this).parent().css("background-color", function() {
        return that.checked ? "#F1C40F" : "";
    });
});
});//]]>  

</script>



    <style>

    #content
        {
            width: 960px;
			
        }

    </style>


    <div id="titre-admin-h2">Administration positionnement // Gestion compétences activités</div>
    
    <!--- Partie haute : combo-box question --->
    
    <div>
        <form action="<?php echo $form_url; ?>" method="post" name="formu_admin_com_act" enctype="multipart/form-data">
               <div id="zone-competence">
					<div id="titre-question-h3">1- Choisir une compétence :</div></br>
						<select name="select" id="competence">
							<option>---</option>
							<?php foreach ($competences as $competence) : ?>
							<option value="<? $competence['id_comp']; ?>"><?  echo $competence['nom_comp']; ?></option>
							<?php endforeach; ?>
						</select>
			   </div>
			   <div id="zone-activite">
					<div id="titre-question-h3">2- Choisir les activités liées à cette compétence:</div>
				 
						<div id="ref_activites_admin"  >
							<?php foreach ($activites as $activite) : ?>
							<input type="submit" name="bt-acti-sup" class="bt-acti-sup" value="X" title="Supprimer">
							<label>
							<p>
								
                                <input type="checkbox"  name="admin_acti" value="<?php $activite['nom_activite']; ?>" /> <?php echo $activite['nom_activite']; ?>
								
                            </p>
							</label>
							<?php endforeach; ?>
                        </div>
						<input type="submit" name="enreg_acti"  class="bt-admin-menu-enreg-acti" value="Enregistrer" />
						
						<div id="zone-ajout-acti">
						<hr>
								<div id="titre-question-h3">Ajouter une activité</div></br>
								<input type="text" name="text_acti" class="text-add-acti" style="height:34px;" placeholder="Nom activité"/>
								<input type="submit" name="ok_acti" class="bt-admin-ok-ajout" value="Valider" />
						<hr>
								<div id="titre-question-h3">Modifier une activité</div></br>
								<select name="select" id="modifier_acti" onchange="document.getElementById('modif_acti').value = this.value;">
									<option>---</option>
									<?php foreach ($activites as $activite) : 
									echo '<option value="'.$activite['nom_activite'].'">'.$activite['nom_activite'].'</option>';
									 endforeach; ?>
								</select>
								modifier la sélection :<input type="text" name="modif_acti" class="text-add-acti" id="modif_acti" style="height:34px;" placeholder="Correction"/>
								<input type="submit" name="ok_acti" class="bt-admin-ok-ajout" value="Valider" />
								
				  
				   
						</div>
                </div> 
				
			  
        </form>
    </div>