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

/* * ***************************Includes********************************* */
require_once __DIR__  . '/../../../../core/php/core.inc.php';
require_once __DIR__  . '/../php/dom4_otg.inc.php';

class dom4_otg extends eqLogic {
  /*     * *************************Attributs****************************** */

  /*
  * Permet de définir les possibilités de personnalisation du widget (en cas d'utilisation de la fonction 'toHtml' par exemple)
  * Tableau multidimensionnel - exemple: array('custom' => true, 'custom::layout' => false)
  public static $_widgetPossibility = array();
  */

  /*
  * Permet de crypter/décrypter automatiquement des champs de configuration du plugin
  * Exemple : "param1" & "param2" seront cryptés mais pas "param3"
  public static $_encryptConfigKey = array('param1', 'param2');
  */

  /*     * ***********************Methode static*************************** */

  /*
  * Fonction exécutée automatiquement toutes les minutes par Jeedom
  public static function cron() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les 5 minutes par Jeedom
  public static function cron5() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les 10 minutes par Jeedom
  public static function cron10() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les 15 minutes par Jeedom
  public static function cron15() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les 30 minutes par Jeedom
  public static function cron30() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les heures par Jeedom
  public static function cronHourly() {}
  */

  /*
  * Fonction exécutée automatiquement tous les jours par Jeedom
  public static function cronDaily() {}
  */

  /*     * *********************Méthodes d'instance************************* */

  // Fonction exécutée automatiquement avant la création de l'équipement
  public function preInsert() {
  }

  // Fonction exécutée automatiquement après la création de l'équipement
  public function postInsert() {
  }

  // Fonction exécutée automatiquement avant la mise à jour de l'équipement
  public function preUpdate() {
  }

  // Fonction exécutée automatiquement après la mise à jour de l'équipement
  public function postUpdate() {
  }

  // Fonction exécutée automatiquement avant la sauvegarde (création ou mise à jour) de l'équipement
  public function preSave() {
  }

