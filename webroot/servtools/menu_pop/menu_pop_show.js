current_menu_info=new Array();
function menu_pop_show(obj){
		var parentnode = document.getElementById(obj);
		if(typeof(menu_pop_data)=="object")
		{
			if(!document.getElementById("menu_top_yj"))create_menu_top(menu_pop_data,parentnode);
		}
		var str_data = parentnode.getAttributeNode('data').value;
		if(undefined!=str_data)
		{
			//var stl_data = str_data.toString();
			if(current_menu_info.toString()!=str_data.split(":").toString())
			{
				current_menu_info =str_data.split(":");
				//console.log("current_menu_info"+current_menu_info);
				changeGroup();
			}
		}
		expression();
		
}
function menu_pop_changeBg(obj){
	obj.className = 'hover';
}
function menu_pop_removeBg(obj){
	obj.className = '';
}
function menu_pop_hide_group(obj)
{
	var event = getEvent();
	if(isMouseLeaveOrEnter(event,obj)){  
	        parentNode=document.getElementById("menu_top_pop_layer_box");
			parentNode.style.display='none';
	}   
}

function isMouseLeaveOrEnter(e, el) {  
    if (e.type != 'mouseout' && e.type != 'mouseover') return false;  
    var reltg = e.relatedTarget ? e.relatedTarget : e.type == 'mouseout' ? e.toElement:e.fromElement;  
    while (reltg && reltg != el)  
        reltg = reltg.parentNode;  
    return (reltg != el);  
}


function menu_pop_show_group(obj,id)
{
	parentNode=document.getElementById("menu_top_pop_layer_box");
	var menu_top_yj=document.getElementById("menu_top_yj");
	var win=document.getElementById('pop_layer_'+id);
	parentNode.style.display='';
	//win.className = 'menu_top_pop_layer_2';
//console.log("getx:"+menu_getX(menu_top_yj)+"|gety:"+menu_getY(menu_top_yj));
//console.log("menuWHTL:"+menu_top_yj.offsetWidth+"|"+menu_top_yj.offsetHeight+"|"+menu_top_yj.offsetTop+"|"+menu_top_yj.offsetLeft);
//console.log("objWHTL:"+obj.offsetWidth+"|"+obj.offsetHeight+":"+obj.offsetTop+"|"+obj.offsetLeft);
	var t=menu_getY(menu_top_yj)+obj.offsetHeight;
	var l=menu_getX(menu_top_yj)+obj.offsetLeft;
	//if((l+172)>(document.body.offsetWidth))l=menu_top_yj.offsetLeft-menu_top_yj.offsetWidth+9;
	win.style.top = t + 'px';
	win.style.left = l + 'px';
	var divs = parentNode.childNodes;
	for(var i=0;i<divs.length;i++){
		divs[i].style.display='none';
			if(divs[i].id==win.id){
				divs[i].style.display = '';
			}	
	}
}
function create_menu_top(data,parentnode)
{
	var tatal_users=document.createElement("DIV");
	tatal_users.display="none";
	var num=0;
	var pop_str='<div class="ct"><ul style="padding:0 0 0 0"><li onmouseout="menu_pop_removeBg(this)" onmouseover="menu_pop_changeBg(this)" class=""><input type="checkbox" onclick="selectFriend(this)" value="all" name="all" class="checkbox" style="display:none">所有好友<input type="checkbox" onclick="selectFriend(this)" name="friends" class="checkbox"></li>';
	for(var i=0;i<data.length;i++)
	{
		pop_str+='<li onmouseover="menu_pop_changeBg(this) ; menu_pop_show_group(this,'+(num++)+')" id="'+data[i][0]+'" onmouseout="menu_pop_removeBg(this)">'+data[i][1]+'<input  class="menu_top_checkbox" name="group" type="checkbox" value="'+data[i][0]+'" onclick="selectUsers(this)"/></li>';
		var users=document.createElement("div");
		users.id = 'pop_layer_'+i;
		users.className = "menu_top_pop_layer_2";
		//users.onmouseout = function(){menu_pop_hide_group();};
		users.setAttribute( "onMouseOut", "menu_pop_hide_group(this)"); 
		
		var nodes_str="<ul>";
		for(var j=0;j<data[i][2].length;j++)
		{
			nodes_str+= '<li><input name="'+data[i][0]+'" id="menu_pop_user_'+data[i][2][j][0]+'"  value="'+data[i][2][j][0]+'"  onclick="selectGroup(this)" type="checkbox" /><img src="'+menu_pop_data[i][2][j][2]+'" width="20" height="20" alt="" /><span>'+data[i][2][j][1]+'</span></li>';
		}
		nodes_str+="</ul>";
		users.innerHTML = nodes_str;
		tatal_users.appendChild(users);
	}
	//pop_str+='</ul><div><input type="button" class="menu_top_button" value="确定" onclick="expression()"/><input class="menu_top_button" type="button" value="取消" onclick="menu_pop_cancel()"/></div></div><div class="bt"></div>';
	pop_str+='</ul></div>';
	
	if(!document.getElementById("menu_top_yj"))
	{
		var pop_node=document.createElement("DIV");
		pop_node.id="menu_top_yj";
		pop_node.className="menu_top_pop_layer";
		pop_node.style.display="block";
		pop_node.style.position="relative";
		pop_node.style.width="100%";
		pop_node.innerHTML=pop_str;
		parentnode.appendChild(pop_node);
	}
	//创建一级菜单
	if(!document.getElementById("menu_top_pop_layer_box"))
	{
		var pop_layer_box=document.createElement("DIV");
		pop_layer_box.id="menu_top_pop_layer_box";
		parentnode.appendChild(pop_layer_box);
	}
	//创建下一级菜单
	var parentNode=document.getElementById("menu_top_pop_layer_box");
	parentNode.innerHTML=tatal_users.innerHTML;
	parentNode.style.display="none";
}


