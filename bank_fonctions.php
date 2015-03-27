<?php
/*
 * Paiement Bancaire
 * module de paiement bancaire multi prestataires
 * stockage des transactions
 *
 * Auteurs :
 * Cedric Morin, Nursit.com
 * (c) 2012-2015 - Distribue sous licence GNU/GPL
 *
 */

if (!defined('_ECRIRE_INC_VERSION')) return;

/**
 * #PAYER_ACTE{sips,#ID_TRANSACTION,#HASH}
 * 4e argument optionnel : tableau d'options #ARRAY{title,'Payez!'}
 *
 * @param Object $p
 * @return Object
 */
function balise_PAYER_ACTE_dist($p){
	$_mode = interprete_argument_balise(1,$p);
	$_id = interprete_argument_balise(2,$p);
	$_hash = interprete_argument_balise(3,$p);
	$_opts = interprete_argument_balise(4,$p);

	$p->code = "";

	if ($_mode AND $_id AND $_hash)
		$p->code = "bank_affiche_payer($_mode,'acte',$_id,$_hash,$_opts)";

	$p->interdire_scripts = false;
	return $p;
}


/**
 * #PAYER_ABONNEMENT{sips,#ID_TRANSACTION,#HASH}
 * 4e argument optionnel : tableau d'options #ARRAY{title,'Payez!'}
 *
 * @param Object $p
 * @return Object
 */
function balise_PAYER_ABONNEMENT_dist($p){
	$_mode = interprete_argument_balise(1,$p);
	$_id = interprete_argument_balise(2,$p);
	$_hash = interprete_argument_balise(3,$p);
	$_opts = interprete_argument_balise(4,$p);

	$p->code = "";

	if ($_mode AND $_id AND $_hash)
		$p->code = "bank_affiche_payer($_mode,'abo',$_id,$_hash,$_opts)";

	$p->interdire_scripts = false;
	return $p;
}

/**
 * Une fonction pour expliciter le mode de paiement en fonction du prestataire bancaire
 * par defaut c'est Carte Bancaire
 * sauf si une chaine de langue specifique existe
 * @param $presta
 * @return mixed|string
 */
function bank_titre_type_paiement($presta){
	$titre = _T("bank:label_type_paiement_$presta",array('presta'=>$presta),array('force'=>false));
	if (!$titre)
		$titre = _T("bank:label_type_paiement_cb_generique",array('presta'=>$presta));
	return $titre;
}

/**
 * "Payer par Carte Bleue" ou autre nom de carte en clair en fonction du $code_carte interne a la banque
 * @param string $code_carte
 * @return string
 */
function bank_label_payer_par_carte($code_carte){
	$carte = _T('bank:label_carte_'.$code_carte,array(),array('force'=>false));
	if (!$carte){
		#var_dump($code_carte);
		$carte = $code_carte;
	}
	return _T('bank:payer_par_carte',array('carte'=>$carte));
}


/**
 * Afficher la liste des transactions d'un auteur sur la page auteur de l'espace prive
 *
 * @pipeline affiche_auteurs_interventions
 * @param  array $flux Donn�es du pipeline
 * @return array       Donn�es du pipeline
 */
function bank_affiche_auteurs_interventions($flux) {
	if ($id_auteur = intval($flux['args']['id_auteur'])) {

		$flux['data'] .= recuperer_fond('prive/objets/liste/transactions', array(
			'id_auteur' => $id_auteur,
		), array('ajax' => true));

	}
	return $flux;
}

/**
 * Declarer les CRON
 *
 * @param array $taches_generales
 * @return array
 */
function bank_taches_generales_cron($taches_generales){
	$c = unserialize($GLOBALS['meta']['bank_paiement']);
	if (isset($c['email_reporting']) AND strlen($email = $c['email_reporting'])){
		$taches_generales['bank_daily_reporting'] = 3600*6; // toutes les 6H
	}
	return $taches_generales;
}
