function confirm_rm(id,control){
	if(window.confirm("are you sure you want to delete this entry ?")){
			location.href=LINK+"/index/"+control+'/rm/'+id;
		}
}
function confirm_redir(url,message){
	if(window.confirm("clicking OK will confirm that you want to \n" + message + " \n SURE ?")){
			location.href=url;
		}
}