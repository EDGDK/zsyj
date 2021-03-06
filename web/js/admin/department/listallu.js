//打开添加页面
function openaddus(id,departmentName) {
 $.dialog({id:'department_addu'}).close();
 var url = addusUrl+'&id='+id+'&departmentName='+departmentName;
 $.dialog.open(url,{
 title: '添加'+departmentName+'员工',
 width: 1100,
 height:400,
 lock: true,
 border: false,
 id: 'department_addu',
 drag:true
 });
 }

//详情
function detail(userId,userName){
 $.dialog({id:'user_detail'}).close();
 var url = detailUrl+'&id='+userId;
 $.dialog.open(url,{
  title: '员工信息--'+userName,
  width: 700,
  height:300,
  lock: true,
  border: false,
  id: 'user_detail',
  drag:true
 });
}

//从部门里移除单个员工
function deleteud(id,departmentName,departmentId){
 var paraStr = 'id='+id;
 if (confirm('您确定要把他\她从此部门移除吗？')) {
  $.ajax({
   url: deludUrl,
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
    art.dialog.parent.location.href = adduUrl+'&id='+departmentId+'&departmentName='+departmentName;
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

//从部门里移除多个员工
function deluds(departmentName,departmentId){
 var len=$("input[name='id']:checked").size();
 var ids='';
 $("input[name='id']:checked").each(function(i, n){
  if(i<len-1){
   ids += $(n).val() + '-';
  }else{
   ids += $(n).val();
  }
 });
 if(ids=='') {
  window.top.art.dialog({content:'请选择至少一个员工',lock:true,width:'200',height:'50',border: false,time:1.5},function(){});
  return false;
 }else{
  var paraStr = 'ids='+ids;
  $.ajax({
   url: deludsUrl,
   type: "post",
   dataType: "text",
   data:paraStr ,
   async: "false",
   success: function (data) {
    window.top.art.dialog({
     content: '移除成功！',
     lock: true,
     width: 250,
     height: 80,
     border: false,
     time: 2
    }, function () {
    });
    art.dialog.parent.location.href = adduUrl+'&id='+departmentId+'&departmentName='+departmentName;
   },
   error:function(data){
    window.top.art.dialog({
     content: '移除失败！',
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