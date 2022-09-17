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

?>

  <div class="col-lg-6">
    <table width="100%">
      <th width="45%" align="left"><legend>Consignes mode Jour</legend></th>
    </table>
    <div id='div_ConsignesJour' style="padding-top:10px;padding-bottom:10px"></div>
  </div>
  <div class="col-lg-6">
    <table width="100%">
      <th width="40%" align="left"><legend>Consignes mode Semaine</legend></th>
      <th width="35%" align="center"><legend>Journée:&nbsp<input type="text" id="seljour_amount" style="border: 0; color: #f6931f; width:110px; font-weight: bold;" /></legend></th>
      <th width="25%" align="center"><div id="seljour_slider" style="width: 100%;display:inline-block;background-color:#cccccc!important"></div></th>
    </table>
    <div id='div_ConsignesHebdo' style="padding-top:10px;padding-bottom:10px"></div>
  </div>
  <div class="col-lg-6">
    <legend>Activation par pièce:</legend>
    <div id ="piecej_enable">
    </div>
  </div>  
  <div class="col-lg-6">
   <legend>Autres paramètres:</legend>
   <table width="100%">
      <tr>
        <td width="50%" align="left"><strong>Retour vacances:</strong></td>
        <td width="50%" align="left"><input id="datepicker_retvac" type="text" style="width:100px;font-size: 1.0em;">
        <input id="alternate_retvac" type="text" style="width:150px; background:#e0e0e0;font-size: 1.0em;">
        <input id="heure_retvac" type="text" style="width:60px; font-size: 1.0em;"></td>
      </tr>
      <tr>
        <td width="50%" align="left"><strong>Température Pièce inoccupée:</strong></td>
        <td width="50%" align="left"><input type="text" id="temp_ino_amount" style="border:0;color:#f6931f;width:40px;font-weight: bold;text-align:center;"/><strong> °C</strong></td>
      </tr>
      <tr>
        <td width="50%" align="left"><strong>Température Antigel:</strong></td>
        <td width="50%" align="left"><input type="text" id="temp_antigel_amount" style="border:0;color:#f6931f;width:40px;font-weight: bold;text-align:center;" /><strong> °C</strong></td>
      </tr>
      <tr>
        <td width="50%" align="left"><strong>Pente Courbe de chauffe<br>(Température extérieure):</strong></td>
        <td width="50%" align="left"><input type="text" id="epente_cdc" style="border:0;color:#f6931f;width:40px;font-weight: bold;text-align:center;" /><strong> °C/°C</strong></td>
      </tr>
      <tr>
        <td width="50%" align="left"><strong>Pente Courbe de chauffe<br>(Température intérieure):</strong></td>
        <td width="50%" align="left"><input type="text" id="ipente_cdc" style="border:0;color:#f6931f;width:40px;font-weight: bold;text-align:center;" /><strong> °C/°C</strong></td>
      </tr>
    </table>
  </div>
  <div class="col-lg-6">
    <legend>Mise à jour:</legend>
    <a class="btn btn-success btn-sm tooltips" id='bt_reload_cfg' title="{{Rechargement configuration de la centrale DOM4G, puis rechargement }}">{{Chargement Config. et Rechargement}}</a>
    <a class="btn btn-success btn-sm tooltips" id='bt_reload' title="{{Rechargement depuis la centrale DOM4G}}">{{Rechargement}}</a>
    <a class="btn btn-success btn-sm tooltips" id='bt_update' title="{{Mise à jour vers la centrale DOM4G}}">{{Mise à jour}}</a>
    <a class="btn btn-success btn-sm tooltips" id='bt_update_save' title="{{Mise à jour vers la centrale DOM4G et enregistrement}}">{{Mise à jour et Sauvegarde}}</a><br>
  </div>

<?php 
include_file('3rdparty', 'jquery/jquery.min', 'js');
include_file('3rdparty', 'jquery.ui/jquery-ui.min', 'js');
include_file('3rdparty', 'jquery.ui/jquery-ui', 'css');
include_file('3rdparty', 'highstock/highstock', 'js');
include_file('3rdparty', 'bootstrap/bootstrap.min', 'js');
include_file('3rdparty', 'bootstrap/css/bootstrap.min', 'css');
include_file('desktop', 'prog_chauffage', 'js', 'dom4_otg');
 ?>
