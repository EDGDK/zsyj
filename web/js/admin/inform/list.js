// 加载字典信息
$(document).ready(function(){
	generateDict('DICT_INFORM_TYPE','informType','通知类别');
})

//查询
function search(){
	if(str_is_null($('#title').val()) && str_is_null($('#informType').val()) && str_is_null($('#_senderDateTime').val()) && str_is_null($('#senderDateTime_').val())) {
		alert('至少有一个查询条件不为空');
		return ;
	}
	var paraStr = "&title="+$('#title').val()+"&informType="+$('#informType').val()+"&_senderDateTime="+$('#_senderDateTime').val()+"&senderDateTime_="+$('#senderDateTime_').val();
	$('#iframeId').attr('src',listallUrl+paraStr);
}
