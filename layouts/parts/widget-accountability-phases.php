
<?php use MapasCulturais\i;?>

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
                    <option value="1">01</option>
                    <option value="2">02</option>
                    <option value="3">03</option>
                    <option value="4">04</option>
                    <option value="5">05</option>
                </select>
            </p>
            <div class="opportunity-phases clear">
                <a 
                class="btn btn-primary add"
                title="Click para adicionar uma nova fase de prestação de contas"
                ng-click="editbox.open('btn-new-pc', $event)"  rel='noopener noreferrer'>
                    <?php i::_e("ADICIONAR NOVAS PRESTAÇÕES DE CONTAS");?>
                </a>
            </div>
        </div>
    </label>
</div>

<div ng-controller="OpportunityPhasesController">
    <edit-box 
        id="btn-new-pc" 
        position="right" 
        title="Título da caixa" 
        spinner-condition="data.processando"
        cancel-label="Fechar" 
        submit-label="Enviar"
        on-open="" 
        on-cancel="newPhaseEditBoxCancel"
        on-submit="newPhaseEditBoxSubmit"
        spinner-condition=data.spinner
        close-on-cancel='true'>
        <div>
            <label>
                <li class="evaluation-methods--item">
                    <input type="radio" id="onlyAccountabilityPhase" name="accountability_phase" value="accountability" ng-change="data.step = 'accountability'" ng-model="newPhasePostData.evaluationMethod">
                        <?php i::_e('Prestação de Contas'); ?>
                    <p class="evaluation-methods--name">
                        <?php i::_e('Assinale caso a oportunidade exija prestação de contas'); ?>
                    </p>
                </li>
            </label>
        </div>
    </edit-box>
</div>

