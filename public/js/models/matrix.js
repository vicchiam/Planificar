class Matrix{
	
	constructor(parent, className, data){
		this.parent=parent;
		this.className=className;
		this.data=data;
		this.blocks=[];
		this.createBlocks();
	}

	createBlocks(){
		for(var i=0;i<this.data.length;i++){
			var aux=this.data[i];
			var block=new Block("papa","papa-class",aux.codigo,aux.fechas);
			this.blocks.push(block);
		}
	}

}