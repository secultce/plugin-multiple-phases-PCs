<?php

namespace PrestacaoDeContas;

use MapasCulturais\App;
use MapasCulturais\Definitions;
use MapasCulturais\Entities\OpportunityMeta;

class Plugin extends \MapasCulturais\Plugin
{
    public function _init()
    {
        $app = App::i();
        //Edição da Oportunidade
        $app->hook('template(opportunity.edit.tab-about):begin', function () use ($app) {
        $countIsPhases = 0; //Total de Fases que são PC

            self::comparacao($this->data['entity'], $app);

            if (!is_null($this->data['entity']->parent)) {
                if (($this->data['entity']->parent->id)) {
                    $idsFilhos = [];
                    $opp = $app->repo('Opportunity')->findBy([
                    'parent' => $this->data['entity']->parent->id,
                    ]);
            
                    foreach ($opp as $key => $value) {
                        array_push($idsFilhos, $value->id);
                    }

                    sort($idsFilhos);

                    //contar as fases de prestação de contas
                    foreach ($idsFilhos as $valChild) {
                        $child = $app->repo('OpportunityMeta')->findBy([
                        'owner' => $valChild,
                        ]);

                        foreach ($child as $ChildrenValue) {
                        if ($ChildrenValue->key == 'isOpportunityPhase') {
                            $countIsPhases++;
                        }
                        }
                    }
                }
            }

            $entity = $app->view->controller->requestedEntity;

            if($countIsPhases < 5){
                $app->view->part('widget-accountability-phases', ['entity' => $entity]);
            }
           
            $app->view->enqueueScript('app', 'prestacaodecontas', 'js/prestacaodecontas/prestacaodecontas.js');
        });

        //HOOK para realizar alteração apos o salvar
        $app->hook("entity(Opportunity).update:after", function () use ($app) {
            $entity = $this;
            self::comparacao($entity, $app);
        });
    }

    /**
     * Realiza uma comparação com o count_total_pc e o total de filhos
     * Caso count_total_pc seja maior que o total de filhos, então altera o isLastPhase para 1
     *
     * @param [object] $entity
     * @param [object] $app
     */

    public static function comparacao($entity, $app)
    {
        $meta = $entity->getMetadata();
        $opp = $app->repo('Opportunity')->findBy([
            'parent' => $entity->id,
        ]);

        if ((int) $meta['count_total_pc'] != count($opp)) {
            self::upLastPhase($app, null, $meta['count_total_pc'], $entity, 0);
        } else {
            self::upLastPhase($app, null, $meta['count_total_pc'], $entity, 1);
        }
    }

    /**
     * Altera o valor do isLastPhase para 0 pu 1 dependendo da necessidade
     *
     * @param [Object] $app
     * @param [integer] $countIsPhases
     * @param [integer] $count_total_pc
     * @param [Object] $parent
     * @param [integer] $valueUpdate
     * @return void
     */
    protected static function upLastPhase($app, $countIsPhases = null, $count_total_pc, $parent, $valueUpdate)
    {
        //pegar id do pai
        if ($countIsPhases < $count_total_pc) {
            if (!is_null($parent)) {
                $phase_up = $app->repo('OpportunityMeta')->findOneBy(['owner' => $parent->id, 'key' => 'isLastPhase']);
                if ($phase_up) {
                    $phase_up->setValue($valueUpdate);
                    $app->em->persist($phase_up);
                    $app->em->flush();
                }
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
            'id' => 'selectCountPcback',
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
