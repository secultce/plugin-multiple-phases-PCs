<?php
namespace PrestacaoDeContas;

use MapasCulturais\App;
use MapasCulturais\Controllers\Opportunity;
use MapasCulturais\Definitions;
use MapasCulturais\Entities\OpportunityMeta;

use function MapasCulturais\Controllers\dump;

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

        $app->hook('template(opportunity.edit.tab-about):begin', function () use ($app, &$valueIsLastPhase) {
            $countIsPhases = 0;
            $count_total_pc = 0;
            $parentId = null;
            $oldValue = false;
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

                    //Verifica se já existe o campo que indica a quantidade de fases para que possa ser atualizada
                    foreach ($idsFilhos as $key => $valChild) {
                        $parent = $app->repo('OpportunityMeta')->findBy([
                            'owner' => $this->data['entity']->parent->id,
                        ]);

                        foreach ($parent as $itensOpp) {
                            if ($itensOpp->key == 'oldValue') {
                                $oldValue = true;
                            }
                        }
                    }
                }
            }


            $entity = $app->view->controller->requestedEntity;
            $app->view->part('widget-accountability-phases', ['entity' => $entity]);

             if ($countIsPhases < $count_total_pc) {
                /*  $entity = $app->view->controller->requestedEntity; */
            
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
            }
            
             //adicionando novos metadados no banco ($countIsPhases)
             //Só deve ser executada uma unica vez
             //depois só atualiza os valores
            /*  if (!is_null($this->data['entity']->parent)) {
                $parentId = $this->data['entity']->parent->id;

                $op = $app->repo('Opportunity')->find($parentId);
                $new = new OpportunityMeta;
                $new->key = 'countIsPhases';
                $new->value = $countIsPhases;
                $new->owner = $op;
                $app->em->persist($new);
                $app->em->flush();
            
            } */
        
            //pegar valor atualizado do novo Count_total_pc
            //para permitir atualizar o numero de fases.

            //se count for atualizado, verifica se valor é countAntigo>countAnterior e atualiza o islastphase = 0;
           /*  $countUp = $app->repo('OpportunityMeta')->findOneBy(['owner' => $parentId, 'key' => 'count_total_pc']);
            if($count_Antigo < $count_total_pc){
                $countUp->setValue(0);
                $app->em->persist($countUp);
                $app->em->flush();
            } */

        });
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

    }
}
