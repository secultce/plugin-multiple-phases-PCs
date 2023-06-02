<?php 
use MapasCulturais\i;

?>
    <div class="opportunity-phases clear">
    <a class="btn btn-default add" ng-click="editbox.open('id-da-caixa', $event)"  rel='noopener noreferrer'>
        <?php i::_e("ADICIONAR NOVAS PRESTAÇÕES DE CONTAS");?>
    </a>
</div>



<div ng-controller="OpportunityPhasesController">
    <edit-box 
        id="id-da-caixa" 
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