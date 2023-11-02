
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
 var globalEqLogic = $("#eqlogic_select option:selected").val();
 var isCoutVisible = false;
$(".in_datepicker").datepicker();

// Variables partagées

const CHA_COLOR_NAMES = [
  "LightPink",
  "LightSalmon",
  "LightYellow",
  "Lime",
  "Magenta",
  "MidnightBlue",
  "Orange",
  "OrangeRed",
  "Orchid",
  "PaleGoldenRod",
  "Pink",
  "Purple",
  "Red",
  "RoyalBlue",
  "Salmon",
  "SkyBlue",
  "Tomato",
  "Turquoise",
  "Violet",
  "White",
  "Yellow"
];

const ECS_COLOR_NAMES = [
  "Aquamarine",
  "Blue",
  "BlueViolet",
  "Brown",
  "Chocolate",
  "Coral",
  "Cyan",
  "DarkBlue",
  "DarkCyan",
  "Aqua",
  "DarkGrey",
  "DarkRed",
  "DarkSalmon",
  "DimGrey",
  "Fuchsia",
  "Gold",
  "Green",
  "GreenYellow",
  "Indigo",
  "LightGreen"
];

var heating_stat_loaded = 0;
var heating_info_loaded = 0;

const DAY_NAME = ["Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi"];

// Fonctions realisées au chargement de la page: charger les données sur la période par défaut,
// et afficher les infos correspondantes
// ============================================================================================
$(document).ready(function() {
  // Show pool stats
  $(".tab_content").hide(); //Hide all content
  $("#heating_info_tab").addClass("active").show(); //Activate first tab
  cmd_get_histo(1);
  heating_info_loaded = 1;

});

// Sélection des différents onglets
// ================================
$('.nav li a').click(function(){

	var selected_tab = $(this).attr("href");
  
  if (selected_tab == "#heating_stat_tab") {
    if (heating_stat_loaded == 0) {
      loadStats();
      heating_stat_loaded = 1;
    }
  }
  else if (selected_tab == "#heating_info_tab") {
    if (heating_info_loaded == 0) {
      cmd_get_histo(1);
      heating_info_loaded = 1;
    }
  }

});


// =======================================================================
//       Gestion des infos de controle de la piscine sur une periode
// =======================================================================

// gestion du bouton de definition et de mise à jour de la période pour les trajets
// ================================================================================
// Aujourd'hui
$('#btheating_per_today').on('click',function(){
  cmd_get_histo(1);
});
// Hier
$('#btheating_per_yesterday').on('click',function(){
  cmd_get_histo(2);
});
// Cette semaine
$('#btheating_per_this_week').on('click',function(){
  cmd_get_histo(3);
});
// Les 7 derniers jours
$('#btheating_per_prev_week').on('click',function(){
  cmd_get_histo(4);
});


// Interrogation serveur pour historique sur une periode de temps
// ==============================================================
function cmd_get_histo(range_param) {
  $.ajax({
    type: "POST",
    url: 'plugins/dom4_otg/core/ajax/dom4_otg.ajax.php',
    data: {
      action: "getHeatingFullHistory",
      range: range_param,
    },
    dataType: 'json',
    error: function (request, status, error) {
      alert("loadData:Error"+status+"/"+error);
      handleAjaxError(request, status, error);
    },
    success: function (data) {
      console.log("[cmd_get_histo] Historique objet récupéré");
      if (data.state != 'ok') {
          $('#div_alert').showAlert({message: data.result, level: 'danger'});
          return;
      }
      console.log("historique:"+data.result);
      histo_data = JSON.parse(data.result);
      display_histo(histo_data);
    }
  });
}

