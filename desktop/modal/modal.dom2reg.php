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
require_once dirname(__FILE__) . '/../../core/php/dom4_otg.inc.php';
$dir_jquery = dirname(__FILE__) . '/../../../../3rdparty/jquery/jquery.min.js';

if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
$eq= eqLogic::byType('dom4_otg');

/* Statistiques regulation */
echo '<div>';
  echo "<div id='div_statregul'>";
  echo '</div>';
echo '</div>';

  
/* Chauffage */
foreach ($eq as $eqLogic) {
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
  $nom_piece = array("Chambre Parents", "Chambre Etienne", "Chambre Baptiste", "Cuisine / Séjour", "Bureau", "Reserve");

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
        echo '<th align="center"><p style="color:grey;">'.$nom_piece[$val8].'</p></th>';
      echo '</tr>';
      echo '<tr>';
        echo '<th align="center"><p style="color:grey;">Température extérieure</p></th>';
        echo '<th align="center" colspan=2><p style="color:grey;">'.$val9.' °C</p></th>';
      echo '</tr>';
    echo '</tbody>';
  echo '</table>';
  echo '</strong>';
}
?>

<?php 
include_file('3rdparty', 'jquery/jquery.min', 'js');
include_file('desktop', 'dom2reg', 'js', 'dom4_otg');
?>
