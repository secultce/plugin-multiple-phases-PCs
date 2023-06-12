<?php

namespace PrestacaoDeContas;

use MapasCulturais\App;
use MapasCulturais\Definitions;

class Plugin extends \MapasCulturais\Plugin
{
   public function _init()
   {
      $app = App::i();
      $app->hook('template(opportunity.<<single|edit>>.tab-about--highlighted-message):end', function () use ($app) {

         $valueIsLastPhase = 0;
         $valueCount = 0;


         if (!is_null($this->data['entity']->parent)) {
            if (($this->data['entity']->parent->id)) {

               $parent = $app->repo('OpportunityMeta')->findBy([
                  'owner' => $this->data['entity']->parent->id,
               ]);

               //recebe o valor do isLastPhase
               foreach ($parent as $itensOpp) {
                  if ($itensOpp->key == 'isLastPhase') {
                     $valueIsLastPhase = $itensOpp->value;
                  }
               }

               //pegar valor do count
               foreach ($parent as $itensOpp) {
                  if ($itensOpp->key == 'count_total_pc') {
                     $valueCount = $itensOpp->value;
                  }
               }
            }
         } // fim if PAI

         $app->view->part('widget-opportunity-phases2', ['valueIsLastPhase' => $valueIsLastPhase]);
      });

      //Edição da Oportunidade
      $app->hook('template(opportunity.edit.tab-about):begin', function () use ($app, &$valueIsLastPhase) {
         $countIsPhases = 0; //Total de Oportunidades que são PC
         $count_total_pc = 0; // total registrado na configuração da oportunidade

         $oldValue = 0; //

         /*   $count_Antigo =  $count_total_pc; */
         if (!is_null($this->data['entity']->parent)) {

            if (($this->data['entity']->parent->id)) {
               $parent = $app->repo('OpportunityMeta')->findBy([
                  'owner' => $this->data['entity']->parent->id,
               ]);

               //Quantidade de prestações de conta
               $idsFilhos = [];
               $opp = $app->repo('Opportunity')->findBy([
                  'parent' => $this->data['entity']->parent->id,
               ]);

               foreach ($opp as $key => $value) {
                  array_push($idsFilhos, $value->id);
               }

               sort($idsFilhos);

               //contar as fases de prestação de contas
               foreach ($idsFilhos as $key => $valChild) {
                  // dump($valChild);
                  $child = $app->repo('OpportunityMeta')->findBy([
                     'owner' => $valChild,
                  ]);

                  foreach ($child as $ChildrenValue) {
                     if ($ChildrenValue->key == 'isOpportunityPhase') {
                        $countIsPhases++;
                     }
                  }
               }

               //pega o valor total de fases 
               foreach ($idsFilhos as $key => $valChild) {
                  $parent = $app->repo('OpportunityMeta')->findBy([
                     'owner' => $this->data['entity']->parent->id,
                  ]);

                  foreach ($parent as $itensOpp) {
                     if ($itensOpp->key == 'count_total_pc') {
                        $count_total_pc = $itensOpp->value;
                     }
                  }
               }
            }
         }

         $entity = $app->view->controller->requestedEntity;
         $app->view->enqueueScript('app', 'prestacaodecontas', 'js/prestacaodecontas/prestacaodecontas.js');
         $app->view->part('widget-accountability-phases', ['entity' => $entity]);

         self::upLastPhase($app, $countIsPhases, $count_total_pc, $this->data['entity']->parent);
      });

   }

   /**
    * Altera o valor do isLastPhase para criação de uma nova fase
    *
    * @param [Object] $app
    * @param [integer] $countIsPhases
    * @param [integer] $count_total_pc
    * @param [Object] $parent
    * @return void
    */
   static protected function upLastPhase($app, $countIsPhases, $count_total_pc, $parent)
   {
      $parentId = null;
      if ($countIsPhases < $count_total_pc) {

         //pegar id do pai
         if (!is_null($parent)) {
            $parentId = $parent->id;
         }

         $phase_up = $app->repo('OpportunityMeta')->findOneBy(['owner' => $parentId, 'key' => 'isLastPhase']);
         if ($phase_up) {
            $phase_up->setValue(0);
            $app->em->persist($phase_up);
            $app->em->flush();
         }
      }
   }

   public function register()
   {
      $app = App::i();
      $conf =
         [
            'label' => \MapasCulturais\i::__('Quantidade de fases de prestações de conta'), //alterar
            'type' => 'select',
            //torna obrigatorio preencher campo
            'validations' => array(
               'required' => \MapasCulturais\i::__('Indique a quantidade de fases'),
            ),
            'id'=> 'selectCountPcback',
            'options' =>
            [
               2 => \MapasCulturais\i::__(2), //alterar
               1 => 1,
               2 => 2,
               3 => 3,
               4 => 4,
               5 => 5,
            ],
         ];

      $def_opp = new Definitions\Metadata('count_total_pc', $conf);
      $app->registerMetadata($def_opp, 'MapasCulturais\Entities\Opportunity');

      $app->registerController('prestacaodecontas', Controllers\PrestacaoDeContasController::class);
   }
}
