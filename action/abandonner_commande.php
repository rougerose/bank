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

function action_abandonner_commande_dist($id_transaction=null){

	if (is_null($id_transaction)){
		$securiser_action = charger_fonction('securiser_action','inc');
		$id_transaction = $securiser_action();
	}

	if ($id_transaction=intval($id_transaction)
		AND $row = sql_fetsel("*","spip_transactions","id_transaction=".intval($id_transaction))
	  AND $row["statut"]=="commande"){

		sql_updateq('spip_transactions',array('statut'=>'abandon'),"id_transaction=".intval($id_transaction));

		if ($row['id_panier']
		  AND $abandonner_panier = charger_fonction('abandonner_panier','action',true))
			$abandonner_panier($row['id_panier']);
	}
}