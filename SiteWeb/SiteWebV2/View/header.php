<!--

	/**
	*
	* @author Lopez jimmy
	*
	*/

-->

<header id="headerPokesaurus">
		<a href="./index.php?page=board"><img id="logo" src="./View/logoBDD.png" alt="logo Pokesaurus"/></a>
			<ul id="menu">
				<li><a href="./index.php?page=board">Accueil</a></li>
				<li><a href="./index.php?page=listeConcepts">Concepts</a>
					<ul class="sous_menu">
						<li><a href="./index.php?page=ajoutConcept">Ajouter concept</a></li>
						<li><a href="./index.php?page=listeConcepts">Liste des concepts</a></li>
						<li><a href="./index.php?page=chercherConcept">Chercher un concept</a></li>
						<li><a href="./index.php?page=ajouterFils">Ajouter un fils</a></li>
					</ul>
				</li>
				<li><a href="./index.php?page=listeTermes">Termes</a>
					<ul class="sous_menu">
					
						<!--<li><a href="./index.php?page=trouverTerme">Chercher un terme </a></li>-->
						<li><a href="./index.php?page=listeTermes">Liste des termes</a></li>
						<li><a href="./index.php?page=ajoutSynonyme">Ajouter un synonyme</a></li>
					</ul>
				</li>
				<li><a href="./index.php?page=arbre">Arbre</a></li>
			</ul>
			
			<ul id="mini_menu">
				<li><p>Compte : <?php echo($_SESSION['login']); ?></p></li>
 				<li><a href="./index.php?page=deconnexion">Déconnexion</a></li>
			</ul>

</header>
<body>
<div class="body">
    	
    	