// Génération des graphes d'historique sur une periode de temps
// ============================================================
function display_histo(histo_data) {

  // Mise en forme des donnees du premier graphe
  var te_serie =  {
      name: "Tempé. Ext",
      color: "Green",
      data: []
    };
  if (histo_data.te_ts) {
    console.log("Nb points te_serie:"+histo_data.te_ts.length);
    for (idx=0; idx<histo_data.te_ts.length; idx++) {
      te_serie.data.push([parseInt(histo_data.te_ts[idx])*1000, parseFloat(histo_data.te_va[idx])]);
    }
  }

  // Mise en forme des donnees du second graphe
  var ce_serie =  {
      name: "Conso. ECS",
      step: 'left', // or 'center' or 'right'
      color: "Aquamarine",
      data: []
    };
  var cc_serie =  {
      name: "Conso. Chauffage",
      step: 'left', // or 'center' or 'right'
      color: "Red",
      data: []
    };
  var cel_serie =  {
      name: "Conso. Electricité",
      step: 'left', // or 'center' or 'right'
      color: "Blue",
      data: []
    };
  if (histo_data.ce_ts) {
    console.log("Nb points ce_serie:"+histo_data.ce_ts.length);
    for (idx=0; idx<histo_data.ce_ts.length; idx++) {
      ce_serie.data.push([parseInt(histo_data.ce_ts[idx])*1000, parseFloat(histo_data.ce_va[idx])]);
    }
  }
  if (histo_data.cc_ts) {
    console.log("Nb points cc_serie:"+histo_data.cc_ts.length);
    for (idx=0; idx<histo_data.cc_ts.length; idx++) {
      cc_serie.data.push([parseInt(histo_data.cc_ts[idx])*1000, parseFloat(histo_data.cc_va[idx])]);
    }
  }
  if (histo_data.cel_ts) {
    console.log("Nb points cel_serie:"+histo_data.cel_ts.length);
    for (idx=0; idx<histo_data.cel_ts.length; idx++) {
      cel_serie.data.push([parseInt(histo_data.cel_ts[idx])*1000, parseFloat(histo_data.cel_va[idx])]);
    }
  }

  Highcharts.setOptions({
    global: {
        timezoneOffset: -2 * 60
    }
  });
  // Temperature exterieure
  Highcharts.chart('div_graph_info_tempe', {
      chart: {
          plotBackgroundColor:'#808080',
          type: 'spline'
      },
      title: {
          text: ''
      },
      xAxis: {
          type: 'datetime',
          dateTimeLabelFormats: { // don't display the dummy year
              month: '%e. %b',
              year: '%b'
          },
          title: {
              text: 'Date'
          }
      },
      yAxis: {
          // min: 0,
          title: {
              text: 'Température (°C)'
          }
      },
      tooltip: {
        headerFormat: '<b>{series.name}</b><br>',
        pointFormat: '{point.x:%e. %b - %H:%M}: {point.y:.1f} °C'
      },
      plotOptions: {
        spline: {
          lineWidth: 4,
          marker: {
            enabled: false
          }
        }
      },
      series: [te_serie]
  });

  // Consommation Gaz chauffage, Gaz ECS, Electricite
  Highcharts.chart('div_graph_info_conso', {
      chart: {
          plotBackgroundColor:'#808080',
          type: 'line'
      },
      title: {
          text: ''
      },
      xAxis: {
          type: 'datetime',
          dateTimeLabelFormats: { // don't display the dummy year
              month: '%e. %b',
              year: '%b'
          },
          title: {
              text: 'Date'
          }
      },
      yAxis: {
          min: 0,
          title: {
              text: 'Consommation (kWh)'
          }
      },
      tooltip: {
        headerFormat: '<b>{series.name}</b><br>',
        pointFormat: '{point.x:%e. %b - %H:%M}: {point.y:.1f} kWh'
      },
      plotOptions: {
        line: {
          lineWidth: 4,
          marker: {
            enabled: false
          }
        }
      },
      series: [ce_serie, cc_serie, cel_serie]
  });

}

// =================================================================================
//     Gestion des statistiques des infos de consommation du chauffage + ECS + Elec
// =================================================================================

// capturer les donnees depuis le serveur sur la totalite de l'historique
// ======================================================================
function loadStats(){
    var param = [];
    $.ajax({
        type: 'POST',
        url: 'plugins/dom4_otg/core/ajax/dom4_otg.ajax.php',
        data: {
            action: 'getHeatingStat',
            param: param
        },
        dataType: 'json',
        error: function (request, status, error) {
            alert("loadData:Error"+status+"/"+error);
            handleAjaxError(request, status, error);
        },
        success: function (data) {
            console.log("[loadStats] Objet statistique récupéré");
            if (data.state != 'ok') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            console.log("stat_dt:"+data.result);
            stat_data = JSON.parse(data.result);
            heating_stats(stat_data);
        }
    });
}


