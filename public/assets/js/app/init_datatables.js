$(document).ready(function (){
    if($('#conge_table').length){
        $("#conge_table").DataTable({
            language:{
                searchPlaceholder:"Recherche agent en congé...",
                sSearch:"",
            },
        });
    }
   if( $('#agent_table').length){
        $("#agent_table").DataTable({
            language:{
                searchPlaceholder:"Recherche agent...",
                sSearch:"",
            },
        });
    }


});
