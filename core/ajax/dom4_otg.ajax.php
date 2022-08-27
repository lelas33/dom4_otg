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

global $consigne_chauffage;
global $stat_regulation;
// =================================================================================
// Fonction de capture des tables de consignes de chauffage depuis la centrale DOM2G
// =================================================================================
function get_consignes_chauffage()
{
  global $consigne_chauffage;

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
  $msg['cmd'] = MCNT3_GETSTS;
  $msg['nbp'] = 0x00;
  $ack = array();
  // Envoi du message de commande
  dom2_message_send ($socket, $msg, $ack);
  
    // Mise en forme du résultat dans un tableau "PHP"
  $consigne_chauffage["status"]["vac_jour"]     = $ack['param'][0x13];
  $consigne_chauffage["status"]["vac_mois"]     = $ack['param'][0x14];
  $consigne_chauffage["status"]["vac_annee"]    = $ack['param'][0x15];
  $consigne_chauffage["status"]["vac_heure"]    = $ack['param'][0x16];
  $consigne_chauffage["status"]["vac_min"]      = $ack['param'][0x17];
  $consigne_chauffage["status"]["temp_inocc"]   = $ack['param'][0x18]/10.0;
  $consigne_chauffage["status"]["temp_antigel"] = $ack['param'][0x19]/10.0;
  $consigne_chauffage["status"]["epente_cdc"]   = $ack['param'][0x1A]/10.0;
  $consigne_chauffage["status"]["ipente_cdc"]   = $ack['param'][0x1B]/10.0;

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
  }
  return;
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
    get_consignes_chauffage();
    log::add('dom2otgv4', 'info', 'get_consignes_chauffage - Ajax:');
    $ret_json = json_encode ($consigne_chauffage);
    ajax::success($ret_json);
  }

  // Capture des consignes de temperatures de chauffage 
  else if (init('action') == 'ReloadConfig') {
    reload_config_dom2g();
    log::add('dom2otgv4', 'info', 'ReloadConfig - Ajax:');
    ajax::success();
  }
  // Mise à jour vers la centrale DOM2G
  else if (init('action') == 'UpdateConfig') {
    update_config_dom2g(init('param'));
    log::add('dom2otgv4', 'info', 'UpdateConfig - Ajax:');
    // log::add('dom2otgv4', 'info', 'param0:'.init('param')[0]);
    // log::add('dom2otgv4', 'info', 'param1:'.init('param')[1]);
    // log::add('dom2otgv4', 'info', 'param2:'.init('param')[2]);
    // log::add('dom2otgv4', 'info', 'param3:'.init('param')[3]);
    // log::add('dom2otgv4', 'info', 'param4:'.init('param')[4]);
    // log::add('dom2otgv4', 'info', 'param5:'.init('param')[5]);
    // log::add('dom2otgv4', 'info', 'param6:'.init('param')[6]);
    // log::add('dom2otgv4', 'info', 'param7:'.init('param')[7]);
    // log::add('dom2otgv4', 'info', 'param8:'.init('param')[8]);
    ajax::success();
  }

  // Sauvegarde de la configuration sur la centrale DOM2G
  else if (init('action') == 'SaveConfig') {
    log::add('dom2otgv4', 'info', 'SaveConfig - Ajax:');
    save_config_dom2g();
    ajax::success();
  }

  else if (init('action') == 'GetStatRegulation') {
    log::add('dom2otgv4', 'info', 'stat_regulation_dom2g - Ajax:');
    stat_regulation_dom2g();
    $ret_json = json_encode ($stat_regulation);
    log::add('dom2otgv4', 'info', 'stat_regulation_dom2g - Ajax:'.$ret_json);
    ajax::success($ret_json);
  }

  throw new Exception(__('Aucune méthode correspondante à', __FILE__) . ' : ' . init('action'));
  /*     * *********Catch exeption*************** */
  
}
catch (Exception $e) {
    ajax::error(displayException($e), $e->getCode());
}
