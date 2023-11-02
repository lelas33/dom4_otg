<?php
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */
require_once __DIR__  . '/../php/dom4_otg.inc.php';

define('NB_TAILLE_CONSIGNE', 96);   // Nombre de consigne par jour (1 valeur par 1/4 heure)
define("HEATING_HISTORY_FILE", "/../../data/otg_log.txt");

global $consigne_chauffage;
global $stat_regulation;
global $heating_dt;

// =================================================================================
// Fonction de capture des tables de consignes de chauffage depuis la centrale DOM2G
// =================================================================================
function get_consignes_chauffage()
{
  global $consigne_chauffage;

  $eq = eqLogic::byType('dom4_otg');
  
  // Capture des parametres depuis le module DOM2G
  // ---------------------------------------------
  // Creation d'une liaison TCP/IP avec le serveur vers la centrale DOM2G
  $socket = dom2_start_socket ( "G" );
  
  // 1) envoi du message d'interrogation à DOM2 sur les consignes mode jour
  $msg['cmd'] = MCHA_GETAJ;
  $msg['nbp'] = 0x00;
  $ack = array();
  // Envoi du message de commande
  dom2_message_send ($socket, $msg, $ack);

  // Mise en forme du résultat dans un tableau "PHP"
  for ($piece=0; $piece<NB_PIECES; $piece++) {
    $offs = $piece * (2*NB_TAILLE_CONSIGNE + 1);
    $consigne_chauffage["jour"]["enabled"][$piece] = $ack['param'][$offs++];
    for ($i=0; $i<NB_TAILLE_CONSIGNE; $i++) {
      $consigne_chauffage["jour"]["consigne"][$piece][$i] = $ack['param'][$offs++] + 256 * $ack['param'][$offs++];
    }
    $nom_piece = $eq[0]->getConfiguration("nom_piece_".($piece+1));
    $consigne_chauffage["nom_piece"][$piece] = $nom_piece;
  }

  // 2) envoi du message d'interrogation à DOM2 sur les consignes mode semaine
  for ($jour=0; $jour<7; $jour++) {
    $msg['cmd'] = MCHA_GETAH;
    $msg['nbp'] = 0x01;
    $msg['param'][0] = $jour;
    // Envoi du message de commande
    dom2_message_send ($socket, $msg, $ack);

    // Mise en forme du résultat dans un tableau "PHP"
    for ($piece=0; $piece<NB_PIECES; $piece++) {
      $offs = $piece * (2*NB_TAILLE_CONSIGNE + 1);
      $consigne_chauffage["hebdo"]["enabled"][$jour][$piece] = $ack['param'][$offs++];
      for ($i=0; $i<NB_TAILLE_CONSIGNE; $i++) {
        $consigne_chauffage["hebdo"]["consigne"][$jour][$piece][$i] = $ack['param'][$offs++] + (256 * $ack['param'][$offs++]);
      }
    }
  }

  // 3) envoi du message d'interrogation à DOM2 sur le statut complementaire
  $msg['cmd'] = MCHA_GET_STS;
  $msg['nbp'] = 0x00;
  $ack = array();
  // Envoi du message de commande
  dom2_message_send ($socket, $msg, $ack);
  
    // Mise en forme du résultat dans un tableau "PHP"
  $consigne_chauffage["status"]["vac_jour"]     = $ack['param'][0x03];
  $consigne_chauffage["status"]["vac_mois"]     = $ack['param'][0x04];
  $consigne_chauffage["status"]["vac_annee"]    = $ack['param'][0x05];
  $consigne_chauffage["status"]["vac_heure"]    = $ack['param'][0x06];
  $consigne_chauffage["status"]["vac_min"]      = $ack['param'][0x07];
  $consigne_chauffage["status"]["temp_inocc"]   = $ack['param'][0x08]/10.0;
  $consigne_chauffage["status"]["temp_antigel"] = $ack['param'][0x09]/10.0;
  $consigne_chauffage["status"]["epente_cdc"]   = $ack['param'][0x0A]/10.0;
  $consigne_chauffage["status"]["ipente_cdc"]   = $ack['param'][0x0B]/10.0;

  // Fermeture du socket TCP/IP
  dom2_end_socket ($socket) ;
  return ;
}

