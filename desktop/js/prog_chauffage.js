
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


var NB_PIECE = 8;
var NB_TAILLE_CONSIGNE = 96;     // Nombre de point de consigne de température par jour ( 1 par 15 mn )

var cons_chauffage_jour    = [[]];
var cons_chauffage_jouren  = [];
var cons_chauffage_hebdo   = [[[]]];
var cons_chauffage_hebdoen = [[]];
var temp_name  = [];
var temp_color = ["#F09880",   "#FF1000",   "#0000FF",   "#CC00FF",  "#D06820", "#880088",  "#D06820", "#880088" ];
var sel_jour = 0;
var jour_name = ["Lundi", "Mardi", "Mercredi", "Jeudi",   "Vendredi",  "Samedi", "Dimanche" ];


// Fonctions realisées au chargement de la page
// Chargement des consignes depuis DOM2G
// =======================================================
$(document).ready(function() {
  getConsignes();
});


  // Mise en place du slider "jour select"
  var aujourdhui = new Date();
  sel_jour = aujourdhui.getDay();
  sel_jour = (sel_jour == 0)?6:sel_jour-1;        // jour de 0 … 6, avec 0 = lundi, 6 = dimanche
  $( "#seljour_slider" ).slider({
    value:sel_jour,
    min: 0, // Lundi
    max: 6, // Dimanche
    step:1,
    slide: function( event, ui ) {
        $( "#seljour_amount" ).val(jour_name[ui.value]);
      },
    stop: function( event, ui ) {
        // Envoi de la nouvelle valeur par requete ajax
        sel_jour = $( "#seljour_slider" ).slider( "value" );
        //alert ("journee="+sel_jour);
        dispay_consigne_hebdo();
        // dispay_enable_hebdo();
      }
    });
  $( "#seljour_amount" ).val(jour_name[$("#seljour_slider" ).slider( "value" )]);

  // boutons de mise a jour / rechargement
  $('#bt_reload_cfg').on('click',function(){
    dom2g_reload_cfg();
  });
  $('#bt_reload').on('click',function(){
    getConsignes();
  });
  $('#bt_update').on('click',function(){
    dom2g_update(0);  // update only
  });
  $('#bt_update_save').on('click',function(){
    dom2g_update(1);  // update & save
    //dom2g_save_cfg();
  });

// Rechargement configuration sur centrale DOM2G
// =============================================
function dom2g_reload_cfg() {
    $.ajax({
      type: 'POST',
      async:true,
      url: "plugins/dom4_otg/core/ajax/dom4_otg.ajax.php", // url du fichier php
      data: {
        action:'ReloadConfig',
        },
      dataType: 'json',
      error: function (request, status, error) {
          handleAjaxError(request, status, error);
      },
      success: function (data) {
        // recharge les consignes depuis la centrale DOM2G
        getConsignes();
      }
    });
}

// Mise à jour vers la centrale DOM2G
// ==================================
function dom2g_update( save ) {
    var retvac_date = $("#datepicker_retvac").datepicker("getDate");
    var param = [];
    var heure_retvac = $("#heure_retvac").val().split('h');
    param[0]= retvac_date.getDate();
    param[1]= retvac_date.getMonth()+1;
    param[2]= retvac_date.getFullYear()-2000;
    param[3]= parseInt(heure_retvac[0]);
    param[4]= parseInt(heure_retvac[1]);
    param[5]= parseInt($("#temp_ino_amount").val()*10.0); // Temperature des pieces inoccupees ( 14.0 )
    param[6]= parseInt($("#temp_antigel_amount").val()*10.0); // Temperature de consigne antigel   (  5.0 )
    param[7]= parseInt($("#epente_cdc").val()*10.0); // pente courbe de chauffe temp. ext
    param[8]= parseInt($("#ipente_cdc").val()*10.0); // pente courbe de chauffe temp. int
    param[9]= 0xff;
    for (p=0; p<NB_PIECE; p++) {
      ret1 = $('input[name=jour_enp'+p+']').is(':checked');
      ret2 = $('input[name=jour_enp'+p+']').is(':disabled');
      param[10+p]= ret1 ? 1:(ret2?255:0);
      // console.log("[dom2g_update] Piece : " + p + " / ret1 = " + ret1 + " / ret2 = " + ret2 + " / enable = " + param[10+p]);
    }
    $.ajax({
      type: 'POST',
      async:true,
      url: "plugins/dom4_otg/core/ajax/dom4_otg.ajax.php", // url du fichier php
      data: {
        action:'UpdateConfig',
        param:param
        },
      dataType: 'json',
      error: function (request, status, error) {
          handleAjaxError(request, status, error);
          alert('ajax error');
      },
      success: function (data) {
        if (save == 0)
          alert ('Mise à jour DOM2G réalisée');
        else {
          dom2g_save_cfg();
        }
      }
    });
}

