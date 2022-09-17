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
  */
  public static function cron() {
      // Mise à jour des infos
      // ---------------------
      log::add('dom4_otg', 'info', 'cron:Mise à jour données');
      foreach (eqLogic::byType('dom4_otg') as $dom4otg) {
        if ($dom4otg->getIsEnable() == 1) {
          $dom4otg->getInfos();
        }
      }
    
  }

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

    private function getListeDefaultCommandes()
    {
      return array(
        //                         name                                     type     subtype,     unit  hist visible  generic_type    template_dashboard  template_mobile
        // Infos : parametres norme OT
        "otg_0"           => array('Status'                               , 'info',  'string',    "",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_1"           => array('Control setpoint'                     , 'info',  'numeric', "°C",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_2"           => array('Master configuration'                 , 'info',  'string',    "",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_3"           => array('Slave configuration'                  , 'info',  'string',    "",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_5"           => array('Application-specific flags'           , 'info',  'string',    "",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_14"          => array('Maximum relative modulation level'    , 'info',  'numeric',  "%",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_15"          => array('Boiler capacity and modulation limits', 'info',  'string',    "",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_16"          => array('Room setpoint'                        , 'info',  'numeric', "°C",   1,   1,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_17"          => array('Relative modulation level'            , 'info',  'numeric',  "%",   1,   1,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_20"          => array('Day of week and time of day'          , 'info',  'string',    "",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_21"          => array('Date'                                 , 'info',  'string',    "",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_22"          => array('Year'                                 , 'info',  'numeric',   "",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_24"          => array('Room temperature'                     , 'info',  'numeric', "°C",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_25"          => array('Boiler water temperature'             , 'info',  'numeric', "°C",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_26 "         => array('DHW temperature'                      , 'info',  'numeric', "°C",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_27 "         => array('Outside temperature'                  , 'info',  'numeric', "°C",   1,   1,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_48 "         => array('DHW setpoint boundaries'              , 'info',  'string',   "",    1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_49 "         => array('Max CH setpoint boundaries'           , 'info',  'string',   "",    1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_56 "         => array('DHW setpoint'                         , 'info',  'numeric', "°C",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_57 "         => array('Max CH water setpoint'                , 'info',  'numeric', "°C",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_116"         => array('Burner starts'                        , 'info',  'numeric',   "",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_119"         => array('DHW burner starts'                    , 'info',  'numeric',   "",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_120"         => array('Burner operation hours'               , 'info',  'numeric',   "",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_123"         => array('DHW burner operation hours'           , 'info',  'numeric',   "",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_0_0"         => array('Fault indication'                     , 'info',  'binary',    "",   1,   1,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_0_1"         => array('CH Mode'                              , 'info',  'numeric',   "",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_0_2"         => array('DHW Mode'                             , 'info',  'numeric',   "",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_0_3"         => array('Flame Status'                         , 'info',  'numeric',   "",   1,   1,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_0_4"         => array('CH Enable'                            , 'info',  'numeric',   "",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_0_5"         => array('DHW Enable'                           , 'info',  'numeric',   "",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_cons_ch_a"   => array('Consommation chauffage jour'          , 'info',  'numeric',"kWh",   1,   1,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_cons_ecs_a"  => array('Consommation ECS jour'                , 'info',  'numeric',"kWh",   1,   1,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_puiss_ch"    => array('Puissance chauffage'                  , 'info',  'numeric',  "W",   1,   1,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_puiss_ecs"   => array('Puissance ECS'                        , 'info',  'numeric',  "W",   1,   1,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_modec_value" => array('Mode chauffage'                       , 'info',  'numeric',   "",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),

        // Infos : parametres supplementaire (hors norme OT)
        "otg_40"          => array('Température pièce la plus froide'     , 'info',  'numeric', "°C",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_41"          => array('Pièce la plus froide'                 , 'info',  'numeric',   "",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_42"          => array('Température de départ chaudière'      , 'info',  'numeric', "°C",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_cconfort"    => array('Consigne générale'                    , 'info',  'numeric', "°C",   1,   1,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_modec_name"  => array('Nom Mode chauffage'                   , 'info',  'string',    "",   1,   1,       "GENERIC_INFO", 'core::badge',      'core::badge'),

        // Infos : Consignes courante par piece
        "otg_cs_room_1"   => array('Consigne pièce 1'                     , 'info',  'numeric', "°C",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_cs_room_2"   => array('Consigne pièce 2'                     , 'info',  'numeric', "°C",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_cs_room_3"   => array('Consigne pièce 3'                     , 'info',  'numeric', "°C",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_cs_room_4"   => array('Consigne pièce 4'                     , 'info',  'numeric', "°C",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_cs_room_5"   => array('Consigne pièce 5'                     , 'info',  'numeric', "°C",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_cs_room_6"   => array('Consigne pièce 6'                     , 'info',  'numeric', "°C",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_cs_room_7"   => array('Consigne pièce 7'                     , 'info',  'numeric', "°C",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        "otg_cs_room_8"   => array('Consigne pièce 8'                     , 'info',  'numeric', "°C",   1,   0,       "GENERIC_INFO", 'core::badge',      'core::badge'),
        
        //                          name                                    type      subtype,    unit  hist visible  generic_type      template_dashboard  template_mobile
        // Commandes : modes chauffage                                    
        "modec_bypass"    => array('Thermostat origine'                   , 'action', 'other',    "",   0,   1,       "GENERIC_ACTION", 'default',          'default'),
        "modec_arret"     => array('Arrêt complet'                        , 'action', 'other',    "",   0,   1,       "GENERIC_ACTION", 'default',          'default'),
        "modec_ete"       => array('Eté'                                  , 'action', 'other',    "",   0,   1,       "GENERIC_ACTION", 'default',          'default'),
        "modec_hiverj"    => array('Hiver Journalier'                     , 'action', 'other',    "",   0,   1,       "GENERIC_ACTION", 'default',          'default'),
        "modec_hiverh"    => array('Hiver Hebdomadaire'                   , 'action', 'other',    "",   0,   1,       "GENERIC_ACTION", 'default',          'default'),
        "modec_hiverv"    => array('Hiver Vacances'                       , 'action', 'other',    "",   0,   1,       "GENERIC_ACTION", 'default',          'default'),
        // Commandes : Autres                                             
        "set_cconfort"    => array('Réglage Consigne générale'            , 'action', 'slider',   "",   0,   1,       "GENERIC_ACTION", 'default',          'default'),
        "modec_setmode"   => array('Choix mode chauffage'                 , 'action', 'message',  "",   0,   0,       "GENERIC_ACTION", 'default',          'default') 
      );
    }



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
      // creation de la liste des commandes / infos
      // ------------------------------------------
      foreach( $this->getListeDefaultCommandes() as $id => $data) {
        list($name, $type, $subtype, $unit, $hist, $visible, $generic_type, $template_dashboard, $template_mobile) = $data;
        $cmd = $this->getCmd(null, $id);
        if (! is_object($cmd)) {
          // New CMD
          $cmd = new dom4_otgCmd();
          $cmd->setName($name);
          $cmd->setEqLogic_id($this->getId());
          $cmd->setType($type);
          if ($type == "info") {
            $cmd->setDisplay ("showStatsOndashboard",0);
            $cmd->setDisplay ("showStatsOnmobile",0);
          }
          $cmd->setSubType($subtype);
          if ($id == "set_cconfort") {
            $cmd->setConfiguration('minValue', 10);
            $cmd->setConfiguration('maxValue', 30);
            $cmd->setConfiguration('step', 0.5);
          }
          $cmd->setUnite($unit);
          $cmd->setLogicalId($id);
          $cmd->setIsHistorized($hist);
          $cmd->setIsVisible($visible);
          $cmd->setDisplay('generic_type', $generic_type);
          $cmd->setTemplate('dashboard', $template_dashboard);
          $cmd->setTemplate('mobile', $template_mobile);
          $cmd->save();
        }
        else {
          // Upadate CMD
          $cmd->setType($type);
          if ($type == "info") {
            $cmd->setDisplay ("showStatsOndashboard",0);
            $cmd->setDisplay ("showStatsOnmobile",0);
          }
          $cmd->setSubType($subtype);
          if ($id == "set_cconfort") {
            $cmd->setConfiguration('minValue', 10);
            $cmd->setConfiguration('maxValue', 30);
            $cmd->setConfiguration('step', 0.5);
          }
          $cmd->setUnite($unit);
          // $cmd->setIsHistorized($hist);
          // $cmd->setIsVisible($visible);
          $cmd->setDisplay('generic_type', $generic_type);
          $cmd->setTemplate('dashboard', $template_dashboard);
          $cmd->setTemplate('mobile', $template_mobile);
        }
      }

      // couplage des commandes et infos : "set_cconfort" et "otg_cconfort"
      $cmd_act = $this->getCmd(null, 'set_cconfort');
      $cmd_inf = $this->getCmd(null, 'otg_cconfort');
      if ((is_object($cmd_act)) and (is_object($cmd_inf))) {
        $cmd_act->setValue($cmd_inf->getid());
        $cmd_act->save();
      }
      
      // ajout de la commande refresh data
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
      log::add('dom4_otg','debug','postSave:Ajout ou Mise des commandes et infos');
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
  $msg['cmd'] = MCHA_GET_STS ;
  $msg['nbp'] = 0x00 ;
  $ack = array();
  // Envoi du message de commande
  dom2_message_send ($socket, $msg, $ack);
  $current_modec = $ack['param'][0x0];
  $cconfort      = (($ack['param'][0x2] - 128) + 200)/10;  // Consigne generale centree sur 20 deg
  log::add('dom4_otg','debug','getInfos:current_modec='.$current_modec.' / cconfort='.$cconfort);

  // 2) envoi du message de definition de temperature courante par piece
  $msg['cmd'] = MCHA_PUSH_TEMPE ;
  $msg['nbp'] = NB_TCAP*2 ;
  $buf_tempe = '( ';
  for ($i=0; $i<NB_TCAP; $i++) {
    $tempe = TEMP_INVALIDE;
    // Recuperation de la commande d'acces au capteur de temperature d'apres la page de configuration de l'equipement
    $temp_cmd_id = str_replace('#', '', $this->getConfiguration("tempe_piece_".($i+1)));
    // log::add('dom4_otg','debug','getInfos:temp_cmd('.$i.') = '.$temp_cmd_id);
    $temp_cmd = cmd::byId($temp_cmd_id);
    // Interrogation du capteur sur sa temperature courante
    if (($temp_cmd_id != "") and (is_object($temp_cmd))) {
      $tempe = intval($temp_cmd->execCmd()*10+0.5);
    }
    $buf_tempe = $buf_tempe. $tempe.', ';
    $msg['param'][2*$i  ] = ($tempe     & 0xff);
    $msg['param'][2*$i+1] = ($tempe >>8)& 0xff;
  }
  $buf_tempe = $buf_tempe.')';
  log::add('dom4_otg','debug','getInfos:tempe('.NB_TCAP.') = '.$buf_tempe);

  $ack = array();
  // Envoi du message de commande
  dom2_message_send ($socket, $msg, $ack);

  // 3)  envoi du message d'interrogation à DOM2G sur les parametres Opentherm
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

  // 4) envoi du message d'interrogation à DOM2G sur les statistiques chauffage
  $msg['cmd'] = MCHA_GET_STAT;
  $msg['nbp'] = 0x00 ;

  // Envoi du message de commande
  dom2_message_send ($socket, $msg, $ack);

  // Mise en forme du résultat dans un tableau "PHP"
  $stat_conso_chaudiere["stat_conso_chau"] = ($ack['param'][ 3] <<24) + ($ack['param'][ 2] <<16) + ($ack['param'][ 1] <<8) + $ack['param'][ 0];
  $stat_conso_chaudiere["stat_conso_dhw"]  = ($ack['param'][ 7] <<24) + ($ack['param'][ 6] <<16) + ($ack['param'][ 5] <<8) + $ack['param'][ 4];
  $stat_conso_chaudiere["puiss_chau"]      = ($ack['param'][11] <<24) + ($ack['param'][10] <<16) + ($ack['param'][ 9] <<8) + $ack['param'][ 8];
  $stat_conso_chaudiere["puiss_dhw"]       = ($ack['param'][15] <<24) + ($ack['param'][14] <<16) + ($ack['param'][13] <<8) + $ack['param'][12];

  // 5) envoi du message d'interrogation à DOM2G sur les consignes courantes par piece (stat regulation)
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
    $offs+=14;
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
    /*
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
    */
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