// =================================================================================
// Demande de rechargement de la configuration pour la centrale DOM2G
// (La centrale recharge son fichier de configuration pour le chauffage)
// =================================================================================
function reload_config_dom2g()
{
  // Creation d'une liaison TCP/IP avec le serveur vers la centrale DOM2G
  $socket = dom2_start_socket ( "G" );
  
  // 1) envoi du message : rechargement config
  $msg['cmd'] = MCHA_RLD_CONF;
  $msg['nbp'] = 0x00;
  $ack = array();
  // Envoi du message de commande
  dom2_message_send ($socket, $msg, $ack);
  
  // Fermeture du socket TCP/IP
  dom2_end_socket ($socket);
  return;
}

// =================================================================================
// Fonction de capture des tables de consignes de chauffage depuis la centrale DOM2G
// =================================================================================
function update_config_dom2g($dt)
{
  // Creation d'une liaison TCP/IP avec le serveur vers la centrale DOM2G
  $socket = dom2_start_socket ( "G" );
  
  // 1) envoi du message : rechargement config
  $msg['cmd'] = MCHA_STPARAM;
  $msg['nbp'] = 0x0A+NB_PIECES;
  $msg['param'][0] = $dt[0];
  $msg['param'][1] = $dt[1];
  $msg['param'][2] = $dt[2];
  $msg['param'][3] = $dt[3];
  $msg['param'][4] = $dt[4];
  $msg['param'][5] = $dt[5];
  $msg['param'][6] = $dt[6];
  $msg['param'][7] = $dt[7];
  $msg['param'][8] = $dt[8];
  $msg['param'][9] = 0xff;    // mise a jour de tous les parametres
  for ($piece=0; $piece<NB_PIECES; $piece++)
    $msg['param'][10+$piece] = $dt[10+$piece];
  $ack = array();
  // Envoi du message de commande
  dom2_message_send ($socket, $msg, $ack);
  
  // Fermeture du socket TCP/IP
  dom2_end_socket ($socket);
  return;
}

// =============================================================================================
// Fonction de sauvegarde des parametres de chauffage dans la centrale DOM2G
// (La centrale sauvegarde sa configuration dans son fichier de configuration pour le chauffage)
// =============================================================================================
function save_config_dom2g()
{
  // Creation d'une liaison TCP/IP avec le serveur vers la centrale DOM2G
  $socket = dom2_start_socket ( "G" );
  
  // 1) envoi du message : rechargement config
  $msg['cmd'] = MCHA_SAVE_CONF;
  $msg['nbp'] = 0x00;
  $ack = array();
  // Envoi du message de commande
  dom2_message_send ($socket, $msg, $ack);
  
  // Fermeture du socket TCP/IP
  dom2_end_socket ($socket);
  return;
}


