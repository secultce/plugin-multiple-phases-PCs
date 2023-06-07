<?php 

namespace PrestacaoDeContas\Controllers;

use MapasCulturais\App;

class PrestacaoDeContasController extends \MapasCulturais\Controller {
    function GET_itens () {
        $this->requireAuthentication();
        $this->render('items');
    }

    function POST_total () {
        // dump($this->data);
        $app = App::i();

        //BUSCANDO INSTANCIA REQUISITADA
        $entity = $app->repo('Opportunity')->find($this->data['entidade']);
        $total = 0;
        //se o parent nao for null é por que é uma instancia PAI
        if (is_null($entity->parent)) {
           
            $child = $app->repo('Opportunity')->findBy([
                'parent' => $entity->id,
             ]);

            //  dump(count($child));
            $total = count($child);
        }else{
            //É UM FILHO           
            $child = $app->repo('Opportunity')->findBy([
                'parent' => $entity->parent->id,
             ]);

            //  dump(count($child));
             $total = count($child);
        }
        // dump($entity->id);
        // die;

        $parent = $app->repo('OpportunityMeta')->findBy([
            'owner' => $entity->id,
         ]);
        $countChild = 0;
        foreach ($parent as $childrenValue) {
            if ($childrenValue->key == 'count_total_pc') {
                $countChild = $childrenValue->value;
            }
        }
        // dump($countChild, $total);
            //  dump($parent);



        //  dump($parent);
        // die;
        $this->json(['message' => $total, 'status' => 400]);
        // $parent = $app->repo('OpportunityMeta')->findBy([
        //     'owner' => $this->data['entidade'],
        //  ]);

         //se tem filhos
        //  $child = $app->repo('Opportunity')->findBy([
        //     'parent' => $parent[0],
        //  ]);
        //  dump(count($child));
        //  dump(count($parent));
    //    $this->json(['message' => 1]);

    }
}

