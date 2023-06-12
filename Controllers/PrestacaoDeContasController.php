<?php 

namespace PrestacaoDeContas\Controllers;

use MapasCulturais\App;

class PrestacaoDeContasController extends \MapasCulturais\Controller {
    function GET_itens () {
        $this->requireAuthentication();
        $this->render('items');
    }

    function POST_total () {
        $totalChild = self::totalCountChildren($this->data['entidade']);
        /**
         * Se o total do banco é igual ao total de filhos, só poderá add mais PC
         */
        if($totalChild['count_total_pc'] == $totalChild['countChild'] && $this->data['valor_escolhido'] > $totalChild['countChild']){
           //validado para o usuario
           $this->json(['message' => 'Pode alterar', 'status' => 200]);
        }elseif($totalChild['count_total_pc'] == $totalChild['countChild'] && $this->data['valor_escolhido'] < $totalChild['countChild']){
            //Mensagem de erro
            $this->json(['message' => 'Não poderá alterar', 'status' => 400]);
        }elseif($totalChild['count_total_pc'] > $totalChild['countChild'] && $this->data['valor_escolhido'] > $totalChild['countChild'])
        {
            $this->json(['message' => 'Outra condicao', 'status' => 400]);
        }
    }
    /**
     * Faz uma verificação de total de filhos de uma op. e o total que foi conf. no banco
     *
     * @param [integer] $idEntity
     * @return array
     */
    public function totalCountChildren($idEntity)
    {
        $app = App::i();
        //BUSCANDO INSTANCIA REQUISITADA
        $entity = $app->repo('Opportunity')->find($idEntity);
        //para registrar o total de filhos
        
        //se o parent nao for null é por que é uma instancia PAI
        $totalChild = self::totalChildren($entity, $app);

        $parent = $app->repo('OpportunityMeta')->findBy([
            'owner' => $entity->id,
         ]);

        $count_total_pc = 0;// total que está registrado no banco
        foreach ($parent as $childrenValue) {
            
            if ($childrenValue->key == 'count_total_pc') {
                $count_total_pc = $childrenValue->value;
             }
        }

        return ['countChild' => $totalChild, 'count_total_pc' => $count_total_pc];
    }

    /**
     * Função exclusica para contagem dos filhos
     * @param [object] $entity
     * @param [object] $app
     * @return int
    */
    public function totalChildren($entity, $app)
    {
        $totalChild = 0;
        if (is_null($entity->parent)) {
           
            $child = $app->repo('Opportunity')->findBy([
                'parent' => $entity->id,
             ]);
            $totalChild = count($child);
        }else{
            //É UM FILHO
            $child = $app->repo('Opportunity')->findBy([
                'parent' => $entity->parent->id,
            ]);

            $totalChild = count($child);
        }
        return $totalChild;
    }
}

