<?php

// $this->enqueueScript(
//     'app', // grupo de scripts
//     'ng-module-prestacaodecontas',  // nome do script
//     'js/prestacaodecontas/ng.module.prestacaodecontas.js', // arquivo do script
//     [] // dependências do script
// );
?>

<div id="widget-select-pc">
    <label >
        <div class = "registration-fieldset">
            <h4>Configuração de Prestação de Contas</h4>
            <p class="registration-help">É possível criar opções para os proponentes escolherem a quantidade de fases de "Prestação de Contas". Se não desejar utilizar este recurso, automaticamente o campo assumirá apenas uma fase.</p>
            <p>
                <span class="label">Número de prestações de contas:</span>
                <span class="js-editable" data-edit="count_total_pc" data-original-title="Total de PC" data-emptytext=""
                id="selectCountPC">
                    <?php echo $entity->count_total_pc; ?>
                </span>
                <select name="select_total_count" id="select_total_count">
                    <option value="01">01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                </select>
            </p>

            <?php use MapasCulturais\i;?>
            <div class="opportunity-phases clear">
                <a class="btn btn-default add" ng-click="editbox.open('id-da-caixa', $event)"  rel='noopener noreferrer'>
                    <?php i::_e("ADICIONAR NOVAS PRESTAÇÕES DE CONTAS");?>
                </a>
            </div>
        </div>
    </label>

 <div>
 <h1>Lista de itens</h1>
  <ul>
    <li id="item_um_pc">Item - <a>remover</a></li>
  </ul>
 </div>
</div>

