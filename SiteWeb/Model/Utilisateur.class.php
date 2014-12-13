<?php
	/**
	*
	* @author Chataigner manuel 
	* @author Lopez jimmy
	*
	*/

	class Utilisateur
	{
		private $login;
		private $mdp;
		private $nom;
		private $prenom;
		private $admin;

		//TODO la liste des concepts et thermes
	/**
* Constructeur
* @param login de l'utilisateur à créer
*/
function __construct($donnees, $session)
{
	// TODO FAIRE VARIABLE DE SESSION
	// TODO TOUT A REFAIRE car incorect manu
	//$this->login = $login;
	/**/
	$this->login = $donnees->mail;
	/*$this->nom = $donnees->nom;
	$this->prenom = $donnees->prenom;
	$this->admin = $donnees->admin;

	if($session){ // au cas ou on doit créer des utilisateurs : pour profil des membre par exemple
		$_SESSION['login'] = $donnees->login;
		$_SESSION['nom'] = $donnees->nom;
		$_SESSION['prenom'] = $donnees->prenom;
		$_SESSION['admin'] = $donnees->admin;

		//TODO idem list des concept etc ...
	}*/

}

public function connexion() {
	//TODO faire verif
	$login = $_POST['login'];
	//$mdp = md5($_POST['password']);


	// refaire la requete pour la connexion d'un user 
	echo("888");
		
	$pdo = ConnexionBD::getPDO();

	echo($pdo);

	$query = "SELECT * FROM Utilisateur WHERE mail=".$login;
	$res = $pdo->prepare($query);
	$ssh = $res->execute();
	$row = $ssh->fetch();

	echo($row);


	if ($row)
	{
		echo($row);
		$user = new Utilisateur($row, false);
	}
	else
	{
		return 0;
	}

	//$pdo = ConnexionBD::getPDO();
	//$query = "SELECT * FROM Utilisateur WHERE login = '.$login.'" AND mdp = "'.$mdp.'";
	//$res = $pdo->query($query);
/*
	if($login === 'zerocooldu30@gmail.com' && $mdp === md5('a')) { // TODO tester si la requete est vide pour le teste je verif juste si login c'est mon mail perso
		//$user = new Utilisateur($login, false);
		$user = new Utilisateur($login);
		// md5($mdp);
		return $user;
	}
	else {
		return 0;
	}*/

}

public function connectionSuccess() {
	echo('<div class="bs-example">
			<div class="alert alert-success">
				<a href="#" class="close" data-dismiss="alert">&times;</a>
				<strong>Connexion réussi : </strong> Bienvenu sur le site Pokésaurus.
			</div>
		</div>'
		);

}



public function connectionFailed() {
	echo('<div class="bs-example">
			<div class="alert alert-danger">
				<a href="#" class="close" data-dismiss="alert">&times;</a>
				<strong>Échec connexion : </strong> L\'email et le mot de passe avec lequel vous êtes connecté n\'appartiennent à aucun compte de Pokésaurus.
			</div>
		</div>'
	);
}

public function inscription() {
	$login = $_POST['login'];
	$mdp = md5($_POST['password']);

	

}

public function inscriptionFailed() {
	echo('<div class="bs-example">
			<div class="alert alert-danger">
				<a href="#" class="close" data-dismiss="alert">&times;</a>
				<strong>Échec inscription : </strong> L\'email avec lequel vous êtes inscrit appartiennent à un compte existant de Pokésaurus.
			</div>
		</div>'
	);
}

public function inscriptionSuccess() {
	echo('<div class="bs-example">
			<div class="alert alert-success">
				<a href="#" class="close" data-dismiss="alert">&times;</a>
				<strong>Inscription réussi : </strong> Bienvenu sur le site Pokésaurus.
			</div>
		</div>'
		);
}

public function deconnection(){
	session_destroy();
}

public function getLogin()
{
	return $this->login;
}
public function getMdp()
{
	return $this->mdp;
}
public function getMail()
{
	return $this->mail;
}
public function estAdmin()
{
	return $this->admin;
}
/**
* @action Insère un concept dans la bd
* @param nomConcept Nom du concept
* @param description Description
* @param parent Parent du concept créé
*/
public function creerConcept($nomConcept, $description, $parent, $vedette)
{
	$pdo = ConnexionBD::getPDO();
	$query = "INSERT INTO Concept (idConcept, nomConcept, description, vedette, parents) VALUES (idConcept, '$nomConcept', '$description',
		(select REF(T) from TermeVedette T where idTerme = $vedette->getId()),
		GroupeConcept_t((SELECT ref(c) FROM Concept c WHERE c.idConcept = $parent->getId())));";
$pdo->query($query);
/** Tester si la nested table est vide pour connâitre la méthode d'ajout
* https://docs.oracle.com/cd/B12037_01/server.101/b10759/conditions013.htm
*/
// Insertion du concept dans la table Utilisateur
$query = "UPDATE Utilisateur
set concepts = GroupeConcept_t((SELECT ref(c) FROM Concept c WHERE c.idConcept = idConcept))
where login = '$this->login';";
$query2 = "INSERT INTO TABLE (SELECT concepts FROM Utilisateur WHERE login = '$this->login')
VALUES ((SELECT ref(c) FROM Concept c WHERE c.idConcept = idConcept));";
$pdo->query($query);
}
/**
* @action Insère un terme vedette dans la bd
* @param nomTerme Nom du terme
* @param description Description
* @param concept Concept lié au terme vedette
*/
public function creerTerme($nomTerme, $description)
{
	$pdo = ConnexionBD::getPDO();
	$query = "INSERT INTO TermeVedette (idTerme, nomTerme, description) VALUES(idTerme, '$nomTerme', '$description');";
	$pdo->query($query);
// Insertion du concept dans la table Utilisateur
	$query = "UPDATE Utilisateur
	set termes = GroupeTerme_t((SELECT ref(t) FROM TermeVedette t WHERE t.idTerme = idTerme))
	where login = '$this->login';";
	$query2 = "INSERT INTO TABLE (SELECT termes FROM Utilisateur WHERE login = '$this->login')
	VALUES ((SELECT ref(t) FROM TermeVedette t WHERE t.idTerme = idTerme));";
	$pdo->query($query);
}
/**
* @action Insère dans la bd un synonyme lié à la vedette passé en paramètre
* @param nomSynonyme Nom du synonyme
* @param vedette Terme vedette lié au synonyme
*/
public function creerSynonyme($nomSynonyme, $vedette)
{
	$pdo = ConnexionBD::getPDO();
	$query = "INSERT INTO Synonyme (idSynonyme, nomSynonyme) VALUES (idSynonyme,'$nomSynonyme');";
	$pdo->query($query);
// Insertion du synonyme dans la table Vedette
	$query = "UPDATE TermeVedette
	set synonymes = GroupeSynonyme_t((SELECT ref(s) FROM Synonyme s WHERE s.idSynonyme = idSynonyme))
	where idTerme = $vedette->getId();";
	$query2 = "INSERT INTO TABLE (SELECT synonymes FROM TermeVedette WHERE idTerme = $vedette->getId())
	VALUES ((SELECT ref(s) FROM Synonyme s WHERE s.idSynonyme = idSynonyme));";
	$pdo->query($query);
// Insertion du synonyme dans la table Utilisateur
	$query = " UPDATE Utilisateur
	set synonymes = GroupeSynonyme_t((SELECT ref(s) FROM Synonyme s WHERE s.idSynonyme = idSynonyme))
	where login = '$this->login';";
	$query2 = "INSERT INTO TABLE (SELECT Synonymes FROM Utilisateur WHERE login = '$this->login')
	VALUES ((SELECT ref(s) FROM Synonyme s WHERE s.idSynonyme = idSynonyme));";
	$pdo->query($query);
}
/**
* @action Supprime le concept de la bd
* @param concept Concept à supprimer
*/
public function supprimerConcept($concept)
{
	$pdo = ConnexionBD::getPDO();
// Suppression des références parent au concept supprimé dans les autres concepts
	$query = "DELETE FROM TABLE (SELECT parents FROM Concept) parent
	WHERE VALUE(parent) = (SELECT ref(c) FROM Concept c WHERE c.idConcept = $concept->getId());";
	$pdo->query($query);
// Suppression du conept dans la table utilisateur
	$query = "DELETE FROM TABLE (SELECT concepts FROM Utilisateur) concept
	WHERE VALUE(concept) = (SELECT ref(c) FROM Concept c WHERE c.idConcept = $concept->getId());";
	$pdo->query($query);
// Suppression du concept
	$query = "DELETE FROM Concept WHERE idConcept = $concept->getId();";
	$pdo->query($query);
// Termes associés ?
}
/**
* @action Supprime le terme de la bd
* @param terme Terme à supprimer
*/
public function supprimerTerme($terme)
{
	$pdo = ConnexionBD::getPDO();
// Suppression du conept dans la table utilisateur
	$query = "DELETE FROM TABLE (SELECT termes FROM Utilisateur) terme
	WHERE VALUE(terme) = (SELECT ref(t) FROM TermeVedette t WHERE t.idTerme = $terme->getId());";
	$pdo->query($query);
// Suppression des synonymes ou remplacement ??
// Suppression concept associé ?
	$query = "DELETE FROM TermeVedette WHERE idTerme=$terme->getId();";
	$pdo->query($query);
}
/**
* @action Supprime le synonyme de la bd
* @param synonyme Synonyme à supprimer
*/
public function supprimerSynonyme($synonyme)
{
	$pdo = ConnexionBD::getPDO();
// Suppression du synonyme dans la table utilisateur
	$query = "DELETE FROM TABLE (SELECT synonymes FROM Utilisateur) synonyme
	WHERE VALUE(synonyme) = (SELECT ref(s) FROM Synonyme s WHERE s.idSynonyme = $synonyme->getId());";
	$pdo->query($query);
// Suppression du synonyme dans la table vedette
	$query = "DELETE FROM TABLE (SELECT synonymes FROM TermeVedette) synonyme
	WHERE VALUE(synonyme) = (SELECT ref(s) FROM Synonyme s WHERE s.idSynonyme = $synonyme->getId());";
	$pdo->query($query);
// Suppression du synonyme
	$query = "DELETE FROM Synonyme WHERE idSynonyme=$synonyme->getId();";
	$pdo->query($query);
}
/**
* @action Supprime le compte courant de la bd
*/
public function supprimerCompte()
{
	$pdo = ConnexionBD::getPDO();
	$query = "DELETE FROM Utilisateur WHERE login='$this->login';";
	$pdo->query($query);
// Suppression des concepts, termes et synonymes créés ? Ou passage de l'auteur à null ?
}
}
?>