<?php
namespace PrestacaoDeContas;

use MapasCulturais\App;
use MapasCulturais\Definitions;

use function MapasCulturais\Controllers\dump;

class Plugin extends \MapasCulturais\Plugin
{
    public function _init()
    {
        $app = App::i();
        $app->hook('template(opportunity.<<single|edit>>.tab-about--highlighted-message):end', function () use ($app) {

            $valueIsLastPhase = 0;
        
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
                }
            } // fim if PAI
            $app->view->part('widget-opportunity-phases2', ['valueIsLastPhase' => $valueIsLastPhase]);
        });

        $app->hook('template(opportunity.edit.tab-about):begin', function () use ($app, &$valueIsLastPhase) {
            $countIsPhases = 0;
            $count_total_pc = 0;
            $parentId = null;

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

            if ($countIsPhases <= $count_total_pc) {
                $entity = $app->view->controller->requestedEntity;
            
                //pegar id do pai
                if (!is_null($this->data['entity']->parent)) {
                    $parentId = $this->data['entity']->parent->id;
                }

                $phase_up = $app->repo('OpportunityMeta')->findOneBy(['owner' => $parentId, 'key' => 'isLastPhase']);
                if ($phase_up) {
                    $phase_up->setValue(0);
                    $app->em->persist($phase_up);
                    $app->em->flush();
                }

               /*  if ($countIsPhases == $count_total_pc) {
                  echo("Passei aqui");
                  $phase_up->setValue(1);
                  $app->em->persist($phase_up);
                  $app->em->flush();
               } */

                $app->view->part('widget-accountability-phases', ['entity' => $entity]);

            }
        });
    }


    public function register()
    {
        $app = App::i();
        

        $conf =
            [
            'label' => \MapasCulturais\i::__('Modelo de selo'), //alterar
            'type' => 'select',
            'options' =>
            [
                '' => \MapasCulturais\i::__("Selecione o número de fases"), //alterar
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4,
                5 => 5,
            ],
        ];

        $def_opp = new Definitions\Metadata('count_total_pc', $conf);
        $app->registerMetadata($def_opp, 'MapasCulturais\Entities\Opportunity');

    }
}
