<?php
namespace PrestacaoDeContas;
use MapasCulturais\App;

class Plugin extends \MapasCulturais\Plugin {
   function _init () {
      $app = App::i();
      $app->hook('template(opportunity.<<single|edit>>.tab-about--highlighted-message):end', function() use($app){
         // $opportunity = self::getBaseOpportunity();

         // $phases = self::getPhases($opportunity);

         // $app->view->part('widget-opportunity-phases', ['opportunity' => $opportunity, 'phases' => $phases]);
         $app->view->part('widget-opportunity-phases2', ['teste' => 'teste']);
     });

   }

   function register () {}
}