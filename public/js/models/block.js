class Block{

	constructor(parent,className,data){
		this.parent=parent;
		this.className=className;
		this.code=data.codigo;
		this.descripcion=data.descripcion;
		this.data=data.fechas;		
		this.columns=[];
		this.createColumns();
	}

	createColumns(){
		for(var i=0;i<this.data.length;i++){
			var item=this.data[i];
			var column=new Column("row","cell",item);
			this.columns.push(column);
		}
	}	

	renderTitle(){		
		var td=$("<td/>",{
			class:"data-title text-center",
			colspan: this.data.length,
			html: this.code+"-"+this.descripcion
		});
		var tr=$("<tr/>",{
			class:"",
			html:td
		});
		return tr;
	}

	render(){
		
	}
	

}