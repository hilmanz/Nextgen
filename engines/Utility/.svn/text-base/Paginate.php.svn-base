<?php
class Paginate{
	var $View;
	function get($start,$limit,$total_rows){
		$curr_page = ceil($start/$limit)+1;
		if($curr_page==0){$curr_page=1;}
		$total_pages = ceil($total_rows/$limit);
		if($curr_page-3>=1){
			if($curr_page+3>=$total_pages){
				$i = $total_pages-7;
			}else{
				$i=$curr_page-3;
			}
		}else{
			$i=0;
		}
		$j=0;
		for($n=0;$n<7;$n++){
			if($i>=0){
				if($i+1>$total_pages){break;}
				$foo[$j]['start'] = $i*$limit;
				$foo[$j]['no'] = $i+1;
				$j++;
			}
			
			$i++;
		}
		return $foo;
	}
	function generate($start,$limit,$total_rows,$base_url="",$start_param="st"){
		
		if($total_rows!=0&&$total_rows>$limit){
			$curr_page = ceil($start/$limit)+1;
			if($curr_page==0){$curr_page=1;}
			$last_rows = ceil($total_rows/$limit)*$limit-$limit;
			$this->View = new BasicView();
			$this->View->assign("curr_page",$curr_page);
			$this->View->assign("begin","0");
			$this->View->assign("last",$last_rows);
			$this->View->assign("page",$this->get($start,$limit,$total_rows));
			$this->View->assign("start_param",$start_param);
			$this->View->assign("base_url",$base_url);
			
			$nPages = ceil($total_rows/$limit);
			
			if($curr_page!=1){
				$this->View->assign("isPrev","1");
			}else{
				if($curr_page<$nPages&&$nPages>1){
					$this->View->assign("isNext","1");
				}
			}
			
			return $this->View->toString("common/paging.html");
		}
	}
	function getAdminPaging($start,$limit,$total_rows,$base_url=""){
		if($total_rows!=0&&$total_rows>$limit){
			$curr_page = ceil($start/$limit)+1;
			if($curr_page==0){$curr_page=1;}
			$last_rows = ceil($total_rows/$limit)*$limit-$limit;
			$this->View = new BasicView();
			$this->View->assign("curr_page",$curr_page);
			$this->View->assign("begin","0");
			$this->View->assign("last",$last_rows);
			$this->View->assign("page",$this->get($start,$limit,$total_rows));
			$this->View->assign("base_url",$base_url);
			$nPages = ceil($total_rows/$limit);
			
			if($curr_page!=1){
				$this->View->assign("isPrev","1");
			}else{
				if($curr_page<$nPages&&$nPages>1){
					$this->View->assign("isNext","1");
				}
			}
			return $this->View->toString("common/admin/paging.html");
		}
	}
	function getJsPaging($start,$limit,$total_rows,$base_url,$html_part){
		if($total_rows!=0&&$total_rows>$limit){
			$curr_page = ceil($start/$limit)+1;
			if($curr_page==0){$curr_page=1;}
			$last_rows = ceil($total_rows/$limit)*$limit-$limit;
			$this->View = new BasicView();
			$this->View->assign("curr_page",$curr_page);
			$this->View->assign("html_part",$html_part);
			$this->View->assign("begin","0");
			$this->View->assign("last",$last_rows);
			$this->View->assign("page",$this->get($start,$limit,$total_rows));
			$this->View->assign("base_url",$base_url);
			$nPages = ceil($total_rows/$limit);
			
			if($curr_page!=1){
				$this->View->assign("isPrev","1");
			}else{
				if($curr_page<$nPages&&$nPages>1){
					$this->View->assign("isNext","1");
				}
			}
			return $this->View->toString("common/paging_js.html");
		}
	}
}
?>