// ===========================================================================
// Fonction de capture des statistiques de regulation depuis la centrale DOM2G
// ===========================================================================
function stat_regulation_dom2g()
{
  global $stat_regulation;

  $eq = eqLogic::byType('dom4_otg');

  // Creation d'une liaison TCP/IP avec le serveur vers la centrale DOM2G
  $socket = dom2_start_socket ( "G" );
  
  // 1) envoi du message : stat regulation
  $msg['cmd'] = MCHA_STAT_REG;
  $msg['nbp'] = 0x00;
  $ack = array();
  // Envoi du message de commande
  dom2_message_send ($socket, $msg, $ack);
  
  // Fermeture du socket TCP/IP
  dom2_end_socket ($socket);
  

  // Mise en forme des resultats dans un tableau "PHP"
  $offs = 0;
  for ($piece=0; $piece<NB_PIECES; $piece++) {
    $tmp = $ack['param'][$offs++] + 256 * $ack['param'][$offs++]; if ($tmp & 0x8000) $tmp = (($tmp & 0x7fff) - 0x8000); $stat_regulation["reg_ctemp"][$piece]   = $tmp/10.0;
    $tmp = $ack['param'][$offs++] + 256 * $ack['param'][$offs++]; if ($tmp & 0x8000) $tmp = (($tmp & 0x7fff) - 0x8000); $stat_regulation["reg_consi"][$piece]   = $tmp/10.0;
    $tmp = $ack['param'][$offs++] + 256 * $ack['param'][$offs++]; if ($tmp & 0x8000) $tmp = (($tmp & 0x7fff) - 0x8000); $stat_regulation["ect_current"][$piece] = $tmp/10.0;
    $tmp = $ack['param'][$offs++] + 256 * $ack['param'][$offs++]; if ($tmp & 0x8000) $tmp = (($tmp & 0x7fff) - 0x8000); $stat_regulation["ect_maxipos"][$piece] = $tmp/10.0;
    $tmp = $ack['param'][$offs++] + 256 * $ack['param'][$offs++]; if ($tmp & 0x8000) $tmp = (($tmp & 0x7fff) - 0x8000); $stat_regulation["ect_maxineg"][$piece] = $tmp/10.0;
    $tmp = $ack['param'][$offs++] + 256 * $ack['param'][$offs++]; if ($tmp & 0x8000) $tmp = (($tmp & 0x7fff) - 0x8000); $stat_regulation["ect_mean"][$piece]    = $tmp/10.0;
    $nom_piece = $eq[0]->getConfiguration("nom_piece_".($piece+1));
    $stat_regulation["nom_piece"][$piece] = $nom_piece;
    $stat_regulation["piece_enable"][$piece] = $ack['param'][$offs++];
    $offs++;
  }
  return;
}

