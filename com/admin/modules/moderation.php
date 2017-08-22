<?php
class moderation extends App{

	function beforeFilter(){
		global $LOCALE,$CONFIG;
		
		$this->contentHelper = $this->useHelper('contentHelper');
		$this->searchHelper = $this->useHelper('searchHelper');
	 
	 
		
		$this->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
		$this->assign('locale', $LOCALE[1]);		
		$this->assign('pages', strip_tags($this->_g('page')));
		$this->assign('user',$this->user);
		
	 
	}
	
	function main(){
		$articlelist = $this->contentHelper->getArticleContent(null,10,0,array(0,3),"timeline");
		// pr($articlelist);
		$time['time'] = '%H:%M:%S';
		
		$this->assign('total',intval($articlelist['total']));
		$this->assign('timeline',$articlelist['result']);
		$this->assign('time',$time);
		$this->log('surf','moderation');
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'/apps/moderation-pages.html');
	}
	
	function detail(){
		$cidStr = intval($this->_request('id'));		
		$article = $this->contentHelper->getDetailArticle();
		$comment = $this->contentHelper->getComment($cidStr,false,0,10);
		
		$this->assign('detail',$article['result']);
		$this->assign('comment',$comment[$cidStr]);
		$this->assign('cidStr',$cidStr);
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'/widgets/inbox-detail.html');
	}
	
	function addComment(){
		global $CONFIG;
		
		$cid = intval($this->_p('cid'));
		$data = $this->contentHelper->addComment();
		sendRedirect($CONFIG['ADMIN_DOMAIN']."moderation/detail/{$cid}");
		return $this->out(TEMPLATE_DOMAIN_ADMIN . '/login_message.html');
		exit;
	}
	
	function unpublish(){
		global $CONFIG;
		
		$this->Request->setParamPost('cid',intval($this->_request('id')));
		$this->Request->setParamPost('publishedtype',3);
		$this->Request->setParamPost('type',3);
		
		$data = $this->contentHelper->unContentPost();
		sendRedirect($CONFIG['ADMIN_DOMAIN']."moderation");
		return $this->out(TEMPLATE_DOMAIN_ADMIN . '/login_message.html');
		exit;
	}
	
	function commentList(){
		$commentlist = $this->contentHelper->getCommentModeration(null,10,3);
		
		$this->assign('startdate',$this->_p('startdate'));
		$this->assign('enddate',$this->_p('enddate'));
		$this->assign('search',$this->_p('search'));
		$this->assign('total',intval($commentlist['total']));
		$this->assign('comment',$commentlist['result']);
		$this->assign('act',"commentList");
		$this->log('surf','moderation');
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'/apps/moderation-pages.html');
	}
	
	function venueList(){
		$venuelist = $this->checkinHelper->getVenue(null,10);

		$this->assign('search',$this->_p('search'));
		$this->assign('searchType',$this->_p('searchType'));
		$this->assign('total_venue',intval($venuelist['total']));
		$this->assign('venue',$venuelist['result']);
		$this->assign('act',"venueList");
		$this->log('surf','moderation');
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'/apps/moderation-pages.html');
	}
	
	function galleryList(){
		$articlelist = $this->contentHelper->getGalleryTypeContent(null,10);
		// pr($articlelist);
		$time['time'] = '%H:%M:%S';
		$this->assign('startdate',$this->_p('startdate'));
		$this->assign('enddate',$this->_p('enddate'));
		$this->assign('search',$this->_p('search'));
		$this->assign('total',intval($articlelist['total']));
		$this->assign('timeline',$articlelist['result']);
		$this->assign('time',$time);
		$this->assign('act',"galleryList");
		$this->log('surf','moderation');
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'/apps/moderation-pages.html');
	
	}
	
	
	function detailVenue(){
		$cidStr = intval($this->_request('id'));
		$article = $this->checkinHelper->getVenue(null,10,"detail");
	
		
		$category = $this->contentHelper->getCategoryVenue();
		$province = $this->contentHelper->getListProvinceVenue();
		$venueCat = $this->contentHelper->getCategoryVenue();
		
		$this->assign('detail',$article['result'][0]);
		$this->assign('category',$category);
		$this->assign('province',$province);
		$this->assign('venue_category',$venueCat);
		$this->assign('cidStr',$cidStr);
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'/widgets/edit-venue.html');
	}
	
	function unpublishvenue(){
		global $CONFIG;
		$this->Request->setParamPost('publishedtype',3);
		$data = $this->contentHelper->unVenueReference();
		sendRedirect($CONFIG['ADMIN_DOMAIN']."moderation/venueList");
		return $this->out(TEMPLATE_DOMAIN_ADMIN . '/login_message.html');
		exit;
	}
	
	function editVenue(){
		global $CONFIG;
		
		$data = $this->contentHelper->editVenue();
		
		sendRedirect($CONFIG['ADMIN_DOMAIN']."moderation/venueList");
		return $this->out(TEMPLATE_DOMAIN_ADMIN . '/login_message.html');
		exit;
	}
	
	function uncomment(){
		global $CONFIG;
		$this->Request->setParamPost('publishedtype',3);
		$this->Request->setParamPost('id',intval($this->_request('id')));		
		$data = $this->contentHelper->unCommentPost();
		sendRedirect($CONFIG['ADMIN_DOMAIN']."moderation/commentList");
		return $this->out(TEMPLATE_DOMAIN_ADMIN . '/login_message.html');
		exit;
	}
	
	function ajax(){
		$needs = $this->_request("needs");
		$contentid = intval($this->_request("contentid"));

		$start = intval($this->_request("start"));
		if ($this->_request("startdate") || $this->_request("enddate") || $this->_request("search")) {
			$this->Request->setParamPost('startdate',$this->_request('startdate'));
			$this->Request->setParamPost('enddate',$this->_request('enddate'));
			$this->Request->setParamPost('search',$this->_request('search'));
		}
		
		if($needs=="post_moderation") $data =  $this->contentHelper->getArticleContent($start,10,0,array(0,3),"timeline");
		if($needs=="gallery_moderation") $data =  $this->contentHelper->getGalleryTypeContent($start,10);
		if($needs=="comment_moderation") $data =  $this->contentHelper->getCommentModeration($start,10,3);
		if($needs=="venue_moderation") $data =  $this->checkinHelper->getVenue($start,10);
		if($needs=="load_city_moderation"){
			$data =  $this->contentHelper->getListCityVenue($this->_p('provinceName'));
		}
		
		print json_encode($data);exit;
	}
	
	
	function publishit(){
		
		global $CONFIG;
		
		$this->Request->setParamPost('cid',intval($this->_request('id')));
		$this->Request->setParamPost('publishedtype',1);	
		$this->Request->setParamPost('type',3);
		
		$data = $this->contentHelper->unContentPost();
		sendRedirect($CONFIG['ADMIN_DOMAIN']."moderation");
		return $this->out(TEMPLATE_DOMAIN_ADMIN . '/login_message.html');
		exit;
	}
	
	function commentpublished(){
			
		global $CONFIG;
		$this->Request->setParamPost('publishedtype',1);	
		$this->Request->setParamPost('id',intval($this->_request('id')));		
		$data = $this->contentHelper->unCommentPost();
		sendRedirect($CONFIG['ADMIN_DOMAIN']."moderation/commentList");
		return $this->out(TEMPLATE_DOMAIN_ADMIN . '/login_message.html');
		exit;
	}
	
	function publishitvenue(){
			
		global $CONFIG;
			$this->Request->setParamPost('publishedtype',1);
		$data = $this->contentHelper->unVenueReference();
		sendRedirect($CONFIG['ADMIN_DOMAIN']."moderation/venueList");
		return $this->out(TEMPLATE_DOMAIN_ADMIN . '/login_message.html');
		exit;
	}
	
	function getUserAllName(){
		$keyword = $this->_p('q');
		$sql =" SELECT id,name,last_name FROM social_member WHERE name like '{$keyword}%' OR last_name like '%{$keyword}%' AND n_status = 1 ORDER BY name ASC , last_name ASC LIMIT 20 ";
		
		$data = $this->fetch($sql,1);
		
		print json_encode($data);exit;
		
	}
	
}
?>