  // Fonction exécutée automatiquement après la sauvegarde (création ou mise à jour) de l'équipement
  public function postSave() {
      log::add('dom4_otg', 'info', 'postUpdate');
      
      // Création des commandes / Infos du plugin OTG
      // --------------------------------------------
      // Infos
      define('NB_ID_LABEL_INFO', 46);      
      $otg_info = array();
      $otg_info[ 0]['lid'] = '0';          $otg_info[ 0]['name'] = 'Status'                               ;  $otg_info[ 0]['type'] = 'o';  $otg_info[ 0]['unit'] = 'n'; $otg_info[ 0]['visible'] = 0;
      $otg_info[ 1]['lid'] = '1';          $otg_info[ 1]['name'] = 'Control setpoint'                     ;  $otg_info[ 1]['type'] = 'n';  $otg_info[ 1]['unit'] = 'd'; $otg_info[ 1]['visible'] = 0;
      $otg_info[ 2]['lid'] = '2';          $otg_info[ 2]['name'] = 'Master configuration'                 ;  $otg_info[ 2]['type'] = 'o';  $otg_info[ 2]['unit'] = 'n'; $otg_info[ 2]['visible'] = 0;
      $otg_info[ 3]['lid'] = '3';          $otg_info[ 3]['name'] = 'Slave configuration'                  ;  $otg_info[ 3]['type'] = 'o';  $otg_info[ 3]['unit'] = 'n'; $otg_info[ 3]['visible'] = 0;
      $otg_info[ 4]['lid'] = '5';          $otg_info[ 4]['name'] = 'Application-specific flags'           ;  $otg_info[ 4]['type'] = 'o';  $otg_info[ 4]['unit'] = 'n'; $otg_info[ 4]['visible'] = 0;
      $otg_info[ 5]['lid'] = '14';         $otg_info[ 5]['name'] = 'Maximum relative modulation level'    ;  $otg_info[ 5]['type'] = 'n';  $otg_info[ 5]['unit'] = 'p'; $otg_info[ 5]['visible'] = 0;
      $otg_info[ 6]['lid'] = '15';         $otg_info[ 6]['name'] = 'Boiler capacity and modulation limits';  $otg_info[ 6]['type'] = 'o';  $otg_info[ 6]['unit'] = 'n'; $otg_info[ 6]['visible'] = 0;
      $otg_info[ 7]['lid'] = '16';         $otg_info[ 7]['name'] = 'Room setpoint'                        ;  $otg_info[ 7]['type'] = 'n';  $otg_info[ 7]['unit'] = 'd'; $otg_info[ 7]['visible'] = 1;
      $otg_info[ 8]['lid'] = '17';         $otg_info[ 8]['name'] = 'Relative modulation level'            ;  $otg_info[ 8]['type'] = 'n';  $otg_info[ 8]['unit'] = 'p'; $otg_info[ 8]['visible'] = 0;
      $otg_info[ 9]['lid'] = '20';         $otg_info[ 9]['name'] = 'Day of week and time of day'          ;  $otg_info[ 9]['type'] = 'o';  $otg_info[ 9]['unit'] = 'n'; $otg_info[ 9]['visible'] = 0;
      $otg_info[10]['lid'] = '21';         $otg_info[10]['name'] = 'Date'                                 ;  $otg_info[10]['type'] = 'o';  $otg_info[10]['unit'] = 'n'; $otg_info[10]['visible'] = 0;
      $otg_info[11]['lid'] = '22';         $otg_info[11]['name'] = 'Year'                                 ;  $otg_info[11]['type'] = 'n';  $otg_info[11]['unit'] = 'n'; $otg_info[11]['visible'] = 0;
      $otg_info[12]['lid'] = '24';         $otg_info[12]['name'] = 'Room temperature'                     ;  $otg_info[12]['type'] = 'n';  $otg_info[12]['unit'] = 'd'; $otg_info[12]['visible'] = 0;
      $otg_info[13]['lid'] = '25';         $otg_info[13]['name'] = 'Boiler water temperature'             ;  $otg_info[13]['type'] = 'n';  $otg_info[13]['unit'] = 'd'; $otg_info[13]['visible'] = 0;
      $otg_info[14]['lid'] = '26';         $otg_info[14]['name'] = 'DHW temperature'                      ;  $otg_info[14]['type'] = 'n';  $otg_info[14]['unit'] = 'd'; $otg_info[14]['visible'] = 0;
      $otg_info[15]['lid'] = '27';         $otg_info[15]['name'] = 'Outside temperature'                  ;  $otg_info[15]['type'] = 'n';  $otg_info[15]['unit'] = 'd'; $otg_info[15]['visible'] = 1;
      $otg_info[16]['lid'] = '48';         $otg_info[16]['name'] = 'DHW setpoint boundaries'              ;  $otg_info[16]['type'] = 'o';  $otg_info[16]['unit'] = 'n'; $otg_info[16]['visible'] = 0;
      $otg_info[17]['lid'] = '49';         $otg_info[17]['name'] = 'Max CH setpoint boundaries'           ;  $otg_info[17]['type'] = 'o';  $otg_info[17]['unit'] = 'n'; $otg_info[17]['visible'] = 0;
      $otg_info[18]['lid'] = '56';         $otg_info[18]['name'] = 'DHW setpoint'                         ;  $otg_info[18]['type'] = 'n';  $otg_info[18]['unit'] = 'd'; $otg_info[18]['visible'] = 0;
      $otg_info[19]['lid'] = '57';         $otg_info[19]['name'] = 'Max CH water setpoint'                ;  $otg_info[19]['type'] = 'n';  $otg_info[19]['unit'] = 'n'; $otg_info[19]['visible'] = 0;
      $otg_info[20]['lid'] = '116';        $otg_info[20]['name'] = 'Burner starts'                        ;  $otg_info[20]['type'] = 'n';  $otg_info[20]['unit'] = 'n'; $otg_info[20]['visible'] = 0;
      $otg_info[21]['lid'] = '119';        $otg_info[21]['name'] = 'DHW burner starts'                    ;  $otg_info[21]['type'] = 'n';  $otg_info[21]['unit'] = 'n'; $otg_info[21]['visible'] = 0;
      $otg_info[22]['lid'] = '120';        $otg_info[22]['name'] = 'Burner operation hours'               ;  $otg_info[22]['type'] = 'n';  $otg_info[22]['unit'] = 'n'; $otg_info[22]['visible'] = 0;
      $otg_info[23]['lid'] = '123';        $otg_info[23]['name'] = 'DHW burner operation hours'           ;  $otg_info[23]['type'] = 'n';  $otg_info[23]['unit'] = 'n'; $otg_info[23]['visible'] = 0;
      $otg_info[24]['lid'] = '0_0';        $otg_info[24]['name'] = 'Fault indication'                     ;  $otg_info[24]['type'] = 'b';  $otg_info[24]['unit'] = 'n'; $otg_info[24]['visible'] = 1;
      $otg_info[25]['lid'] = '0_1';        $otg_info[25]['name'] = 'CH Mode'                              ;  $otg_info[25]['type'] = 'n';  $otg_info[25]['unit'] = 'n'; $otg_info[25]['visible'] = 0;
      $otg_info[26]['lid'] = '0_2';        $otg_info[26]['name'] = 'DHW Mode'                             ;  $otg_info[26]['type'] = 'n';  $otg_info[26]['unit'] = 'n'; $otg_info[26]['visible'] = 0;
      $otg_info[27]['lid'] = '0_3';        $otg_info[27]['name'] = 'Flame Status'                         ;  $otg_info[27]['type'] = 'n';  $otg_info[27]['unit'] = 'n'; $otg_info[27]['visible'] = 1;
      $otg_info[28]['lid'] = '0_4';        $otg_info[28]['name'] = 'CH Enable'                            ;  $otg_info[28]['type'] = 'n';  $otg_info[28]['unit'] = 'n'; $otg_info[28]['visible'] = 0;
      $otg_info[29]['lid'] = '0_5';        $otg_info[29]['name'] = 'DHW Enable'                           ;  $otg_info[29]['type'] = 'n';  $otg_info[29]['unit'] = 'n'; $otg_info[29]['visible'] = 0;
      $otg_info[30]['lid'] = 'cons_ch_a';  $otg_info[30]['name'] = 'Consommation chauffage jour'          ;  $otg_info[30]['type'] = 'n';  $otg_info[30]['unit'] = 'k'; $otg_info[30]['visible'] = 1;
      $otg_info[31]['lid'] = 'cons_ecs_a'; $otg_info[31]['name'] = 'Consommation ECS jour'                ;  $otg_info[31]['type'] = 'n';  $otg_info[31]['unit'] = 'k'; $otg_info[31]['visible'] = 1;
      $otg_info[32]['lid'] = 'puiss_ch';   $otg_info[32]['name'] = 'Puissance chauffage'                  ;  $otg_info[32]['type'] = 'n';  $otg_info[32]['unit'] = 'w'; $otg_info[32]['visible'] = 1;
      $otg_info[33]['lid'] = 'puiss_ecs';  $otg_info[33]['name'] = 'Puissance ECS'                        ;  $otg_info[33]['type'] = 'n';  $otg_info[33]['unit'] = 'w'; $otg_info[33]['visible'] = 1;
      $otg_info[34]['lid'] = 'modec_value';$otg_info[34]['name'] = 'Mode chauffage'                       ;  $otg_info[34]['type'] = 'n';  $otg_info[34]['unit'] = 'n'; $otg_info[34]['visible'] = 0;
      // parametres supplementaire (hors norme OT)
      $otg_info[35]['lid'] = '40';         $otg_info[35]['name'] = 'Température pièce la plus froide'     ;  $otg_info[35]['type'] = 'n';  $otg_info[35]['unit'] = 'd'; $otg_info[35]['visible'] = 0;
      $otg_info[36]['lid'] = '41';         $otg_info[36]['name'] = 'Pièce la plus froide'                 ;  $otg_info[36]['type'] = 'n';  $otg_info[36]['unit'] = 'n'; $otg_info[36]['visible'] = 0;
      $otg_info[37]['lid'] = '42';         $otg_info[37]['name'] = 'Température de départ chaudière'      ;  $otg_info[37]['type'] = 'n';  $otg_info[37]['unit'] = 'd'; $otg_info[37]['visible'] = 0;
      $otg_info[38]['lid'] = 'cconfort';   $otg_info[38]['name'] = 'Consigne générale'                    ;  $otg_info[38]['type'] = 'n';  $otg_info[38]['unit'] = 'd'; $otg_info[38]['visible'] = 1;
      $otg_info[39]['lid'] = 'modec_name'; $otg_info[39]['name'] = 'Nom Mode chauffage'                   ;  $otg_info[39]['type'] = 'o';  $otg_info[39]['unit'] = 'n'; $otg_info[39]['visible'] = 1;
      // Consignes courante par piece (hors norme OT)
      $otg_info[40]['lid'] = 'cs_room_0';  $otg_info[40]['name'] = 'Consigne0:Ch.Parents'                 ;  $otg_info[40]['type'] = 'n';  $otg_info[40]['unit'] = 'd'; $otg_info[40]['visible'] = 0;
      $otg_info[41]['lid'] = 'cs_room_1';  $otg_info[41]['name'] = 'Consigne1:Ch.Etienne'                 ;  $otg_info[41]['type'] = 'n';  $otg_info[41]['unit'] = 'd'; $otg_info[41]['visible'] = 0;
      $otg_info[42]['lid'] = 'cs_room_2';  $otg_info[42]['name'] = 'Consigne2:Ch.Baptiste'                ;  $otg_info[42]['type'] = 'n';  $otg_info[42]['unit'] = 'd'; $otg_info[42]['visible'] = 0;
      $otg_info[43]['lid'] = 'cs_room_3';  $otg_info[43]['name'] = 'Consigne3:Sejour'                     ;  $otg_info[43]['type'] = 'n';  $otg_info[43]['unit'] = 'd'; $otg_info[43]['visible'] = 0;
      $otg_info[44]['lid'] = 'cs_room_4';  $otg_info[44]['name'] = 'Consigne4:Bureau'                     ;  $otg_info[44]['type'] = 'n';  $otg_info[44]['unit'] = 'd'; $otg_info[44]['visible'] = 0;
      $otg_info[45]['lid'] = 'cs_room_5';  $otg_info[45]['name'] = 'Consigne5:Ch.invité'                  ;  $otg_info[45]['type'] = 'n';  $otg_info[45]['unit'] = 'd'; $otg_info[45]['visible'] = 0;

      // commandes
      define('NB_ID_LABEL_CMD', 8);      
      $otg_cmd = array();
        // modes chauffage
      $otg_cmd[ 0]['lid'] = 'modec_bypass';  $otg_cmd[ 0]['name'] = 'Mode thermostat origine'    ;  $otg_cmd[ 0]['type'] = 'o';
      $otg_cmd[ 1]['lid'] = 'modec_arret';   $otg_cmd[ 1]['name'] = 'Mode Arrêt complet'         ;  $otg_cmd[ 1]['type'] = 'o';
      $otg_cmd[ 2]['lid'] = 'modec_ete';     $otg_cmd[ 2]['name'] = 'Mode Eté'                   ;  $otg_cmd[ 2]['type'] = 'o';
      $otg_cmd[ 3]['lid'] = 'modec_hiverj';  $otg_cmd[ 3]['name'] = 'Mode Hiver Journalier'      ;  $otg_cmd[ 3]['type'] = 'o';
      $otg_cmd[ 4]['lid'] = 'modec_hiverh';  $otg_cmd[ 4]['name'] = 'Mode Hiver Hebdomadaire'    ;  $otg_cmd[ 4]['type'] = 'o';
      $otg_cmd[ 5]['lid'] = 'modec_hiverv';  $otg_cmd[ 5]['name'] = 'Mode Hiver Vacances'        ;  $otg_cmd[ 5]['type'] = 'o';
      $otg_cmd[ 6]['lid'] = 'set_cconfort';  $otg_cmd[ 6]['name'] = 'Réglage Consigne générale'  ;  $otg_cmd[ 6]['type'] = 's';
      $otg_cmd[ 7]['lid'] = 'modec_setmode'; $otg_cmd[ 7]['name'] = 'Choix mode chauffage'       ;  $otg_cmd[ 7]['type'] = 'm';

      // Création des infos
      for ($i=0; $i<NB_ID_LABEL_INFO; $i++) {
        $info = $this->getCmd(null, 'otg_'.$otg_info[$i]['lid']);
        if (!is_object($info)) {
          $info = new dom4_otgCmd();
          $info->setName(__($otg_info[$i]['name'], __FILE__));
        }
        $info->setLogicalId('otg_'.$otg_info[$i]['lid']);
        $info->setEqLogic_id($this->getId());
        if ($otg_info[$i]['unit'] == 'd')
          $info->setUnite('°C');
        else if ($otg_info[$i]['unit'] == 'p')
          $info->setUnite('%');
        else if ($otg_info[$i]['unit'] == 'k')
          $info->setUnite('kWh');
        else if ($otg_info[$i]['unit'] == 'w')
          $info->setUnite('W');
        else
          $info->setUnite('');
        $info->setType('info');
        if ($otg_info[$i]['type'] == 'n') {
          $info->setSubType('numeric');
          $info->setTemplate('dashboard','badge');
        }
        else if ($otg_info[$i]['type'] == 'b') 
          $info->setSubType('binary');
        else if ($otg_info[$i]['type'] == 'o') 
          $info->setSubType('string');
        $info->setIsHistorized(1);
        if ($otg_info[$i]['visible'] == 1) 
          $info->setIsVisible(1);
        else
          $info->setIsVisible(0);
        $info->save();
      }
      // Création des commandes
      for ($i=0; $i<NB_ID_LABEL_CMD; $i++) {
        $command = $this->getCmd(null, $otg_cmd[$i]['lid']);
        if (!is_object($command)) {
          $command = new dom4_otgCmd();
          $command->setName(__($otg_cmd[$i]['name'], __FILE__));
        }
        $command->setLogicalId($otg_cmd[$i]['lid']);
        $command->setType('action');
        if ($otg_cmd[$i]['type'] == 'o') 
          $command->setSubType('other');
        else if ($otg_cmd[$i]['type'] == 's') {
          $command->setSubType('slider');
          $command->setConfiguration('minValue', 0);
          $command->setConfiguration('maxValue', 30);
          $command->setConfiguration('step', 0.5);
        }
        else if ($otg_cmd[$i]['type'] == 'm') {
          $command->setSubType('message');
          $command->setIsVisible(0);
        }
        $command->setEqLogic_id($this->getId());
        $command->save();
      }
     
      // Fonction refresh data
      $refresh = $this->getCmd(null, 'refresh');
      if (!is_object($refresh)) {
        $refresh = new dom4_otgCmd();
        $refresh->setName(__('Rafraichir', __FILE__));
      }
      $refresh->setEqLogic_id($this->getId());
      $refresh->setLogicalId('refresh');
      $refresh->setType('action');
      $refresh->setSubType('other');
      $refresh->save();      
  }

