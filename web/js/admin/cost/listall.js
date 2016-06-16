//打开修改页面
function openedit(id,materialname) {
	$.dialog({id:'materialcost_update'}).close();
	var url = editUrl + '&id='+id;
	$.dialog.open(url,{
		title: '修改消耗记录--'+materialname,
		width: 500,
		height:300,
		lock: true,
		border: false,
		id: 'materialcost_update',
		drag:true
	});

}

//多选删除
function delopt() {
	var len = $("input[name='id']:checked").size();
	var ids = '';
	$("input[name='id']:checked").each(function(i, n){
		if(i<len-1){
			ids += $(n).val() + '-';
		}else{
			ids += $(n).val();
		}
	});
	if(ids=='') {
		window.top.art.dialog({content:'请选择至少一条消耗记录',lock:true,width:'200',height:'50',border: false,time:1.5},function(){});
		return;
	} else {
	var paraStr = 'ids='+ids;
		$.ajax({
			url: delallUrl,
			type: "post",
			dataType: "text",
			data:paraStr,
			async: "false",
			success: function (data) {
				window.top.art.dialog({
					content: '删除成功！',
					lock: true,
					width: 250,
					height: 80,
					border: false,
					time: 2
				}, function () {
				});
				$('#pageForm').submit();
			},
			error:function(data){
				window.top.art.dialog({
					content: '删除失败！',
					lock: true,
					width: 250,
					height: 80,
					border: false,
					time: 2
				}, function () {
				});
			}
		});
	}
}

//删除一个
function deleteCost(id) {
	var paraStr = "";
	paraStr = "id=" + id;
	if (confirm('您确定要删除这项消耗记录吗？')){
		$.ajax({
			url: delUrl,
			type: "post",
			dataType: "text",
			data:paraStr ,
			async: "false",
			success: function (data) {
				window.top.art.dialog({
					content: '删除成功！',
					lock: true,
					width: 250,
					height: 80,
					border: false,
					time: 2
				}, function () {
				});
				$('#pageForm').submit();
			},
			error:function(data){
				window.top.art.dialog({
					content: '删除失败！',
					lock: true,
					width: 250,
					height: 80,
					border: false,
					time: 2
				}, function () {
				});
			}
		});
	}
}

//详情
function detail(id,materialName){
	$.dialog({id:'materialcost_detail'}).close();
	var url = detailUrl+'&id='+id;
	$.dialog.open(url,{
		title: '原材料消耗信息--'+materialName,
		width: 700,
		height:300,
		lock: true,
		border: false,
		id: 'materialcost_detail',
		drag:true
	});
}