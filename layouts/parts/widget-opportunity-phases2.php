<?php 
use MapasCulturais\i;

?>
<p> Valor atual do IsLastPhase: <?php echo $valueIsLastPhase; ?> </p>
<div class="opportunity-phases clear">
<a class="btn btn-default add" ng-click="editbox.open('new-opportunity-phase', $event)"  rel='noopener noreferrer'>
                        <?php i::_e("ADICIONAR NOVA FASE plugin");?>
                    </a>
</div>