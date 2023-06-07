<?php

$this->enqueueScript(
    'app', // grupo de scripts
    'ng-module-prestacaodecontas',  // nome do script
    'js/prestacaodecontas/ng.module.prestacaodecontas.js', // arquivo do script
    [] // dependências do script
);
?>

<div ng-app="module-prestacaodecontas" class="main-content">
    <label >
        <div class = "registration-fieldset">
            <h4>Configuração de Prestação de Contas</h4>
            <p class="registration-help">É possível criar opções para os proponentes escolherem a quantidade de fases de "Prestação de Contas". Se não desejar utilizar este recurso, automaticamente o campo assumirá apenas uma fase.</p>
            <p>
                <span class="label">Número de prestações de contas:</span>
                <span class="js-editable" data-edit="count_total_pc" data-original-title="Total de PC" data-emptytext="">
                    <?php echo $entity->count_total_pc; ?>
                </span>
            </p>

            <?php use MapasCulturais\i;?>
            <div class="opportunity-phases clear">
                <a class="btn btn-default add" ng-click="editbox.open('id-da-caixa', $event)"  rel='noopener noreferrer'>
                    <?php i::_e("ADICIONAR NOVAS PRESTAÇÕES DE CONTAS");?>
                </a>
            </div>
        </div>
    </label>
 <div ng-controlle="ItemController">
 <h1>Lista de itens</h1>
  <ul>
    <li ng-repeat="item in data.items">{{item.title}} - <a>remover</a></li>
  </ul>
 </div>
</div>

