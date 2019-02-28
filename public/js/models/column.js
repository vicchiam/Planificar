class Column{

	constructor(parent,className,data){
		this.parent=parent;
		this.className=className;
		this.data=data;		
	}

	render(type){
		if(type=="date"){
			return this.createDate();
		}
	}

	createDate(){
		var td=$("<td/>",{
			class: this.className,
			html: this.data.fecha
		});
		return td;
	}

}