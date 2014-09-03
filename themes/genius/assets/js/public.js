/**
 * vo public js
 * @author xiongchuan <xiongchuan@luxtonenet.com>
 */
var VO = {
    version:"v0.1"
};
VO.Ajax = {
	className:"id",
	changeStatus:function(dom){
		if($(dom).attr('class')!='clicked'){
			$(dom).addClass('clicked');
			var id = $(dom).siblings('.'+this.className).html();
			if(id!='' && id!= undefined){
				$.post("/admin/config/changeStatus",{id:id},function(data){
					if(1==data.status){
						$(dom).html(data.msg);
					}else{
						alert('修改失败！');
					}
					$(dom).removeClass('clicked');
				},'json')
			}
		}
	}
}
