<?php
/**
 * 我的订单
 *
 * @author 微动力
 * @url
 */
//$this->checklogin($from);
$status=intval($_GPC['status']);


//搜索订单
 
if($_GPC['d']=='detail'){
	$orderid=intval($_GPC['orderid']);
	$condition="AND status ={$status}  AND id={$orderid} AND from_user='{$_W['fans']['from_user']}'";
	$order = pdo_fetch("SELECT * FROM ".tablename('shopping3_order')." WHERE weid = '{$weid}'  $condition ");
 
 	$row= pdo_fetchall("SELECT a.*,b.title,b.thumb,b.marketprice FROM ".tablename('shopping3_order_goods')." as a left join  ".tablename('shopping3_goods')." as b on a.goodsid=b.id WHERE a.weid = '{$weid}' and a.orderid={$order['id']}");
	//$address=pdo_fetch("SELECT * FROM ".tablename('shopping3_address')." WHERE weid = '{$weid}' AND id={$order['aid']}");
}else{
	$pindex = max(1, intval($_GPC['page']));
	$psize = 5;
 	$condition="AND status ={$status} AND from_user='{$_W['fans']['from_user']}'";
	
	/* if($_GPC['ispay']==1){
		$condition.=" AND ispay=1 ";
	}elseif($_GPC['ispay']==0){
		$condition.=" AND ispay=0 ";
	} */
	
	$list = pdo_fetchall("SELECT * FROM ".tablename('shopping3_order')." WHERE weid = '{$weid}'  $condition ORDER BY createtime DESC, id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('shopping3_order') . " WHERE weid = '{$weid}' $condition");
	$pager='';
	if($pindex==1){
		$pager.="<font class='page_noclick'><<上一页</font>&nbsp;&nbsp;";
	}else{
		$_GET['page'] = $pindex-1;
		$pager.="<a href='" . $_W['script_name'] . "?" . http_build_query($_GET) . "' class='page_button'><<上一页</a>&nbsp;&nbsp;";
	}
	$totalpage= ceil($total / $psize);
	
	$pager.="<span class='fc_red'>".$pindex."</span> / ".$totalpage."&nbsp;&nbsp;";
	if($pindex==$totalpage){
		$pager.="<font class='page_noclick'>下一页>></font>&nbsp;&nbsp;";
	}else{
		$_GET['page'] = $pindex+1;
		$pager.="<a href='" . $_W['script_name'] . "?" . http_build_query($_GET) . "' class='page_button'>下一页>></a>&nbsp;&nbsp;";
	}
	foreach($list as $k=>$v){
		$list[$k]['goods']= pdo_fetchall("SELECT a.*,b.title,b.thumb,b.marketprice FROM ".tablename('shopping3_order_goods')." as a left join  ".tablename('shopping3_goods')." as b on a.goodsid=b.id WHERE a.weid = '{$weid}' and a.orderid={$v['id']} limit 3");
	}
}
	include $this->template('wl_member');