function menu_pop_cancel()
{
	//document.getElementById("menu_top_yj").style.display="none";
	//document.getElementById("menu_top_pop_layer_box").style.display="none";
}
//表达式JS
function expression()
{
	c=document.getElementsByTagName("input");
	_all="";
	_group="[,";
	user="{,";
	
	for(i=0;i<c.length;i++)
	{
		if('checkbox'==c[i].type)
		{
			
			if(c[i].name=='all')
			{
				 if(c[i].checked)_all="!all";
			}
			else if(c[i].name=='group')
			{
				 if(c[i].checked)_group+=c[i].value+",";
				 else user+=selectUsersValue(c[i]);
			}
		}
	}
	_group+="]";
	user+="}";
	tem="";
	//
	if(user=='{,}')
	{
		if(_group=="[,]")
		{
			tem=_all;	
		}
		else
		{
			tem=_group;
		}
	}
	else
	{
		tem=user;
		if(_group!="[,]")tem=_group+"|"+tem;
	}
	//传送表达式
	if(document.getElementById('privacy')){
		document.getElementById('privacy').value=tem;
	}else{
		if(tem!=current_menu_info[2]){
			var post_expree=new Ajax();
			post_expree.getInfo("servtools/menu_pop/translate_pri.php","POST","app","t_name="+current_menu_info[0]+"&vid="+current_menu_info[1]+"&tem="+tem,function(c){
				if(c=='success'){
					var re_obj=document.getElementById(current_menu_info[0]+':'+current_menu_info[1]+':'+current_menu_info[2]);
					re_obj.id=current_menu_info[0]+':'+current_menu_info[1]+':'+tem;
				}else{
					alert('设置失败');
				}
			});
		}else{
			
		}
	}
	menu_pop_cancel();
	//结束传送表达式
}
function checkall(a)
{
	if(a.checked)
	{
		c=document.getElementsByTagName("input");
		for(i=0;i<c.length;i++)
		{
			if(c[i].type=='checkbox')c[i].checked=true;
		}
	}
	else
	{
		c=document.getElementsByTagName("input");
		for(i=0;i<c.length;i++)
		{
			if(c[i].type=='checkbox')c[i].checked=false;
		}
	}
}

