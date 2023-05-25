<?php
namespace PrestacaoDeContas;
use MapasCulturais\App;

class Plugin extends \MapasCulturais\Plugin {
   function _init () {
      $app = App::i();
      $app->hook('template(opportunity.<<single|edit>>.tab-about--highlighted-message):end', function() use($app){
         // dump($this->data['entity']->parent->id);
         echo self::getNameJota();
         $valueIsLastPhase = 0;
         // dump($this->data['entity']->parent->id);
         if(($this->data['entity']->parent->id)){
            $parent = $app->repo('OpportunityMeta')->findBy([
               'owner' => $this->data['entity']->parent->id
            ]);
            // dump($parent);
            //recebe o valor do isLastPhase
            $valueIsLastPhase = 0;
            foreach ($parent as $itensOpp) {
               if($itensOpp->key == 'isLastPhase'){
                  $valueIsLastPhase = $itensOpp->value;
               }
            }
   
            $idsChildren = [];
            $opp = $app->repo('Opportunity')->findBy([
               'parent' => $this->data['entity']->parent->id
            ]);
            
            // dump($opp);
            foreach ($opp as $key => $value) {
               // dump($value->id);
               // dump($value->isOpportunityPhase);
               array_push($idsChildren, $value->id);
               // dump($value);
               // if($value->key == 'isOpportunityPhase') {
               //    $countIsPhases += $value->isOpportunityPhase;
               // }
                
   
            }
            // dump($value->id);
            
            sort($idsChildren);
            // dump($idsChildren);
            $countIsPhases = 0;
            foreach ($idsChildren as $key => $valChild) {
               // dump($valChild);
               $child = $app->repo('OpportunityMeta')->findBy([
                  'owner' => $valChild
               ]);
               // dump($child);
               foreach ($child as $keyClild => $ChildrenValue) {
                  // dump($ChildrenValue->key);
                  if($ChildrenValue->key == 'isOpportunityPhase')
                  {
   
                     $countIsPhases++;
                  }
               }
               // if($child[$key]->key == 'isOpportunityPhase')
               // {
               //    // dump($child[$key]->key,$value->id );
               //    echo 'é uma pc ' . $value->id;
               // }
            }
            echo $countIsPhases;
            // $class = "MapasCulturais\Entities\OpportunityMeta";
            // $select = "select * from MapasCulturais\Entities\OpportunityMeta where owner in (4260,4261,4262);";
            // $query = $app->em->createQuery($select);
            // $query->getResult();
            // dump($query->getResult());
         }
        




         // foreach ($this->data as $key => $value) {
         //    // dump($value->parent);
         //    dump($value);
         //    // get_object_vars($value);
         // }
         // $opportunity = self::getBaseOpportunity();

         // $phases = self::getPhases($opportunity);

         // $app->view->part('widget-opportunity-phases', ['opportunity' => $opportunity, 'phases' => $phases]);
         $app->view->part('widget-opportunity-phases2', ['valueIsLastPhase' =>  $valueIsLastPhase]);
     });

   }

   function register () {}


   static function getNameJota() {
      echo "Jocelio";
   }
}