// Génération des graphes de statistiques pour l'ensemble des donnees
// ==================================================================
function heating_stats(stat_data) {

  // recuperation des infos de configuration
  var cfg_cost_kwh = stat_data.cost_kwh;
  // var cfg_conso_ecs = stat_data.ecs;
  // var cfg_conso_cha = stat_data.cha;
  // var cfg_conso_ele = stat_data.ele;

  // mise en forme des donnnes annuelles: GAZ
  var sum_duree = [];
  var dt_ecs = [[]];
  var dt_cha = [[]];
  var dt_total = [[]];
  for (y=2022; y<2040; y++) {
    sum_duree[y] = 0;
    dt_ecs[y] = [];
    dt_cha[y] = [];
    dt_total[y] = [];
    for (m=1; m<=12; m++) {
      // ECS
      dt_ecs[y][m-1] = 0;
      if (stat_data.ecs[y] != null)
        if (stat_data.ecs[y][m] != null)
          dt_ecs[y][m-1] = Math.round(stat_data.ecs[y][m]);
        else
          dt_ecs[y][m-1] = 0;
      // somme duree ECS
      sum_duree[y] += dt_ecs[y][m-1];
      // Chauffage
      dt_cha[y][m-1] = 0;
      if (stat_data.cha[y] != null)
        if (stat_data.cha[y][m] != null)
          dt_cha[y][m-1] = Math.round(stat_data.cha[y][m]);
        else
          dt_cha[y][m-1] = 0;
      // Consommation totale
      dt_total[y][m-1] = 0;
      if ((stat_data.ecs[y] != null) && (stat_data.ecs[y][m] != null))
          dt_total[y][m-1]  = Math.round(stat_data.ecs[y][m]);
      if ((stat_data.cha[y] != null) && (stat_data.cha[y][m] != null))
          dt_total[y][m-1] += Math.round(stat_data.cha[y][m]);
    }
  }
  // console.log("dt_ecs:"+dt_ecs[2022]);
  // console.log("dt_cha:"+dt_cha[2022]);
  // console.log("dt_total:"+dt_total[2022]);
  // dt_ecs[2023] = dt_ecs[2022];
  // dt_cha[2023] = dt_cha[2022];
  // sum_duree[2023] = sum_duree[2022];

  // mise en forme des donnnes annuelles: ELEC
  var sum_duree_el = [];
  var dt_elec = [[]];
  for (y=2022; y<2040; y++) {
    sum_duree_el[y] = 0;
    dt_elec[y] = [];
    for (m=1; m<=12; m++) {
      dt_elec[y][m-1] = 0;
      if (stat_data.ele[y] != null)
        if (stat_data.ele[y][m] != null)
          dt_elec[y][m-1] = Math.round(stat_data.ele[y][m]);
        else
          dt_elec[y][m-1] = 0;
      // somme duree Elec
      sum_duree_el[y] += dt_elec[y][m-1];
    }
  }
  
  // mise en forme des donnnes des mois precedent et courant:GAZ
  var det_tot_series_previous = {
    name: "Mois précédent",
    color: ECS_COLOR_NAMES[0],
    data: stat_data.prev_month
  };
  var det_tot_series_current = {
    name: "Mois courant",
    color: ECS_COLOR_NAMES[1],
    data: stat_data.cur_month
  };
  console.log("det_tot_series_previous:"+det_tot_series_previous.data);
  console.log("det_tot_series_current:"+det_tot_series_current.data);
  // mise en forme des donnnes des mois precedent et courant:ELEC
  var det_tot_series_previous_el = {
    name: "Mois précédent",
    color: ECS_COLOR_NAMES[0],
    data: stat_data.prev_month_el
  };
  var det_tot_series_current_el = {
    name: "Mois courant",
    color: ECS_COLOR_NAMES[1],
    data: stat_data.cur_month_el
  };

  // mise au format attendu par highcharts
  tot_series = [];
  for (y=2022; y<2040; y++) {
    var serie_ecs = {
      name: 'ECS:'+y,
      color: ECS_COLOR_NAMES[y-2022],
      data: dt_ecs[y],
      stack: y
    };
    var serie_cha = {
      name: 'Chauffage:'+y,
      color: CHA_COLOR_NAMES[y-2022],
      data: dt_cha[y],
      stack: y
    };
    if (sum_duree[y] != 0) {
      tot_series.push(serie_ecs);
      tot_series.push(serie_cha);
    }
  }
  tot_series_el = [];
  for (y=2022; y<2040; y++) {
    var serie_ele = {
      name: 'Elec:'+y,
      color: ECS_COLOR_NAMES[y-2022],
      data: dt_elec[y],
      stack: y
    };
    if (sum_duree_el[y] != 0) {
      tot_series_el.push(serie_ele);
    }
  }
  // console.log("pmp_series:"+dt_pmp);

  // Consommation totale
  Highcharts.chart('div_graph_stat_total', {
      chart: {
          plotBackgroundColor:'#808080',
          type: 'column'
      },
      title: {
          text: ''
      },
      xAxis: {
          title: {
              text: 'Mois'
          },
          categories: ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec']
      },
      yAxis: {
          min: 0,
          title: {
              text: 'Consom. (kWh) / Coût (€)'
          }
      },
      tooltip: {
          headerFormat: '<b>{point.x}</b><br/>',
          pointFormat: '{series.name}: {point.y} kW/h<br/>Total: {point.stackTotal} kW/h'
      },
      plotOptions: {
          column: {
              stacking: 'normal'
              }
      },
      series: tot_series
  });
  
  // Consommation totale detaille par jour du mois en cours et du mois precedent
  Highcharts.chart('div_graph_detstat_total', {
      chart: {
          plotBackgroundColor:'#808080',
          type: 'column'
      },
      title: {
          text: ''
      },
      xAxis: {
          title: {
              text: 'Jour'
          },
      },
      yAxis: {
          min: 0,
          title: {
              text: 'Consom. (kWh) / Coût (€)'
          }
      },
      // plotOptions: {
          // series: {
              // pointStart: Date.UTC(2022, 6, 1),
              // pointIntervalUnit: 'day'
          // }
      // },
      tooltip: {
          shared: true,
          useHTML: true,
          formatter: function () {
              return this.points.reduce(function (s, point) {
                  var hdr  = '<br/><span style="color:'+ point.series.color +';font-size:14px"><b>' + point.series.name + ': </b></span>';
                  var data = '<span style="font-size:14px">'+Math.round(point.y * 10) / 10 + ' kWh / ' + Math.round(10*point.y*cfg_cost_kwh)/10 + ' €</span>';
                  return (s + hdr + data);
              }, '<span style="font-size:16px"><b>'+this.x+'</b></span>');
          }
      },
      series: [det_tot_series_previous, det_tot_series_current]
  });

  // Consommation totale electrique
  Highcharts.chart('div_graph_stat_totale', {
      chart: {
          plotBackgroundColor:'#808080',
          type: 'column'
      },
      title: {
          text: ''
      },
      xAxis: {
          title: {
              text: 'Mois'
          },
          categories: ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec']
      },
      yAxis: {
          min: 0,
          title: {
              text: 'Consom. (kWh) / Coût (€)'
          }
      },
      tooltip: {
          headerFormat: '<b>{point.x}</b><br/>',
          pointFormat: '{series.name}: {point.y} kW/h<br/>Total: {point.stackTotal} kW/h'
      },
      plotOptions: {
          column: {
              stacking: 'normal'
              }
      },
      series: tot_series_el
  });
  
  // Consommation totale electrique detaille par jour du mois en cours et du mois precedent
  Highcharts.chart('div_graph_detstat_totale', {
      chart: {
          plotBackgroundColor:'#808080',
          type: 'column'
      },
      title: {
          text: ''
      },
      xAxis: {
          title: {
              text: 'Jour'
          },
      },
      yAxis: {
          min: 0,
          title: {
              text: 'Consom. (kWh) / Coût (€)'
          }
      },
      // plotOptions: {
          // series: {
              // pointStart: Date.UTC(2022, 6, 1),
              // pointIntervalUnit: 'day'
          // }
      // },
      tooltip: {
          shared: true,
          useHTML: true,
          formatter: function () {
              return this.points.reduce(function (s, point) {
                  var hdr  = '<br/><span style="color:'+ point.series.color +';font-size:14px"><b>' + point.series.name + ': </b></span>';
                  var data = '<span style="font-size:14px">'+Math.round(point.y * 10) / 10 + ' kWh / ' + Math.round(10*point.y*cfg_cost_kwh)/10 + ' €</span>';
                  return (s + hdr + data);
              }, '<span style="font-size:16px"><b>'+this.x+'</b></span>');
          }
      },
      series: [det_tot_series_previous_el, det_tot_series_current_el]
  });

}