  // Fonction exécutée automatiquement avant la suppression de l'équipement
  public function preRemove() {
  }

  // Fonction exécutée automatiquement après la suppression de l'équipement
  public function postRemove() {
  }
// ================================================================================
// Fonction de capture des Infos issues du module DOM2OTG (consignes chaudiere ...)
// ================================================================================
public function getInfos() {
  
  $nom_piece = array("Chambre_P", "Chambre_E", "Chambre_B", "Sejour", "Bureau", "Chambre_I");

  // Capture des parametres depuis le module DOM2G
  // ---------------------------------------------
  // Creation d'une liaison TCP/IP avec le serveur vers la centrale DOM2G
  $socket = dom2_start_socket ( "G" );
  
  // 1) envoi du message d'interrogation à DOM2G sur les info generales
  $msg['cmd'] = MCNT3_GETSTS ;
  $msg['nbp'] = 0x00 ;
  $ack = array();
  // Envoi du message de commande
  dom2_message_send ($socket, $msg, $ack);
  $current_modec = $ack['param'][0x10];
  $cconfort      = (($ack['param'][0x12] - 128) + 200)/10;  // Consigne generale centree sur 20 deg

  // 2)  envoi du message d'interrogation à DOM2G sur les parametres Opentherm
  $msg['cmd'] = MCHA_GET_OT ;
  $msg['nbp'] = 0x00 ;
  // Envoi du message de commande
  dom2_message_send ($socket, $msg, $ack);

  // Mise en forme du résultat dans un tableau "PHP"
  // pour tous les parametres OTG
  for ( $i=0; $i<128; $i++) {
    $param_ot["enabled"][$i] = $ack['param'][4*$i+0];
    $param_ot["type"][$i]    = $ack['param'][4*$i+1];
    $param_ot["value"][$i]   = $ack['param'][4*$i+3] * 256 + $ack['param'][4*$i+2];
    }

  // 3) envoi du message d'interrogation à DOM2G sur les statistiques chauffage
  $msg['cmd'] = MCHA_GET_STAT;
  $msg['nbp'] = 0x00 ;

  // Envoi du message de commande
  dom2_message_send ($socket, $msg, $ack);

  // Mise en forme du résultat dans un tableau "PHP"
  $stat_conso_chaudiere["stat_conso_chau"] = ($ack['param'][ 3] <<24) + ($ack['param'][ 2] <<16) + ($ack['param'][ 1] <<8) + $ack['param'][ 0];
  $stat_conso_chaudiere["stat_conso_dhw"]  = ($ack['param'][ 7] <<24) + ($ack['param'][ 6] <<16) + ($ack['param'][ 5] <<8) + $ack['param'][ 4];
  $stat_conso_chaudiere["puiss_chau"]      = ($ack['param'][11] <<24) + ($ack['param'][10] <<16) + ($ack['param'][ 9] <<8) + $ack['param'][ 8];
  $stat_conso_chaudiere["puiss_dhw"]       = ($ack['param'][15] <<24) + ($ack['param'][14] <<16) + ($ack['param'][13] <<8) + $ack['param'][12];

  // 4) envoi du message d'interrogation à DOM2G sur les consignes courantes par piece (stat regulation)
  $msg['cmd'] = MCHA_STAT_REG;
  $msg['nbp'] = 0x00 ;

  // Envoi du message de commande
  dom2_message_send ($socket, $msg, $ack);

  // Mise en forme du résultat dans un tableau "PHP"
  $offs = 2;
  for ($piece=0; $piece<NB_PIECES; $piece++) {
    $tmp = $ack['param'][$offs++] + 256 * $ack['param'][$offs++];
    if ($tmp & 0x8000) $tmp = (($tmp & 0x7fff) - 0x8000);
    $stat_regulation["reg_consi"][$piece] = $tmp/10.0;
    $offs+=10;
  }

  // Fermeture du socket TCP/IP
  dom2_end_socket ($socket) ;

  // recopie les parametres OT dans les objets cmd
  for ( $i=0; $i<128; $i++) {
    $cmd = $this->getCmd(null, 'otg_'.$i);
    if ((is_object($cmd)) && ($param_ot["enabled"][$i] != 0)) {
      $cmd->setCollectDate('');
      switch ($param_ot["type"][$i]) {
        case 0 : // flag8/flag8
                 $cmd->event( dechex ($param_ot["value"][$i]));
                 break;
        case 1 : // flag8/u8
                 $cmd->event( dechex ($param_ot["value"][$i]));
                 break;
        case 2 : // u8/flag8
                 $cmd->event( dechex ($param_ot["value"][$i]));
                 break;
        case 3 : // u8/u8
                 $cmd->event( dechex ($param_ot["value"][$i]));
                 break;
        case 4 : // s8/s8
                 $cmd->event( dechex ($param_ot["value"][$i]));
                 break;
        case 16 : // f8.8
                 //traitement extension de signe (uniquement sur le parametre "outside temp" qui peut etre negatif)
                 if ($i == 27) {
                   $temp = $param_ot["value"][$i];
                   if ($temp & 0x8000) $temp = (($temp & 0x7fff) - 0x8000);		// extension de signe sur 32 bits
                   $cmd->event(round($temp/256.0,2));
                 }
                 else
                   $cmd->event( round($param_ot["value"][$i]/256.0,2));
                 break;
        case 17 : // u16
                 $cmd->event( $param_ot["value"][$i]);
                 break;
        case 18 : // s16
                 $cmd->event( $param_ot["value"][$i]);
                 break;
      }
    }
  }

  // traitement particulier des Flag de status
  $cmd = $this->getCmd(null, 'otg_0_0');
  if (is_object($cmd)) { // 'Fault indication'
    $cmd->setCollectDate('');
    $cmd->event(!($param_ot["value"][0] & 0x01));
  }
  $cmd = $this->getCmd(null, 'otg_0_1');
  if (is_object($cmd)) { // 'CH Mode' 
    $cmd->setCollectDate('');
    $cmd->event((($param_ot["value"][0] & 0x02) >> 1)+0);  // offset pour pour affichage combiné en graphique
  }
  $cmd = $this->getCmd(null, 'otg_0_2');
  if (is_object($cmd)) { // 'DHW Mode'
    $cmd->setCollectDate('');
    $cmd->event((($param_ot["value"][0] & 0x04) >> 2)+4);  // offset pour pour affichage combiné en graphique
  }
  $cmd = $this->getCmd(null, 'otg_0_3');
  if (is_object($cmd)) { // 'Flame Status'  
    $cmd->setCollectDate('');
    $cmd->event((($param_ot["value"][0] & 0x08) >> 3)+8);  // offset pour pour affichage combiné en graphique
  }
  $cmd = $this->getCmd(null, 'otg_0_4');
  if (is_object($cmd)) { // 'CH Enable'  
    $cmd->setCollectDate('');
    $cmd->event((($param_ot["value"][0] & 0x100) >> 8)+2);  // offset pour pour affichage combiné en graphique
  }
  $cmd = $this->getCmd(null, 'otg_0_5');
  if (is_object($cmd)) { // 'DWH Enable'  
    $cmd->setCollectDate('');
    $cmd->event((($param_ot["value"][0] & 0x200) >> 9)+6);  // offset pour pour affichage combiné en graphique
  }

  // recopie des statistiques chauffage dans les objets cmd (memorisation en kWh => Convertion W.s => kW.h)
  $cmd = $this->getCmd(null, 'otg_cons_ch_a');
  if (is_object($cmd)) {
    $cmd->setCollectDate('');
    $cmd->event(round($stat_conso_chaudiere["stat_conso_chau"]/(1000.0*3600.0),2));
  }
  $cmd = $this->getCmd(null, 'otg_cons_ecs_a');
  if (is_object($cmd)) {
    $cmd->setCollectDate('');
    $cmd->event(round($stat_conso_chaudiere["stat_conso_dhw"]/(1000.0*3600.0),2));
  }
  
  // puissance courante Chauffage et ECS
  $cmd = $this->getCmd(null, 'otg_puiss_ch');
  if (is_object($cmd)) {
    $cmd->setCollectDate('');
    $cmd->event(round($stat_conso_chaudiere["puiss_chau"]/1000.0,2));
  }
  $cmd = $this->getCmd(null, 'otg_puiss_ecs');
  if (is_object($cmd)) {
    $cmd->setCollectDate('');
    $cmd->event(round($stat_conso_chaudiere["puiss_dhw"]/1000.0,2));
  }

  // recopie des consignes courantes dans les objets cmd
  for ($piece=0; $piece<NB_PIECES; $piece++) {
    $cmd = $this->getCmd(null, 'otg_cs_room_'.$piece);
    if (is_object($cmd)) {
      $cmd->setCollectDate('');
      $cmd->event($stat_regulation["reg_consi"][$piece]);
    }
    // Mise a jour consigne Vanne radiateur si besoin
    $exp_name = "Vanne_".$nom_piece[$piece];
    foreach (eqLogic::byType('openzwave') as $zw_device) {
      if (($zw_device->getIsEnable() == 1) && ($zw_device->getName() == $exp_name)) {
        log::add('dom4_otg', 'debug', 'getInfos:op_zwave = trouvé:'.$exp_name);
        // Search GetConsigne CMD
        $cval = 0;
        foreach ($zw_device->getCmd() as $cmd_device) {
          if (is_object($cmd_device) && ($cmd_device->getName()=="Consigne")) {
            log::add('dom4_otg', 'debug', 'getInfos:cmd = trouvé:'.$cmd_device->getName());
            // Get current value
            $cval = $cmd_device->execCmd();
            log::add('dom4_otg', 'debug', 'getInfos:current value:'.$cval);
          }
        }
        // Search SetConsigne CMD
        foreach ($zw_device->getCmd() as $cmd_device) {
          if (is_object($cmd_device) && ($cmd_device->getName()=="Commande")) {
            log::add('dom4_otg', 'debug', 'getInfos:cmd = trouvé:'.$cmd_device->getName());
            $option['slider'] = $stat_regulation["reg_consi"][$piece];
            if ($cval != $option['slider']) {
              log::add('dom4_otg', 'debug', 'getInfos:set consigne => :'.$option['slider']);
              $cmd_device->execute($option);
            }
          }
        }
      }
    }
  }

  // Prise en compte du mode chauffage courant
  $cmd = $this->getCmd(null, 'otg_modec_value');
  if (is_object($cmd)) {
    $cmd->setCollectDate('');
    $cmd->setConfiguration('value', $current_modec);
    $cmd->save();
    $cmd->event($current_modec);
  }
  // Prise en compte du nom de mode associe
  $cmd = $this->getCmd(null, 'otg_modec_name');
  if      ($current_modec == 0) $current_modec_name = "Bypass";  
  else if ($current_modec == 1) $current_modec_name = "Arret";   
  else if ($current_modec == 2) $current_modec_name = "Ete";     
  else if ($current_modec == 3) $current_modec_name = "Hiver-J"; 
  else if ($current_modec == 4) $current_modec_name = "Hiver-S"; 
  else if ($current_modec == 5) $current_modec_name = "Vacances";
  if (is_object($cmd)) {
    $cmd->setCollectDate('');
    $cmd->setConfiguration('value', $current_modec_name);
    $cmd->save();
    $cmd->event($current_modec_name);
  }
  
  // Prise en compte du parametre correction confort
  $cmd = $this->getCmd(null, 'otg_cconfort');
  if (is_object($cmd)) {
    $cmd->setCollectDate('');
    $cmd->setConfiguration('value', $cconfort);
    $cmd->save();
    $cmd->event($cconfort);
  }

  return ;
}

// ================================================================================
// Fonction d'execution des commandes de la centrale dom2g (chauffage)
// ================================================================================
//  param : 'Chauffage'
//       10: Mode Chauffage : thermostat origine
//       11: Mode Chauffage : Arret complet
//       12: Mode Chauffage : Ete
//       13: Mode Chauffage : Hiver Journalier
//       14: Mode Chauffage : Hiver Hebdomadaire
//       15: Mode Chauffage : Hiver Vacances
//       16: Mode Chauffage : mode en parametre (de 0 a 5)
//       20: Defini le parametre confort chauffage (consigne generale en entree)
public function dom2g_cmd_chau($command, $parametre) {

  log::add('dom4_otg', 'info', 'dom2g_cmd_chau:'.$command.', parametre='.$parametre);

  // Creation d'une liaison TCP/IP avec le serveur vers la centrale DOM2G
  $socket = dom2_start_socket ("G");

  // Commande de definition du mode de chauffage
  if (($command >= 10) && ($command <= 15)) {  // mode chauffage
    $msg['nbp'] = 0x02;
    $msg['cmd'] = MCHA_SETMODE;
    $msg['param'][0] = ($command - 10);
    $msg['param'][1] = 255;   // pas de modif delta_tempe
  }
  else if ($command == 16) {  // mode chauffage (avec parametre)
    $modec=1;
    if      ($parametre == "Bypass")   $modec=0;
    else if ($parametre == "Arret")    $modec=1;
    else if ($parametre == "Ete")      $modec=2;
    else if ($parametre == "Hiver-J")  $modec=3;
    else if ($parametre == "Hiver-S")  $modec=4;
    else if ($parametre == "Vacances") $modec=5;
    $msg['nbp'] = 0x02;
    $msg['cmd'] = MCHA_SETMODE;
    $msg['param'][0] = $modec;
    $msg['param'][1] = 255;   // pas de modif delta_tempe
  }
  else if ($command == 20) {  // parametre confort chauffage
    $msg['nbp'] = 0x02;
    $msg['cmd'] = MCHA_SETMODE;
    $msg['param'][0] = 255;   // pas de modif mode
    $msg['param'][1] = ($parametre-20)*10 + 128;  // converti consigne en ecart, et passe en x10
  }
  // Envoi du message de commande
  dom2_message_send ($socket, $msg, $ack);

  // Fermeture du socket TCP/IP
  dom2_end_socket ($socket);

  return ;
}

