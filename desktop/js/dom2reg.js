
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


var NB_PIECE = 6;

var stat_regulation    = [[]];
var piece_name  = ["Chambre.P", "Chambre.E", "Chambre.B", "Séjour",  "Bureau",  "Chambre.I" ];
var piece_color = ["#efce8f",   "#0080ff",   "#008000",   "#b49763", "#ff8000", "#d0d0d0" ];
var sel_jour = 0;
var jour_name = ["Lundi", "Mardi", "Mercredi", "Jeudi",   "Vendredi",  "Samedi", "Dimanche" ];



// Fonctions realisées au chargement de la page
// Chargement des statistiques de regulation depuis DOM2G
// =======================================================
$(document).ready(function() {
  GetStatRegulation();
});

// Chargement des consignes depuis DOM2G
// =====================================
function GetStatRegulation() {
    $.ajax({
      type: 'POST',
      async:true,
      url: "plugins/dom4_otg/core/ajax/dom4_otg.ajax.php", // url du fichier php
      data: {
        action:'GetStatRegulation',
        derive:'',
        allowZero:1
        },
      dataType: 'json',
      error: function (request, status, error) {
        alert("GetStatRegulation:Error"+status+"/"+error);
        handleAjaxError(request, status, error);
      },
      success: function (data) {
        stat_regul = jQuery.parseJSON(data.result);
        //alert("GetStatRegulation:"+stat_regul.reg_consi[0]);
        for (p=0;p<NB_PIECE;p++) {
          stat_regulation[p] = [];
          stat_regulation[p][0] = stat_regul.reg_consi[p];
          stat_regulation[p][1] = stat_regul.reg_ctemp[p];
          stat_regulation[p][2] = stat_regul.ect_current[p];
          stat_regulation[p][3] = stat_regul.ect_maxipos[p];
          stat_regulation[p][4] = stat_regul.ect_maxineg[p];
          stat_regulation[p][5] = stat_regul.ect_mean[p];
        }
        display_stat();
      }
    });
}

// Mise a jour du tableau d'affichage sur la page HTML
// ===================================================
function display_stat() {
  
  buff = '';
  buff += '<font size="3" color="green"><strong style="font-size:1.5em;">';
  buff += 'Statistiques de suivi de la régulation du chauffage par pièce';
  buff += '</strong></font>';
  // creation tableau
  buff += '<br><br><table width="60%" border="1" cellpadding="10">';
  // ligne entete
  buff += '<tr>';
  buff += '<td width="10%" align="center" height=25 style="background-color:#606060"><strong style="font-size:1.2em;color:LightGray;">Pièce</strong></th>';
  buff += '<td width="10%" align="center" style="background-color:#606060"><strong style="font-size:1.2em;color:LightGray;">Consigne</strong></th>';
  buff += '<td width="10%" align="center" style="background-color:#606060"><strong style="font-size:1.2em;color:LightGray;">Température</strong></th>';
  buff += '<td width="10%" align="center" style="background-color:#606060"><strong style="font-size:1.2em;color:LightGray;">Ecart courant</strong></th>';
  buff += '<td width="10%" align="center" style="background-color:#606060"><strong style="font-size:1.2em;color:LightGray;">Ecart Mini</strong></th>';
  buff += '<td width="10%" align="center" style="background-color:#606060"><strong style="font-size:1.2em;color:LightGray;">Ecart Maxi</strong></th>';
  buff += '<td width="10%" align="center" style="background-color:#606060"><strong style="font-size:1.2em;color:LightGray;">Ecart moyen</strong></th>';
  buff += '</tr>';
  // contenu du tableau
  for (p=0;p<NB_PIECE;p++) {
    buff += '<tr>';
    buff += '<td align="center" height=25><strong style="font-size:1.0em;color:'+piece_color[p]+'">'+piece_name[p]+'</strong></td>';
    if (stat_regulation[p][0] != 99) {
      buff += '<td align="center"><p style="color:grey;">'+stat_regulation[p][0]+'</p></td>';
      buff += '<td align="center"><p style="color:grey;">'+stat_regulation[p][1]+'</p></td>';
      buff += '<td align="center"><p style="color:grey;">'+stat_regulation[p][2]+'</p></td>';
      buff += '<td align="center"><p style="color:grey;">'+stat_regulation[p][4]+'</p></td>';
      buff += '<td align="center"><p style="color:grey;">'+stat_regulation[p][3]+'</p></td>';
      buff += '<td align="center"><p style="color:grey;">'+stat_regulation[p][5]+'</p></td>';
    }
    else {
      buff += '<td align="center">-</td>';
      buff += '<td align="center">-</td>';
      buff += '<td align="center">-</td>';
      buff += '<td align="center">-</td>';
      buff += '<td align="center">-</td>';
      buff += '<td align="center">-</td>';
    }
    buff += '</tr>';
  }
  buff += '</table>';
  //Fin du tableau
  $("#div_statregul").empty();
  $("#div_statregul").append(buff);
  
}

