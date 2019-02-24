class ListCodes{

	constructor(parent,className,data){
		this.parent=parent;
		this.className=className;
		this.list=data;
		this.clickItem=null;
	}

	getList(){
		return this.list;
	}

	size(){
		return this.list.length;
	}

	getCode(code){
		for(var i=0;this.list.length;i++){
			var item=this.list[i];
			if(item.codigo==code)
				return item;
		}
		return false;
	}	

	setClickItem(callback){
		this.clickItem=callback;
	}

	render(centro,tipo,oculto,text,type){
		$("#"+this.parent).html("");
		for(var i=0;i<this.list.length;i++){
			var item=this.list[i];
			var match=false;
			var matchCentro=true;
			var matchText=true;

			if(item.tipo==tipo && item.oculto==oculto){			
				match=true;	
				if(centro>0 && item.centro!=centro){
					matchCentro=false;
				}				
				if(text.length>1){
					var expression=new RegExp(text, 'i');
					var aux=item.codigo+"-"+item.descripcion;
					if(!aux.match(expression))
						match=false;									
				}				
			}
			if(match && matchCentro && matchText){
				var item=this.renderItem(i,this.clickItem);
				$("#"+this.parent).append(item);
			}
		}
	}

	renderItem(index, callback){
		var item=this.list[index];
		var li=$("<li/>",{
			class: "list-group-item text-truncate pointer "+this.className,
			"data-code": item.codigo,
			"data-order": item.orden,
			title: item.descripcion,
			html: item.codigo+"-"+item.descripcion
		}).click(callback);		
		return li;
	}

}