  /*
  * Permet de modifier l'affichage du widget (également utilisable par les commandes)
  public function toHtml($_version = 'dashboard') {}
  */

  /*
  * Permet de déclencher une action avant modification d'une variable de configuration du plugin
  * Exemple avec la variable "param3"
  public static function preConfig_param3( $value ) {
    // do some checks or modify on $value
    return $value;
  }
  */

  /*
  * Permet de déclencher une action après modification d'une variable de configuration du plugin
  * Exemple avec la variable "param3"
  public static function postConfig_param3($value) {
    // no return value
  }
  */

  /*     * **********************Getteur Setteur*************************** */

}

class dom4_otgCmd extends cmd {
  /*     * *************************Attributs****************************** */

  /*
  public static $_widgetPossibility = array();
  */

  /*     * ***********************Methode static*************************** */


  /*     * *********************Methode d'instance************************* */

  /*
  * Permet d'empêcher la suppression des commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
  public function dontRemoveCmd() {
    return true;
  }
  */

  // Exécution d'une commande
  public function execute($_options = array()) {

      $eqlogic = $this->getEqLogic(); //récupère l'éqlogic de la commande $this
      switch ($this->getLogicalId()) {	//vérifie le logical id de la commande 			
        case 'refresh': // Commande refresh
          log::add('dom4_otg', 'info', 'Exécution commande refresh');
          // Ne fait rien: refresh effectué à chaque commande
          break;
        // Commande chauffage
        case 'modec_bypass': // mode chauffage
          dom4_otg::dom2g_cmd_chau(10, 0);
          break;
        case 'modec_arret': // mode chauffage
          dom4_otg::dom2g_cmd_chau(11, 0);
          break;
        case 'modec_ete': // mode chauffage
          dom4_otg::dom2g_cmd_chau(12, 0);
          break;
        case 'modec_hiverj': // mode chauffage
          dom4_otg::dom2g_cmd_chau(13, 0);
          break;
        case 'modec_hiverh': // mode chauffage
          dom4_otg::dom2g_cmd_chau(14, 0);
          break;
        case 'modec_hiverv': // mode chauffage
          dom4_otg::dom2g_cmd_chau(15, 0);
          break;
        case 'modec_setmode': // mode chauffage
          dom4_otg::dom2g_cmd_chau(16, $_options['message']);
          break;
        case 'set_cconfort': // consigne de base de temperature
          dom4_otg::dom2g_cmd_chau(20, $_options['slider']);
          break;
		  }
      // Mise à jour des infos
      // ---------------------
      // Interrogation de la centrale DOM2G(OTG) sur la valeur de ces "infos", et mise à jour infos et widget
      $eqlogic->getInfos();
  }

  /*     * **********************Getteur Setteur*************************** */

}
