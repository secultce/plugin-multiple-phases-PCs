$(document).ready(function () {

   console.log('Prestacao de contas jquery');
   $("#selectCountPC").hide();

    $("#select_total_count").change(function (e) { 
        e.preventDefault();
        console.log(e.target.value)
        $.ajax({
            type: "post",
            url: MapasCulturais.baseURL + "prestacaodecontas/total",
            data: {valor_escolhido : e.target.value, entidade : MapasCulturais.entity.id},
            dataType: "json",
            success: function (response) {
                console.log({response})
                
                if(response.status == 200)
                {
                    MapasCulturais.Messages.success('Total de Mensagem: ' + response.message);
                }

                MapasCulturais.Messages.error(response.message);
            }
        });
    });


    
    var parent = 0;
    var parent = MapasCulturais.entity.object.parent.id;
    console.log({parent});
    if(parent > 0){
        $("#widget-select-pc").hide();
    }

});