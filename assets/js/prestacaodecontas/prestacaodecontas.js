$(document).ready(function () {
   //Iniciando como x-editable
   $("#selectCountPC").editable().hide()
   //Oculta o select para não ser mostrado enquanto a página está carregando
   $("#select_total_count").hide();
    //quando o campo do select é alterado
    $("#select_total_count").change(function (e) { 
        e.preventDefault();
        //INSTANCIA DO X-EDITABLE
        var selectCountPC = $("#selectCountPC").editable();
        //REALIZANDO A REQUISIÇÃO PARA BACKEND
        $.ajax({
            type: "post",
            url: MapasCulturais.baseURL + "prestacaodecontas/total",
            data: {valor_escolhido : e.target.value, entidade : MapasCulturais.entity.id},
            dataType: "json",
            success: function (response) {
                //SE FOR POSSIVEL FAZER A ALTERAÇÃO O CAMPO DO META_DATA RECEBE O VALOR ESCOLHIDO
                if(response.status == 200)
                {
                    MapasCulturais.Messages.success(response.message);
                    selectCountPC.editable('setValue', e.target.value);
                    selectCountPC.editable('toggleDisabled');
                }else{
                    MapasCulturais.Messages.error(response.message);
                }
            }
        });
        var selectCountPC = $("#selectCountPC").editable();
        //REALIZANDO A REQUISIÇÃO PARA BACKEND
       
    });
    //PARA AS OPORTUNIDADES QUE SÃO FILHAS
    var parent = getIdEntity();
    if(parent > 0){
        $("#widget-select-pc").hide();
    }
    //BUSCA O TOTAL DE PRESTAÇÃO DE CONTAS QUE ESTÁ NO BANCO
    getTotalPc();

    //Quando o select for mudado e tirado seu foco
    $("#select_total_count").blur(function (e) {
        val_count_pc = $("#select_total_count").val();
        var testeData =  {
            valueSelect : val_count_pc, entidade: MapasCulturais.entity.id
        };
        getNumber(testeData)
    })
});

//Função usada na controller para mudança do isLastPhase
function getNumber(tData)
{
    $.ajax({
        type: "GET",
        url: MapasCulturais.baseURL + "prestacaodecontas/changeNumber/",
        data: {valor_escolhido : e.target.value, entidade : MapasCulturais.entity.id},
        data: {tData,},
        dataType: "json",
        success: function (response) {
            console.log({response})
        }
    });
}

//BUSCA O TOTAL DE PRESTAÇÃO DE CONTAS QUE ESTÁ NO BANCO
//E SETA O VALOR COMO PADRÃO DENTRO DO SELECT
function getTotalPc()
{
    var idEntity = MapasCulturais.entity.id;
    $.ajax({
        type: "GET",
        url: MapasCulturais.baseURL + "prestacaodecontas/gettotpc",
        data: {entity: idEntity},
        dataType: "json",
        success: function (response) {
            tot = response.message;
            $("#select_total_count").show();
            $('#select_total_count option[value='+tot+']').attr('selected','selected');
        }
    });
}

/**
 * PARA VERIFICAÇÃO SE A OPORTUNIDADE É PAI OU FILHA 
 * @returns 
 */
function getIdEntity()
{
    var parent = 0;
    if(typeof MapasCulturais.entity.object.parent === 'object' && MapasCulturais.entity.object.parent !== null) {
        var parent = MapasCulturais.entity.object.parent.id;
    };
    return parent;
}