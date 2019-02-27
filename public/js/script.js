var list=null;
var selectedCodes=null;

function init(){
	loadCodes();
	changeVisibilityBtn();

	$("#centro").change(function(){
		renderList();		
	});

	$("#familia").change(function(){
		renderList();		
	});

	$("#oculto").change(function(){
		changeVisibilityBtn();
		renderList();		
	});

	$("#searchCode").on('input',function(){				
		renderList();
	});

	$("#btn-lock-unlock").click(function(){
		var value=$(this).data("lock");
		var src="";
		if(value==0){
			value=1;
			src="assets/lock-24px.svg";
		}
		else{
			value=0;
			src="assets/unlock-24px.svg";
			selectedCodes.clean();
			$(".selected-item").remove();
		}
		$(this).data("lock",value);
		$(this).attr("src",src);
	});

	$("#btn-visibility").click(function(){
		var code=$(".active:first").data("code");
		var item=list.getCode(code);
		if(typeof code !== "undefined"){
			changeVisibility(item);
		}
	});

}

//Carga los datos de la bbdd
function loadCodes(){
	$.post( "php/repartidor.php", { operacion:"getCodes"} ,function(data) {
		selectedCodes=new SelectedCodes("list-codes","item-selected");
		list=new ListCodes("list-codes","item-code",data);
		list.setClickItem(function(){
			$(".active").removeClass("active");
			$(this).addClass("active");			
			selectedItem(this);
		})		
		renderList();		
		setActiveTop();
	},"json");

}

function setActiveTop(){
	$(".item-code:first").addClass("active");
}

//Dibuja la lista de codigos
function renderList(){
	var centro=$("#centro").val();
	var oculto=$("#oculto").val();
	var text=$("#searchCode").val();
	list.render(centro,1,oculto,text);
	selectedCodes.render();
	$("#panel-list-codes").scrollTop(0);		
}

function selectedItem(element){
	if($("#btn-lock-unlock").data("lock")=="1"){		
		var code=$(element).data("code");
		addSelectedItem(code);
	}	
	else{
		var codes=getSibiling(element);
		loadData(codes);
	}
}

function getSibiling(element){
	var maxData=$("#num-data").val();
	var num=((maxData-1)/2);
	var numPrev=Math.floor(num);
	var numNext=Math.round(num);	

	var codes=[];

	var prev=element;
	var next=element;

	for(var i=(numPrev-1);i>=0;i--){
		prev=$(prev).prev();
		var code=$(prev).data("code");
		codes[i]=code;
	}

	var code=$(element).data("code");
	codes.push(code);

	for(var i=0;i<numNext;i++){
		next=$(next).next();
		var code=$(next).data("code");
		codes.push(code);
	}	
	return codes;
}

//Añade el codigo seleccionado a la lista de seleccionados si esta activo el boton
function addSelectedItem(code){
	var item=list.getCode(code);
	if(item!=false){
		var maxData=$("#num-data").val();
		selectedCodes.add(item, maxData);		
	}
}

function loadData(codes){
	$.post("php/repartidor.php", { operacion:"getData", codes: codes, date: '2019-02-01'} ,function(data) {
		console.log(data);
	});
}

function changeVisibilityBtn(){
	var src="";
	if($("#oculto").val()==0){
		src="assets/visibility-24px.svg";		
	}
	else{
		src="assets/visibility_off-24px.svg";
	}
	$("#btn-visibility").attr("src",src);
}

function changeVisibility(item){
	$.post( "php/repartidor.php", { operacion:"changeVisibility", code: item.codigo} ,function(resp) {
		if(resp=="ok"){
			item.oculto=((item.oculto==0)?1:0);
			$(".active:first").remove();
		}
	});
}
