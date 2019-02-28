class Block{

	constructor(parent,className,data,numCols){
		this.parent=parent;
		this.className=className;
		this.code=data.codigo;
		this.descripcion=data.descripcion;
		this.data=data.fechas;
		this.numCols=numCols;
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

	render(row){		
		if(row==0){
			return this.createTitle();
		}
		else if(row==1){
			return this.createDates();
		}
	}

	createTitle(){
		var tr=$("<tr/>");
		var td=$("<td/>",{
			class:"data-title text-center",
			colspan: this.numCols,
			html: this.code+"-"+this.descripcion
		});
		tr.html(td);
		return tr;
	}

	createDates(){
		var tr=$("<tr/>");
		for(var i=0;i<this.columns.length;i++){
			var td=this.columns[i].render("date");
			tr.append(td);			
		}		
		return tr;
	}

	createItem(){
		var title=$("<td/>",{
			class:"data-title"
		});
		var tr=$("<tr/>",{
			"data-code":this.code,
			html:""
		});
		for(var i=0;i<this.columns.length;i++){

		}
	}

}