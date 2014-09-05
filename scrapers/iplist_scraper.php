#!/usr/bin/env php
<?php
/**
 *
 * Enter description here ...
 * @var unknown_type
 */
$url_iplist="http://www.iplists.com/nw/";
$ce_repertoire = dirname(__FILE__);
$fichier_resultat = $ce_repertoire."/IPMoteurs";
$aujourdhui = date('Ymd');

error_reporting(E_ALL );

/*
 * Récupération de la page principale :
 */
$ch = curl_init();
// set url
curl_setopt($ch, CURLOPT_URL, $url_iplist);
//return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// $output contains the output string
$output = curl_exec($ch);
// close curl resource to free up system resources
curl_close($ch);

/*
 * Analyse du flux :
 */
$r = preg_match_all('|<a[^>]+href="?(\w+.txt)"?|',$output,
    $out, PREG_PATTERN_ORDER);
$fw = fopen ($fichier_resultat,'w'); $prem=true;
foreach ($out[1] as $page){
	if ($page=="non_engines.txt") continue;
	$requete = $url_iplist.$page;
    $ch = curl_init();
        // set url
    curl_setopt($ch, CURLOPT_URL, $requete);
        //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
    $contenu_page = curl_exec($ch);

        // close curl resource to free up system resources
    curl_close($ch);
    $r = preg_match_all('|\n\d+\.\d+\.\d+(\.\d+)?|',$contenu_page,$lesIP,PREG_PATTERN_ORDER);
//    if ($prem) $page = "\n".$page;    $r1 = fwrite($fw,$page);
    foreach ($lesIP[0] as $uneIP){
    		if ($prem) {$uneIP=trim($uneIP);$prem=false;}
        	$r1 = fwrite($fw, $uneIP);
    }

}


exit ();
?>