// Sauvegarde configuration sur centrale DOM2G
// ===========================================
function dom2g_save_cfg() {
    $.ajax({
      type: 'POST',
      async:true,
      url: "plugins/dom4_otg/core/ajax/dom4_otg.ajax.php", // url du fichier php
      data: {
        action:'SaveConfig',
        },
      dataType: 'json',
      error: function (request, status, error) {
          handleAjaxError(request, status, error);
      },
      success: function (data) {
        // 
        alert ('Sauvegarde DOM2G réalisée');
      }
    });
}


// Chargement des consignes depuis DOM2G
// =====================================
function getConsignes() {
    $.ajax({
      type: 'POST',
      async:true,
      url: "plugins/dom4_otg/core/ajax/dom4_otg.ajax.php", // url du fichier php
      data: {
        action:'getConsignes',
        derive:'',
        allowZero:1
        },
      dataType: 'json',
      error: function (request, status, error) {
          handleAjaxError(request, status, error);
      },
      success: function (data) {
        cons_chau = jQuery.parseJSON(data.result);
        //alert("Consignes de chauffage1:"+cons_chau.hebdo.consigne[1]);
        for (p=0;p<NB_PIECE;p++) {
          cons_chauffage_jour[p] = [];
          cons_chauffage_jouren[p] = cons_chau.jour.enabled[p];
          temp_name[p] = cons_chau.nom_piece[p];
          // console.log("[getConsignes] Piece : " + temp_name[p] + " / enabled = " + cons_chauffage_jouren[p]);
          for (i=0;i<NB_TAILLE_CONSIGNE;i++) {
            cons_chauffage_jour[p].push ([i*15*60*1000, parseInt(cons_chau.jour.consigne[p][i],10)/10.0]);
          }
        }
        // Prise en compte des consignes du mode "auto hebdo"
        for (jour=0;jour<7;jour++) {
          cons_chauffage_hebdo[jour] = [[]];
          cons_chauffage_hebdoen[jour] = [];
          for (p=0;p<NB_PIECE;p++) {
            cons_chauffage_hebdo[jour][p] = [];
            cons_chauffage_hebdoen[jour][p] = cons_chau.hebdo.enabled[jour][p];
            for (i=0;i<NB_TAILLE_CONSIGNE;i++) {
              cons_chauffage_hebdo[jour][p].push ([i*15*60*1000, parseInt(cons_chau.hebdo.consigne[jour][p][i],10)/10.0]);
            }
          }
        }
        // autre valeurs
        $("#temp_ino_amount").val(cons_chau.status.temp_inocc);
        $("#temp_antigel_amount").val(cons_chau.status.temp_antigel);
        var tmp =  (2000+cons_chau.status.vac_annee) + "-" + cons_chau.status.vac_mois + "-" + cons_chau.status.vac_jour;
        $("#datepicker_retvac").datepicker("setDate", tmp);
        $("#heure_retvac" ).val(cons_chau.status.vac_heure + " h " + cons_chau.status.vac_min);
        $("#epente_cdc" ).val(cons_chau.status.epente_cdc);
        $("#ipente_cdc" ).val(cons_chau.status.ipente_cdc);
        //console.log(data.result.data);
        dispay_consigne_jour();
        dispay_consigne_hebdo();
        dispay_enable_jour();
      }
    });
}

// Affichage d'un graph de consigne
// ================================
function drawSimpleGraph(_el, _serie) {
    var legend = {
        enabled: true,
        borderColor: 'black',
        borderWidth: 2,
        shadow: true
    };

    new Highcharts.StockChart({
        chart: {
            zoomType: 'x',
            renderTo: _el,
            height: 350,
            spacingTop: 0,
            spacingLeft: 0,
            spacingRight: 0,
            spacingBottom: 0
        },
        credits: {
            text: 'Jeedom',
            href: 'http://jeedom.fr',
        },
        navigator: {
            enabled: true
        },
        rangeSelector: {
            buttons: [{
                    type: 'hour',
                    count: 1,
                    text: 'H'
                }, {
                    type: 'day',
                    count: 1,
                    text: 'J'
                }],
            selected: 1,
            inputEnabled: false
        },
        legend: legend,
        tooltip: {
            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>',
            valueDecimals: 2,
        },
        yAxis: {
            format: '{value}',
            showEmpty: false,
            showLastLabel: true,
            //min: 0,
            labels: {
                align: 'right',
                x: -5
            }
        },
        scrollbar: {
            barBackgroundColor: 'white',
            barBorderRadius: 7,
            barBorderWidth: 0,
            buttonBackgroundColor: 'white',
            buttonBorderWidth: 0,
            buttonBorderRadius: 7,
            trackBackgroundColor: 'none', trackBorderWidth: 1,
            trackBorderRadius: 8,
            trackBorderColor: '#CCC'
        },
        series: _serie
    });
}

