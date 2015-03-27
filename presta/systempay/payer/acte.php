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

function presta_systempay_payer_acte_dist($id_transaction,$transaction_hash, $titre=''){

	include_spip('inc/bank');
	$config = bank_config("systempay");

	$call_request = charger_fonction('request','presta/systempay/call');
	$contexte = $call_request($id_transaction,$transaction_hash,$config);
	$contexte['title'] = $titre;

	$contexte['sandbox'] = ($config['mode_test']?' ':'');

	return recuperer_fond('presta/systempay/payer/acte',$contexte);
}

?>
