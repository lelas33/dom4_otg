<?php
if (!isConnect()) {
    throw new Exception('{{401 - Accès non autorisé}}');
}

$date = array(
    'start' => date('Y-m-d', strtotime(config::byKey('history::defautShowPeriod') . ' ' . date('Y-m-d'))),
    'end' => date('Y-m-d'),
);
sendVarToJS('eqType', 'dom4_otg');
sendVarToJs('object_id', init('object_id'));
$eqLogics = eqLogic::byType('dom4_otg');

?>

<div class="row" id="div_pool">
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1" style="height: 350px;padding-top:10px">
            <fieldset style="border: 1px solid #e5e5e5; border-radius: 5px 5px 0px 5px;background-color:#f8f8f8">
              <div class="pull-left" style="min-height: 100px;">
                <img id="piscine_img" src=<?php echo "plugins/dom4_otg/desktop/php/chauffage-img.jpg"; ?> width="1200" />
              </div>
            </fieldset>
        </div>
    </div>
    <div>
      <div class="row">
      <div class="col-lg-10 col-lg-offset-1" style="padding-top:10px">
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#heating_info_tab" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-tachometer"></i> {{Semaine en cours}}</a></li>
          <li role="presentation"><a href="#heating_stat_tab" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-tachometer"></i> {{Statistiques}}</a></li>
        </ul>
      </div>
      </div>
      <div class="row">
      <div class="tab-content" style="height:1200px;">
        <div role="tabpanel" class="tab-pane" id="heating_info_tab">
          <div class="row">
            <div class="col-lg-10 col-lg-offset-1" style="height: 110px;padding-top:10px;">
              <form class="form-horizontal">
                <fieldset style="border: 1px solid #e5e5e5; border-radius: 5px 5px 0px 5px;background-color:#f8f8f8">
                  <div style="min-height: 10px;">
                  </div>
                  <div style="min-height:40px;font-size: 1.5em;">
                    <i style="font-size: initial;"></i> {{Période analysée}}
                  </div>
                  <div style="min-height:50px;">
                    <div style="padding-top:10px;font-size: 1.5em;">
                      <a style="margin-right:5px;" class="pull-left btn btn-success btn-sm tooltips" id='btheating_per_today'>{{Aujourd'hui}}</a>
                      <a style="margin-right:5px;" class="pull-left btn btn-success btn-sm tooltips" id='btheating_per_yesterday'>{{Hier}}</a>
                      <a style="margin-right:5px;" class="pull-left btn btn-success btn-sm tooltips" id='btheating_per_this_week'>{{Cette semaine}}</a>
                      <a style="margin-right:5px;" class="pull-left btn btn-success btn-sm tooltips" id='btheating_per_prev_week'>{{La semaine derniere}}</a>
                    </div>
                  </div>
                </fieldset>
              </form>
            </div>
            <div class="col-lg-2">
            </div>
          </div>
          <div class="row">
            <div class="col-lg-10 col-lg-offset-1" style="height: 150px;padding-top:10px;">
              <form class="form-horizontal">
                <fieldset style="border: 1px solid #e5e5e5; border-radius: 5px 5px 5px 5px;background-color:#f8f8f8">
                   <div style="padding-top:10px;padding-left:24px;padding-bottom:10px;color: #333;font-size: 1.5em;">
                       <i style="font-size: initial;"></i> {{Informations pour la semaine en cours}}
                   </div>
                   <div style="min-height: 30px;">
                     <img src="plugins/dom4_otg/desktop/php/tempe-ext.jpg"; width="150" />
                     <i style="font-size: 1.5em;">{{Température extérieure}}</i>
                   </div>
                   <div id='div_graph_info_tempe' style="font-size: 1.2em;"></div>
                   <div style="min-height: 30px;">
                     <img src="plugins/dom4_otg/desktop/php/eau-chaude.jpg"; width="150" />
                     <img src="plugins/dom4_otg/desktop/php/radiateur.jpg"; width="150" />
                     <img src="plugins/dom4_otg/desktop/php/elec.jpg"; width="150" />
                     <i style="font-size: 1.5em;">{{Consommation Eau Chaude Sanitaire / Chauffage / Electricité}}</i>
                   </div>
                   <div id='div_graph_info_conso' style="font-size: 1.2em;"></div>
                </fieldset>
                <div style="min-height: 10px;"></div>
              </form>
            </div>
            <div class="col-lg-2">
            </div>
          </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="heating_stat_tab">
          <div class="row">
              <div class="col-lg-8 col-lg-offset-2" style="padding-top:10px">
                <form class="form-horizontal">
                     <fieldset style="border: 1px solid #e5e5e5; border-radius: 5px 5px 5px 5px;background-color:#f8f8f8">
                         <div style="padding-top:10px;padding-left:24px;padding-bottom:10px;color: #333;font-size: 1.5em;">
                             <i style="font-size: initial;"></i> {{Statistiques par mois de consommation de Gaz}}
                         </div>
                         <div style="min-height: 30px;">
                           <img src="plugins/dom4_otg/desktop/php/eau-chaude.jpg"; width="150" />
                           <img src="plugins/dom4_otg/desktop/php/radiateur.jpg"; width="150" />
                           <i style="font-size: 1.5em;">{{Consommation totale (ECS + Chauffage), sur l'année}}</i>
                         </div>
                         <div id='div_graph_stat_total' style="font-size: 1.2em;"></div>
                         <div style="min-height: 30px;">
                           <img src="plugins/dom4_otg/desktop/php/eau-chaude.jpg"; width="150" />
                           <img src="plugins/dom4_otg/desktop/php/radiateur.jpg"; width="150" />
                           <i style="font-size: 1.5em;">{{Consommation totale (ECS + Chauffage), sur le dernier mois}}</i>
                         </div>
                         <div id='div_graph_detstat_total' style="font-size: 1.2em;"></div>
                     </fieldset>
                     <div style="min-height: 10px;"></div>
                     <fieldset style="border: 1px solid #e5e5e5; border-radius: 5px 5px 5px 5px;background-color:#f8f8f8">
                         <div style="padding-top:10px;padding-left:24px;padding-bottom:10px;color: #333;font-size: 1.5em;">
                             <i style="font-size: initial;"></i> {{Statistiques par mois de consommation d'électricité}}
                         </div>
                         <div style="min-height: 30px;">
                           <img src="plugins/dom4_otg/desktop/php/elec.jpg"; width="150" />
                           <i style="font-size: 1.5em;">{{Consommation totale, sur l'année}}</i>
                         </div>
                         <div id='div_graph_stat_totale' style="font-size: 1.2em;"></div>
                         <div style="min-height: 30px;">
                           <img src="plugins/dom4_otg/desktop/php/elec.jpg"; width="150" />
                           <i style="font-size: 1.5em;">{{Consommation totale, sur le dernier mois}}</i>
                         </div>
                         <div id='div_graph_detstat_totale' style="font-size: 1.2em;"></div>
                     </fieldset>
                 </form>
              </div>
          </div>
        </div>
      </div>
    </div>
    </div>
</div>
<?php include_file('desktop', 'panel', 'js', 'dom4_otg');?>