// Affichage du graphe de consigne jour
// ====================================
function dispay_consigne_jour() {
  var Series = [];
  for (i=0;i<NB_PIECE;i++) {
    Series[i] = {};
    Series[i].step = true;
    Series[i].name = temp_name[i];
    Series[i].color = temp_color[i];
    Series[i].data = cons_chauffage_jour[i];
    Series[i].type = 'line';
    Series[i].visible = (cons_chauffage_jouren[i] != 255) ? true:false;
  }
  drawSimpleGraph('div_ConsignesJour', Series);
}

// Affichage du graphe de consigne Semaine
// ====================================
function dispay_consigne_hebdo() {
  var Series = [];
  for (i=0;i<NB_PIECE;i++) {
    Series[i] = {};
    Series[i].step = true;
    Series[i].name = temp_name[i];
    Series[i].color = temp_color[i];
    Series[i].data = cons_chauffage_hebdo[sel_jour][i];
    Series[i].type = 'line';
    Series[i].visible = (cons_chauffage_jouren[i] != 255) ? true:false;
  }
  drawSimpleGraph('div_ConsignesHebdo', Series);
}

// Mise a jour des flags enable mode jour
// ======================================
function dispay_enable_jour() {
  var chkd;
  $("#piecej_enable").empty();
  $("#piecej_enable").append('<table width="100%">');
  for (i=0;i<NB_PIECE/2;i++) {
    $("#piecej_enable").append('<tr>');
    p = 2*i;
    if (cons_chauffage_jouren[p] == 1)
      chkd = 'checked';
    else if (cons_chauffage_jouren[p] == 255)
      chkd = 'disabled="true"';
    else
      chkd = '';
    nom_piece = (cons_chauffage_jouren[p] == 255) ? '<s>'+temp_name[p]+'</s>' : temp_name[p];
    $("#piecej_enable").append('<th width="15%" align="left"><input type="checkbox" name="jour_enp'+p+'" '+chkd+'>&nbsp'+nom_piece+'</th>');
    p = 2*i+1;
    if (cons_chauffage_jouren[p] == 1)
      chkd = 'checked';
    else if (cons_chauffage_jouren[p] == 255)
      chkd = 'disabled="true"';
    else
      chkd = '';
    nom_piece = (cons_chauffage_jouren[p] == 255) ? '<s>'+temp_name[p]+'</s>' : temp_name[p];
    $("#piecej_enable").append('<th width="15%" align="left"><input type="checkbox" name="jour_enp'+p+'" '+chkd+'>&nbsp'+nom_piece+'</th>');
    $("#piecej_enable").append('</tr>');
  }
  $("#piecej_enable").append('</table');
}
      

  // instance datepicker
  // ===================
  $(function() {
    $.datepicker.regional['fr'] = {
      closeText: 'Fermer',
      prevText: '<Préc',
      nextText: 'Suiv>',
      currentText: 'Courant',
      monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin',
      'Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
      monthNamesShort: ['Jan','Fév','Mar','Avr','Mai','Jun',
      'Jul','Aoû','Sep','Oct','Nov','Déc'],
      dayNames: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
      dayNamesShort: ['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'],
      dayNamesMin: ['Di','Lu','Ma','Me','Je','Ve','Sa'],
      weekHeader: 'Sm',
      dateFormat: 'dd/mm/yy',
      firstDay: 1,
      isRTL: false,
      showMonthAfterYear: false,
      yearSuffix: ''};
    $.datepicker.setDefaults($.datepicker.regional['fr']);

    $("#datepicker_retvac").datepicker({
      altField: "#alternate_retvac",
      altFormat: "D d M yy",
      dateFormat: "yy-mm-dd",
      showButtonPanel: true,
      changeMonth: true,
      changeYear: true,
      defaultDate: 0,
      // gestion select datepicker
      onSelect: function(dateText, inst) {
//        chau_par = dateText;
//        alert("dateText="+dateText);
      }
    }).datepicker("setDate", "0");
  });
