class Matrix{
	
	constructor(parent, className, data){
		this.numCols=12;		
		this.parent=parent;
		this.className=className;
		this.data=data;
		this.blocks=[];
		this.createBlocks();
	}

	createBlocks(){
		for(var i=0;i<this.data.length;i++){
			var item=this.data[i];			
			var block=new Block("papa","papa-class",item,this.numCols);
			this.blocks.push(block);
		}
	}

	render(){
		$("#"+this.parent).html();
		var table=this.createItem();		
		$("#"+this.parent).html(table);
	}

	createItem(){		
		var trs=this.createItemBlocks();		
		var table=$("<table/>",{
			class:this.className
		});
		table.html(trs);
		return table;
	}

	createItemBlocks(){
		var trs=[];
		for(var i=0;i<this.blocks.length;i++){
			var aux=this.blocks[i].render(0);
			trs=trs.concat(aux);
			var aux=this.blocks[i].render(1);
			trs=trs.concat(aux);
		}
		return trs;
	}

}