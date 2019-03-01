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
		console.log(trs);
		var table=$("<table/>",{
			class:this.className
		});
		table.html(trs);
		return table;
	}

	createItemBlocks(){
		var trsTitle=[];		
		for(var i=0;i<this.blocks.length;i++){
			var block=this.blocks[i].renderTitle();				
			trsTitle.push(block);
		}
		return trsTitle;
	}

}