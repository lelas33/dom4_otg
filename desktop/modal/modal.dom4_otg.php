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

if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
$eq= eqLogic::byType('dom4_otg');

// Recupere le nom des pieces
$nom_pieces = [];
for ($i=0; $i<NB_PIECES; $i++) {
  $nom_piece = $eq[0]->getConfiguration("nom_piece_".($i+1));
  array_push($nom_pieces, $nom_piece);
}

foreach ($eq as $eqLogic) {
  /* Affiche image de la chaudiere */
  echo '<img src="plugins/dom4_otg/desktop/modal/chaudiere_vivadens_mcr_24.jpg" style="float:right;width:205px;height:321px;">';
  /* Affiche le statut general de la chaudiere */
  $cmd = $eqLogic->getCmd(null, 'otg_0');  if (is_object($cmd)) $status = hexdec($cmd->getCache('value', ''));
  echo '<font size="3" color="green"><strong style="font-size:1.5em;">Statut de la chaudière<br></strong></font>';
  $fault   = ($status & 0x001)? 'Yes':'No'; $fault_bgc = ($status & 0x001)? '#800000':'#008000'; //001
  $ch_mode = ($status & 0x002)? 'On':'Off'; $chmd_bgc  = ($status & 0x002)? '#7000F0':'#808080'; //002
  $dhw_mode= ($status & 0x004)? 'On':'Off'; $dhwmd_bgc = ($status & 0x004)? '#0020FF':'#808080'; //004
  $flame   = ($status & 0x008)? 'On':'Off'; $flame_bgc = ($status & 0x008)? '#E29822':'#808080'; //008
  $ch_en   = ($status & 0x100)? 'On':'Off'; $chen_bgc  = ($status & 0x100)? '#B030FF':'#808080'; //100
  $dhw_en  = ($status & 0x200)? 'On':'Off'; $dhwen_bgc = ($status & 0x200)? '#0080FF':'#808080'; //200
  echo '<strong style="font-size:1.2em;">';
  echo '<table width="60%" id="table_cmd">';
    echo '<tbody>';
      echo '<tr>';
        echo '<th width="60%" align="center"><p style="color:red;">Indication Erreur</p></th>';
        echo '<th width="40%" align="center" bgcolor="'.$fault_bgc.'" colspan=2>'.$fault.'</th>';
      echo '</tr>';
      echo '<tr>';
        echo '<th><p style="color:grey;">Combustion active</p></th>';
        echo '<th bgcolor="'.$flame_bgc.'" colspan=2>'.$flame.'</th>';
      echo '</tr>';
      echo '<tr>';
        echo '<th><p style="color:grey;">Mode chauffage</p></th>';
        echo '<th bgcolor="'.$chen_bgc.'">Activation: '.$ch_en;
        echo '<th bgcolor="'.$chmd_bgc.'">En cours: '.$ch_mode.'</th>';
      echo '</tr>';
      echo '<tr>';
        echo '<th><p style="color:grey;">Mode Eau chaude sanitaire</p></th>';
        echo '<th bgcolor="'.$dhwen_bgc.'">Activation: '.$dhw_en;
        echo '<th bgcolor="'.$dhwmd_bgc.'">En cours: '.$dhw_mode.'</th>';
      echo '</tr>';
    echo '</tbody>';
  echo '</table>';
  echo '</strong>';

  /* Chauffage */
  echo '<font size="3" color="green"><strong style="font-size:1.5em;"><br>Chauffage<br></strong></font>';
  $cmd = $eqLogic->getCmd(null, 'otg_42');   if (is_object($cmd)) $val1 = $cmd->getCache('value', '');  // Température de départ chaudière
  $cmd = $eqLogic->getCmd(null, 'otg_1');    if (is_object($cmd)) $val2 = $cmd->getCache('value', '');  // Control setpoint
  $cmd = $eqLogic->getCmd(null, 'otg_25');   if (is_object($cmd)) $val3 = $cmd->getCache('value', '');  // Boiler water temperature
  $cmd = $eqLogic->getCmd(null, 'otg_17');   if (is_object($cmd)) $val4 = $cmd->getCache('value', '');  // Maximum relative modulation level
  $cmd = $eqLogic->getCmd(null, 'otg_14');   if (is_object($cmd)) $val5 = $cmd->getCache('value', '');  // relative modulation level
  $cmd = $eqLogic->getCmd(null, 'otg_16');   if (is_object($cmd)) $val6 = $cmd->getCache('value', '');  // Room setpoint
  $cmd = $eqLogic->getCmd(null, 'otg_40');   if (is_object($cmd)) $val7 = $cmd->getCache('value', '');  // Température pièce la plus froide
  $cmd = $eqLogic->getCmd(null, 'otg_41');   if (is_object($cmd)) $val8 = $cmd->getCache('value', '');  // Pièce la plus froide
  $cmd = $eqLogic->getCmd(null, 'otg_27');   if (is_object($cmd)) $val9 = $cmd->getCache('value', '');  // Outside temperature

  echo '<strong style="font-size:1.2em;">';
  echo '<table width="60%" id="table_cmd">';
    echo '<tbody>';
      echo '<tr>';
        echo '<th width="60%" align="center"><p style="color:grey;">Valeur courbe de chauffe</p></th>';
        echo '<th width="40%" align="center" colspan=2><p style="color:grey;">'.$val1.' °C</p></th>';
      echo '</tr>';
      echo '<tr>';
        echo '<th align="center"><p style="color:grey;">Température de départ chaudière</p></th>';
        echo '<th align="center" colspan=2><p style="color:grey;">'.$val2.' °C</p></th>';
      echo '</tr>';
      echo '<tr>';
        echo '<th align="center"><p style="color:grey;">Température courante chaudière</p></th>';
        echo '<th align="center" colspan=2><p style="color:grey;">'.$val3.' °C</p></th>';
      echo '</tr>';
      echo '<tr>';
        echo '<th align="center"><p style="color:grey;">Niveau de modulation</p></th>';
        echo '<th align="center"><p style="color:grey;"> Courant: '.$val4.' %</p></th>';
        echo '<th align="center"><p style="color:grey;"> Maximum: '.$val5.' %</p></th>';
      echo '</tr>';
      echo '<tr>';
        echo '<th align="center"><p style="color:grey;">Température de consigne</p></th>';
        echo '<th align="center" colspan=2><p style="color:grey;">'.$val6.' °C</p></th>';
      echo '</tr>';
      echo '<tr>';
        echo '<th align="center"><p style="color:grey;">Pièce la plus froide</p></th>';
        echo '<th align="center"><p style="color:grey;">'.$val7.' °C</p></th>';
        echo '<th align="center"><p style="color:grey;">'.$nom_pieces[$val8].'</p></th>';
      echo '</tr>';
      echo '<tr>';
        echo '<th align="center"><p style="color:grey;">Température extérieure</p></th>';
        echo '<th align="center" colspan=2><p style="color:grey;">'.$val9.' °C</p></th>';
      echo '</tr>';
    echo '</tbody>';
  echo '</table>';
  echo '</strong>';

  /* Generation ECS */
  echo '<font size="3" color="green"><strong style="font-size:1.5em;"><br>Eau chaude sanitaire<br></strong></font>';
  $cmd = $eqLogic->getCmd(null, 'otg_26');  if (is_object($cmd)) $val1 = $cmd->getCache('value', '');
  $cmd = $eqLogic->getCmd(null, 'otg_56');  if (is_object($cmd)) $val2 = $cmd->getCache('value', '');
  echo '<strong style="font-size:1.2em;">';
  echo '<table width="60%" id="table_cmd">';
    echo '<tbody>';
      echo '<tr>';
        echo '<th width="60%" align="center"><p style="color:grey;">Température courante ECS</p></th>';
        echo '<th width="40%" align="center" colspan=2><p style="color:grey;">'.$val1.' °C</p></th>';
      echo '</tr>';
      echo '<tr>';
        echo '<th align="center"><p style="color:grey;">Consigne de tempéraure ECS</p></th>';
        echo '<th align="center" colspan=2><p style="color:grey;">'.$val2.' °C</p></th>';
      echo '</tr>';
    echo '</tbody>';
  echo '</table>';
  echo '</strong>';

  /* Consommation de la journee */
  echo '<font size="3" color="green"><strong style="font-size:1.5em;"><br>Consommation de la journée<br></strong></font>';
  $cmd = $eqLogic->getCmd(null, 'otg_cons_ch_a');   if (is_object($cmd)) $val1 = $cmd->getCache('value', '');
  $cmd = $eqLogic->getCmd(null, 'otg_cons_ecs_a');  if (is_object($cmd)) $val2 = $cmd->getCache('value', '');
  $cmd = $eqLogic->getCmd(null, 'otg_puiss_ch');    if (is_object($cmd)) $val3 = $cmd->getCache('value', '');
  $cmd = $eqLogic->getCmd(null, 'otg_puiss_ecs');   if (is_object($cmd)) $val4 = $cmd->getCache('value', '');
  echo '<strong style="font-size:1.2em;">';
  echo '<table width="60%" id="table_cmd">';
    echo '<tbody>';
      echo '<tr>';
        echo '<th width="60%" align="center"><p style="color:grey;">Consommation en chauffage du jour</p></th>';
        echo '<th width="40%" align="center"><p style="color:grey;">'.$val1.' kWh</p></th>';
      echo '</tr>';
      echo '<tr>';
        echo '<th align="center"><p style="color:grey;">Consommation en ECS du jour</p></th>';
        echo '<th align="center"><p style="color:grey;">'.$val2.' kWh</p></th>';
      echo '</tr>';
      echo '<tr>';
        echo '<th align="center"><p style="color:grey;">Puissance courante du chauffage</p></th>';
        echo '<th align="center"><p style="color:grey;">'.$val3.' kW</p></th>';
      echo '</tr>';
      echo '<tr>';
        echo '<th align="center"><p style="color:grey;">Puissance courante ECS</p></th>';
        echo '<th align="center"><p style="color:grey;">'.$val4.' kW</p></th>';
      echo '</tr>';
    echo '</tbody>';
  echo '</table>';
  echo '</strong>';
  
  /* Affiche les parametres d'entretien de la chaudiere */
  echo '<font size="3" color="green"><strong style="font-size:1.5em;"><br>Entretien de la chaudière<br></strong></font>';
  $cmd = $eqLogic->getCmd(null, 'otg_116');  if (is_object($cmd)) $val1 = $cmd->getCache('value', '');
  $cmd = $eqLogic->getCmd(null, 'otg_119');  if (is_object($cmd)) $val2 = $cmd->getCache('value', '');
  $cmd = $eqLogic->getCmd(null, 'otg_120');  if (is_object($cmd)) $val3 = $cmd->getCache('value', '');
  $cmd = $eqLogic->getCmd(null, 'otg_123');  if (is_object($cmd)) $val4 = $cmd->getCache('value', '');
  echo '<strong style="font-size:1.2em;">';
  echo '<table width="60%" id="table_cmd">';
    echo '<tbody>';
      echo '<tr>';
        echo '<th width="60%" align="center"><p style="color:grey;">Nombre de démarrage du bruleur</p></th>';
        echo '<th width="40%" align="center"><p style="color:grey;">'.$val1.'</p></th>';
      echo '</tr>';
      echo '<tr>';
        echo '<th align="center"><p style="color:grey;">Nombre de démarrage du bruleur (ECS)</p></th>';
        echo '<th align="center"><p style="color:grey;">'.$val2.'</p></th>';
      echo '</tr>';
      echo '<tr>';
        echo '<th align="center"><p style="color:grey;">Nombre heure de fonctionnement du bruleur</p></th>';
        echo '<th align="center"><p style="color:grey;">'.$val3.' h</p></th>';
      echo '</tr>';
      echo '<tr>';
        echo '<th align="center"><p style="color:grey;">Nombre heure de fonctionnement du bruleur (ECS)</p></th>';
        echo '<th align="center"><p style="color:grey;">'.$val4.' h</p></th>';
      echo '</tr>';
    echo '</tbody>';
  echo '</table>';
  echo '</strong>';
  /*
  foreach ($eqLogic->getCmd() as $cmd) {
    echo $cmd->getName().' / '.$cmd->getLogicalId().' = '.$cmd->getCache('value', '').'<br>';
  } */
}

?>
