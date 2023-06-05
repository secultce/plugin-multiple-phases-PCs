<label >
    <div class = "registration-fieldset">
        <h4>Configuração de Prestação de Contas</h4>
        <p class="registration-help">É possível criar opções para os proponentes escolherem a quantidade de fases de "Prestação de Contas". Se não desejar utilizar este recurso, automaticamente o campo assumirá apenas uma fase.</p>
        <p>
            <span class="label">Número de prestações de contas:</span>
            <span class="js-editable" data-edit="count_total_pc" data-original-title="Total de pC" data-emptytext="">
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
