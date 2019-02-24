class SelectedCodes{

	constructor(parent,className){
		this.parent=parent;
		this.className=className;
		this.list=[];
	}

	getList(){
		return this.list;
	}

	add(item, max){
		if(!this.exists(item)){
			if(this.list.length>=max)
				this.list=this.list.slice((max-1)*-1);
			this.list.push(item);
			this.render();
		}
	}

	remove(code){
		var aux=[];
		for(var i=0;i<this.list.length;i++){
			if(this.list[i].codigo!=code){
				aux.push(this.list[i]);
			}
		}
		this.list=aux;				
	}

	exists(item){
		for(var i=0;i<this.list.length;i++){
			if(item.codigo==this.list[i].codigo){
				return true;
			}
		}
		return false;
	}

	clean(){
		this.list=[];
		$("."+this.className).remove();
	}

	size(){
		return this.list.length;
	}

	render(){
		for(var i=0;i<this.list.length;i++){
			if(i==0) $("."+this.className).remove();
			var item=this.renderItem(i);
			$("#"+this.parent).prepend(item);
		}
	}

	renderItem(index){
		var self=this;
		var item=this.list[index];
		var li=$("<li/>",{
			class: "list-group-item text-truncate pointer bg-warning "+this.className,
			"data-code": item.codigo,
			"data-order": item.orden,
			title: item.descripcion,
			html: item.codigo+"-"+item.descripcion
		}).click(function(){
			self.remove(item.codigo);
			this.remove();
		});		
		return li;
	}

}