// =============================================================================
// Fonction de capture de l'historique complet sur une periode de temps
// Periode = 1,2,3,4 (Aujourdhui, hier, cette semaine, la semaine derniere)
// Retour : histo tempe exterieure, histo conso ECS et chauffage
// =============================================================================
function get_heating_full_history($histo_range)
{
  // definition de la periode
  if ($histo_range == 1) {       // aujourd'hui
    $debut = date("Y-m-d", time());
    $fin   = date("Y-m-d", time() + 24*3600);
  }
  else if ($histo_range == 2) {  // hier
    $debut = date("Y-m-d", time() - 24*3600);
    $fin   = date("Y-m-d", time());
  }
  else if ($histo_range == 3) {  // Cette semaine
    $ts = time();
    $jour_sem = date("N", $ts) - 1; // de 0(lun) a 6(dim)
    $ts_deb = $ts - $jour_sem*24*3600;
    $debut = date("Y-m-d", $ts_deb);
    $fin   = date("Y-m-d", $ts_deb + 7*24*3600);
  }
  else if ($histo_range == 4) {  // la semaine derniere
    $ts = time();
    $jour_sem = date("N", $ts) - 1; // de 0(lun) a 6(dim)
    $ts_deb = $ts - (7+$jour_sem)*24*3600;
    $debut = date("Y-m-d", $ts_deb);
    $fin   = date("Y-m-d", $ts_deb + 7*24*3600);
  }
  
  log::add('dom4_otg', 'debug', 'Ajax:get_heating_full_history:debut='.$debut." / fin=".$fin);
  $heating_histo = array();

  $eqLogics = eqLogic::byType('dom4_otg');
  $eqLogic = $eqLogics[0];
  // Historique exterieure : => param "otg_27"
  $tempe_cmd  = $eqLogic->getCmd(null, 'otg_27');
  if (!is_object($tempe_cmd)) {
    log::add('dom4_otg', 'error', "Ajax:get_heating_full_history: commande de temperature de l'air extérieur non valide");
    return;
  }
  $cmdId = $tempe_cmd->getId();
  $values = array();
  $values = history::all($cmdId, $debut, $fin);
  $idx = 0;
  foreach ($values as $value) {
    $heating_histo["te_ts"][$idx] = strtotime($value->getDatetime());
    $heating_histo["te_va"][$idx] = round($value->getValue(),1);
    $idx++;
  }

  // Historique conso ECS : => param "otg_cons_ecs_a"
  $tempe_cmd  = $eqLogic->getCmd(null, 'otg_cons_ecs_a');
  if (!is_object($tempe_cmd)) {
    log::add('dom4_otg', 'error', "Ajax:get_heating_full_history: commande de conso ECS non valide");
    return;
  }
  $cmdId = $tempe_cmd->getId();
  $values = array();
  $values = history::all($cmdId, $debut, $fin);
  $idx = 0;
  foreach ($values as $value) {
    $heating_histo["ce_ts"][$idx] = strtotime($value->getDatetime());
    $heating_histo["ce_va"][$idx] = round($value->getValue(),1);
    $idx++;
  }
  // Historique conso chauffage : => param "otg_cons_ch_a"
  $tempe_cmd  = $eqLogic->getCmd(null, 'otg_cons_ch_a');
  if (!is_object($tempe_cmd)) {
    log::add('dom4_otg', 'error', "Ajax:get_heating_full_history: commande de conso chauffage non valide");
    return;
  }
  $cmdId = $tempe_cmd->getId();
  $values = array();
  $values = history::all($cmdId, $debut, $fin);
  $idx = 0;
  foreach ($values as $value) {
    $heating_histo["cc_ts"][$idx] = strtotime($value->getDatetime());
    $heating_histo["cc_va"][$idx] = round($value->getValue(),1);
    $idx++;
  }

  // Historique conso elec : => param "otg_cons_elec"
  $tempe_cmd  = $eqLogic->getCmd(null, 'otg_cons_elec');
  if (!is_object($tempe_cmd)) {
    log::add('dom4_otg', 'error', "Ajax:get_heating_full_history: commande de conso électrique non valide");
    return;
  }
  $cmdId = $tempe_cmd->getId();
  $values = array();
  $values = history::all($cmdId, $debut, $fin);
  $idx = 0;
  foreach ($values as $value) {
    $heating_histo["cel_ts"][$idx] = strtotime($value->getDatetime());
    $heating_histo["cel_va"][$idx] = round($value->getValue(),1);
    $idx++;
  }

  return($heating_histo);
}

// ===================================================================
// Fonction de lecture de l'historique de fonctionnement du chauffage
// ===================================================================
function get_heating_history($ts_start, $ts_end)
{
  global $heating_dt;
  
  // ouverture du fichier de log: Historique piscine
  $fn_heating = dirname(__FILE__).HEATING_HISTORY_FILE;
  $fheating = fopen($fn_heating, "r");

  // lecture des donnees
  $line = 0;
  $line_all = 0;  
  $heating_dt["hist"] = [];
  if ($fheating) {
    while (($buffer = fgets($fheating, 4096)) !== false) {
      // extrait les timestamps debut et fin du trajet
      $tmp=explode(",", $buffer);
      if (count($tmp) == 4) {
        list($log_ts, $log_conso_heating, $log_conso_ecs, $log_conso_elec) = $tmp;
        $log_tsi = intval($log_ts);
        // selectionne les infos selon leur date
        if (($log_tsi>=$ts_start) && ($log_tsi<$ts_end)) {
          $heating_dt["hist"][$line] = $buffer;
          $line = $line + 1;
        }
      }
      else {
        log::add('dom4_otg', 'error', 'Ajax:get_heating_history: Erreur dans le fichier otg_log.txt, à la ligne:'.$line_all);
      }
      $line_all = $line_all + 1;
    }
  }
  fclose($fheating);
  
  log::add('dom4_otg', 'debug', 'Ajax:get_heating_history:nb_lines='.$line);
  return;
}

