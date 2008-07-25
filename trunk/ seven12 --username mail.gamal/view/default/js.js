function confirm_rm(id,control){
	if(window.confirm("are you sure you want to delete this entry ?"))
		{
			location.href='?do='+control+'&wt=rm&id='+id;
		}
}