//选择组
function selectUsers(me)
{
	var c=document.getElementsByName(me.value);
	var flag=true;
	for(var i=0;i<c.length;i++)
	{
		if(c[i].type=='checkbox') c[i].checked=me.checked;
	}
	var groups=document.getElementsByName("group");
	for(var i=0;i<groups.length;i++)
	{
		if(!groups[i].checked) flag=false;
	}
	document.getElementsByName("friends")[0].checked=flag;
	document.getElementsByName("all")[0].checked=false;
	expression();
}
function selectUsersValue(me)
{

	var users=document.getElementsByName(me.value);
	var tem="";
	for(j=0;j<users.length;j++)
	{
		if(users[j].type=='checkbox' && users[j].checked ) tem+=users[j].value+",";
	}
	return tem;
}

function selectGroup(obj)
{
	var group=document.getElementsByName("group");
	var flag=true;
	var all_flag=false;
	var frined_flag=true;
	var users=document.getElementsByName(obj.name);
	for(i=0;i<users.length;i++)
	{
		if(users[i].type=="checkbox" && !users[i].checked) flag=false; 
		if(users[i].checked)all_flag=false;
	}
	for(i=0;i<group.length;i++)
	{
		if(group[i].type=='checkbox' && group[i].value==obj.name) group[i].checked=flag;
		if(!group[i].checked)frined_flag=false;
	}
	var all=document.getElementsByName("all")[0];
	var friends=document.getElementsByName("friends")[0];
	all.checked=all_flag;
	friends.checked=frined_flag;
	expression();
}
function changeGroup()
{
	groups_str=translateGroup();
	users_str=translateUser();
	var groups=document.getElementsByName("group");
	var all=document.getElementsByName("all")[0];
	all.checked=false;
	if(current_menu_info.length==3 && current_menu_info[2].indexOf("!all")!=-1)all.checked=true;
	for(var i=0;i<groups.length;i++)
	{
		var users=document.getElementsByName(groups[i].value);
		for(var j=0;j<users.length;j++)
		{
			if(users_str.indexOf((","+users[j].value+","))!=-1) users[j].checked=true;
			else users[j].checked=false;
		}
		if(groups_str.indexOf(","+groups[i].value+",")!=-1)
		{
			groups[i].checked=true;
			selectUsers(groups[i]);
		}
		else groups[i].checked=false;
	}
}
function translateUser()
{
	if(current_menu_info=="") return "";
	if(current_menu_info.length==3)
	{
		var users=current_menu_info[2].replace(/.*{(.*)}.*/i,"$1");
		if(users!=current_menu_info[2])
		{
			return users;
		}
		else return "";
	}
	else return "";
}
function translateGroup()
{
	if(current_menu_info=="") return "";
	if(current_menu_info.length==3)
	{
		var users=current_menu_info[2].replace(/.*\[(.*)\].*/i,"$1");
		if(users!=current_menu_info[2])
		{
			return users;
		}
		else return "";
	}
	else return "";
}
function translateUser()
{
	if(current_menu_info=="") return "";
	if(current_menu_info.length==3)
	{
		var users=current_menu_info[2].replace(/.*{(.*)}.*/i,"$1");
		if(users!=current_menu_info[2])
		{
			return users;
		}
		else return "";
	}
	else return "";
}
function selectFriend(obj)
{
	var flag=false;
	if(obj.name=="all")
	{
		flag=false;
		var group=document.getElementsByName("friends");
		if(group[0].type=='checkbox') group[0].checked=flag;
	}
	else
	{
		flag=obj.checked;
		var all=document.getElementsByName("all");
		if(all[0].type=='checkbox') all[0].checked=false;
	}
	var group=document.getElementsByName("group");
	for(var i=0;i<group.length;i++)
	{
		if(group[i].type=='checkbox') group[i].checked=flag;
		var users=document.getElementsByName(group[i].value);
		for(var j=0;j<users.length;j++)
		{
			if(users[j].type=="checkbox") users[j].checked=flag;
		}
	}
	expression();
}

function menu_getX(elem){
    var x = 0;
    while(elem){
        x = x + elem.offsetLeft;
        elem = elem.offsetParent;
    }
    return x;
}
function menu_getY(elem){
    var y = 0;
    while(elem){
        y = y + elem.offsetTop;
        elem = elem.offsetParent;
    }
    return y;
}