// ===================================================================
// Fonction de calcul des statistique de consommation du chauffage
// ===================================================================
function get_heating_stat()
{
  global $heating_dt;
  // calcul des statistiques par mois
  // --------------------------------
  $heating_stat["ecs"] = [[]];
  $heating_stat["cha"] = [[]];
  $heating_stat["ele"] = [[]];
  $heating_stat["cur_month"] = [];      // stat mois en cours (gaz)
  $heating_stat["prev_month"] = [];     // stat mois precedent(gaz)
  $heating_stat["cur_month_el"] = [];   // stat mois en cours (elec)
  $heating_stat["prev_month_el"] = [];  // stat mois precedent(elec)
  for ($id=0; $id<=31; $id++) {
    $heating_stat["cur_month"][$id] = 0;
    $heating_stat["prev_month"][$id] = 0;
    $heating_stat["cur_month_el"][$id] = 0;
    $heating_stat["prev_month_el"][$id] = 0;
  }
  $cur_year   = intval(date('Y'));  // Annee courante
  $cur_month  = intval(date('n'));  // Month courant
  $prev_month = ($cur_month == 1) ? 12 : ($cur_month - 1); // Month precedent
  log::add('dom4_otg', 'debug', 'Ajax:get_heating_stat:nb_lines='.count($heating_dt["hist"]));
  for ($id=0; $id<count($heating_dt["hist"]); $id++) {
    $tmp = explode(",", $heating_dt["hist"][$id]);
    list($log_ts, $log_conso_heating, $log_conso_ecs, $log_conso_elec) = $tmp;
    $year  = intval(date('Y', $log_ts));  // Year => ex 2020
    $month = intval(date('n', $log_ts));  // Month => 1-12
    $day   = intval(date('j', $log_ts));  // Day => 1-31
    if (isset($heating_stat["ecs"][$year][$month])){
      $heating_stat["ecs"][$year][$month] += $log_conso_ecs;
    }
    else {
      $heating_stat["ecs"][$year][$month] = $log_conso_ecs;
    }
    if (isset($heating_stat["cha"][$year][$month])){
      $heating_stat["cha"][$year][$month] += $log_conso_heating;
    }
    else {
      $heating_stat["cha"][$year][$month] = $log_conso_heating;
    }
    if (isset($heating_stat["ele"][$year][$month])){
      $heating_stat["ele"][$year][$month] += $log_conso_elec;
    }
    else {
      $heating_stat["ele"][$year][$month] = $log_conso_elec;
    }
    // stat du mois en court
    if (($year == $cur_year) and ($month == $cur_month)) {
      $heating_stat["cur_month"][$day] += ($log_conso_ecs + $log_conso_heating);
      $heating_stat["cur_month_el"][$day] += $log_conso_elec;
    }
    // stat du mois en precedent
    if ((($year == $cur_year) and ($month == $prev_month)) or (($year == $cur_year-1) and ($month == 12))) {
      $heating_stat["prev_month"][$day] += ($log_conso_ecs + $log_conso_heating);
      $heating_stat["prev_month_el"][$day] += $log_conso_elec;
    }
      
  }
  // Ajoute quelques infos complémentaires pour utilisation par javascript
  $eqLogics = eqLogic::byType('dom4_otg');
  $eqLogic = $eqLogics[0];
  // Ajoute quelques parametres de configuration
  if ($eqLogic->getIsEnable()) {
    // $heating_stat["cost_kwh"]  = floatval($eqLogic->getConfiguration("cost_kwh"));    // Cout kWh
    $heating_stat["cost_kwh"]  = 0.0603 * 1.2;    // Cout kWh selon facture 07 - 10 / 2022
  }
  return($heating_stat);
}


