class Block{

	constructor(parent,className,code,data){
		this.parent=parent;
		this.className=className;
		this.code=code;
		this.data=data;
		this.columns=[];
		this.createColumns();
	}

	createColumns(){
		for(var i=0;i<this.data.length;i++){
			var aux=this.data[i];
			var column=new Column("papa","papa-class",aux);
			this.columns.push(column);
		}
	}

}