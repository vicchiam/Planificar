function Init(){
	loadCodes();

	$("#centro").change(function(){
		loadCodes();
	})

	$("#searchCode").click(function(){
		alert("Buscar");
	});

}

function loadCodes(){
	var centro=$("#centro").val();

	$.post( "php/repartidor.php", { operacion:"getCodes", centro: centro, tipo:"1", oculto:0} ,function(data) {
		$("#codes").html("");
		$.each(data, function( index, value ) {
			
			var item=createCode(index, value);
			$("#codes").append(item);

			$(".code").click(function(){
				$(".active").removeClass("active");
				$(this).addClass("active");
			});

		});
	},"json");
}

function createCode(index,value){
	var active="";
	if(index==0)
		active=" active ";
	var item="<li class='list-group-item text-truncate pointer "+active+" code' data-code='"+value.codigo+"' data-orden='"+value.orden+"' title='"+value.descripcion+"'>";
	item+=value.codigo+"-"+value.descripcion;
	item+="</li>";
	return item;
}