// =====================================
// Gestion des commandes recues par AJAX
// =====================================
try {
    require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
    include_file('core', 'authentification', 'php');

    if (!isConnect('admin')) {
        throw new Exception(__('401 - Accès non autorisé', __FILE__));
    }

  /* Fonction permettant l'envoi de l'entête 'Content-Type: application/json'
    En V3 : indiquer l'argument 'true' pour contrôler le token d'accès Jeedom
    En V4 : autoriser l'exécution d'une méthode 'action' en GET en indiquant le(s) nom(s) de(s) action(s) dans un tableau en argument
  */
    ajax::init();

  // Capture des consignes de temperatures de chauffage 
  if (init('action') == 'getConsignes') {
    log::add('dom4_otg', 'info', 'get_consignes_chauffage - Ajax:');
    get_consignes_chauffage();
    $ret_json = json_encode ($consigne_chauffage);
    ajax::success($ret_json);
  }

  // Capture des consignes de temperatures de chauffage 
  else if (init('action') == 'ReloadConfig') {
    reload_config_dom2g();
    log::add('dom4_otg', 'info', 'ReloadConfig - Ajax:');
    ajax::success();
  }
  // Mise à jour vers la centrale DOM2G
  else if (init('action') == 'UpdateConfig') {
    update_config_dom2g(init('param'));
    log::add('dom4_otg', 'info', 'UpdateConfig - Ajax:');
    // log::add('dom4_otg', 'info', 'param0:'.init('param')[0]);
    // log::add('dom4_otg', 'info', 'param1:'.init('param')[1]);
    // log::add('dom4_otg', 'info', 'param2:'.init('param')[2]);
    // log::add('dom4_otg', 'info', 'param3:'.init('param')[3]);
    // log::add('dom4_otg', 'info', 'param4:'.init('param')[4]);
    // log::add('dom4_otg', 'info', 'param5:'.init('param')[5]);
    // log::add('dom4_otg', 'info', 'param6:'.init('param')[6]);
    // log::add('dom4_otg', 'info', 'param7:'.init('param')[7]);
    // log::add('dom4_otg', 'info', 'param8:'.init('param')[8]);
    ajax::success();
  }

  // Sauvegarde de la configuration sur la centrale DOM2G
  else if (init('action') == 'SaveConfig') {
    log::add('dom4_otg', 'info', 'SaveConfig - Ajax:');
    save_config_dom2g();
    ajax::success();
  }

  else if (init('action') == 'GetStatRegulation') {
    log::add('dom4_otg', 'info', 'stat_regulation_dom2g - Ajax:');
    stat_regulation_dom2g();
    $ret_json = json_encode ($stat_regulation);
    log::add('dom4_otg', 'info', 'stat_regulation_dom2g - Ajax:'.$ret_json);
    ajax::success($ret_json);
  }
  // Page Panel : Infos du moment
  else if (init('action') == 'getHeatingFullHistory') {
    $histo_range = init('range');
    log::add('dom4_otg', 'info', "Ajax:getHeatingFullHistory pour la plage:".$histo_range);
    $heating_histo = get_heating_full_history($histo_range);
    $ret_json = json_encode ($heating_histo);
    ajax::success($ret_json);
  }
  else if (init('action') == 'getHeatingStat') {
    log::add('dom4_otg', 'info', 'Ajax:getHeatingStat');
    get_heating_history(0, time());  // intervalle de l'origine des temps a maintenant
    $heating_stat = get_heating_stat();
    $ret_json = json_encode ($heating_stat);
    ajax::success($ret_json);
  }

  throw new Exception(__('Aucune méthode correspondante à', __FILE__) . ' : ' . init('action'));
  /*     * *********Catch exeption*************** */
  
}
catch (Exception $e) {
    ajax::error(displayException($e), $e->getCode());
}
