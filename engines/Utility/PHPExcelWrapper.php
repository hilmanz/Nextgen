<?php
/*
 *	Irvan Fanani
 *	7 Maret 2011
 */
require_once('PHPExcel.php');
class PHPExcelWrapper extends PHPExcel{
	private $_head 		= array();
	private $_abjad		= array();
	private $_col 			=	0;
	private $_endPos 	= '';
	private $_globalBorder = false;
	private $_globalBorderStyle = array();
	
	public function __construct(){
		parent::__construct();
		$abjad = explode(',', 'A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z');
		$this->_abjad = $abjad;
	}
	public function setHeader($head){
		$this->_head = $head;
		$this->_col = count($head);
	}
	public function getExcel($data,$title,$ser='xls'){
		$xtype = ($ser == 'xlsx') ?  'Excel2007' : 'Excel5';
		for($i=0;$i<$this->_col;$i++){
			$this->setActiveSheetIndex(0)->setCellValue( strval($this->_abjad[$i]) .'1', $this->_head[$i] );
		}
		$row = 2;
		$i = 0;
		if ($data) {
			foreach( $data as $k ){
				foreach( $k as $kk => $v){
					$this->setActiveSheetIndex(0)->setCellValue( strval($this->_abjad[$i]) . strval($row), $v );
					$this->_endPos = strval($this->_abjad[$i]) . strval($row);
					$i = $i + 1;
				}
				$i = 0;
				$row = $row + 1;
			}

			//check if use global border
			if( $this->_globalBorder ){
				$this->getActiveSheet()->getStyle('A1:'.$this->_endPos)->applyFromArray($this->_globalBorderStyle);
			}
		}
		
		$this->getActiveSheet()->setTitle($title);
		$this->setActiveSheetIndex(0);
		
		while (ob_get_level() > 0) {
			ob_end_clean();
		}

		//header("Pragma: public");
		//header("Pragma: no-cache");
		//header("Expires: 0");
		//header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      	//header("Cache-Control: private",false);
		header("Cache-Control: maxage=1"); //In seconds
		header("Pragma: public");
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $title . '.' . $ser. '"');
		header("Content-Transfer-Encoding: binary");
		flush();
		$objWriter = PHPExcel_IOFactory::createWriter($this, $xtype);
		$objWriter->save('php://output');
	}
	
	/*
	 *	parameter $type option:
     * 	- allborders
	 * 	- outline
	 * 	- inside
	 * 	- vertical
	 * 	- horizontal
	 *	parameter $borderType option:
			BORDER_NONE                				= 'none';
			BORDER_DASHDOT            			= 'dashDot';
			BORDER_DASHDOTDOT            	= 'dashDotDot';
			BORDER_DASHED                			= 'dashed';
			BORDER_DOTTED                			= 'dotted';
			BORDER_DOUBLE                			= 'double';
			BORDER_HAIR                					= 'hair';
			BORDER_MEDIUM                			= 'medium';
			BORDER_MEDIUMDASHDOT        	= 'mediumDashDot';
			BORDER_MEDIUMDASHDOTDOT	= 'mediumDashDotDot';
			BORDER_MEDIUMDASHED        	= 'mediumDashed';
			BORDER_SLANTDASHDOT        	= 'slantDashDot';
			BORDER_THICK                				= 'thick';
			BORDER_THIN                					= 'thin';
	 */
	public function setGlobalBorder($set, $type, $color, $size='thin'){
		$this->_globalBorder = $set;
		$this->_globalBorderStyle = array(
														'borders' => array(
															$type => array(
																'color' => array('argb' => $color)
															),
														),
													);
		if( $size == 'none' ){
			$this->_globalBorderStyle['borders'][$type]['style'] = PHPExcel_Style_Border::BORDER_NONE;
		}else if( $size == 'dashDot' ){
			$this->_globalBorderStyle['borders'][$type]['style'] = PHPExcel_Style_Border::BORDER_DASHDOT;
		}else if( $size == 'thick' ){
			$this->_globalBorderStyle['borders'][$type]['style'] = PHPExcel_Style_Border::BORDER_THICK;
		}else if( $size == 'thin' ){
			$this->_globalBorderStyle['borders'][$type]['style'] = PHPExcel_Style_Border::BORDER_THIN;
		}else if( $size == 'slantDashDot' ){
			$this->_globalBorderStyle['borders'][$type]['style'] = PHPExcel_Style_Border::BORDER_SLANTDASHDOT;
		}else if( $size == 'mediumDashed' ){
			$this->_globalBorderStyle['borders'][$type]['style'] = PHPExcel_Style_Border::BORDER_MEDIUMDASHED;
		}else if( $size == 'mediumDashDotDot' ){
			$this->_globalBorderStyle['borders'][$type]['style'] = PHPExcel_Style_Border::BORDER_MEDIUMDASHDOTDOT;
		}else if( $size == 'mediumDashDot' ){
			$this->_globalBorderStyle['borders'][$type]['style'] = PHPExcel_Style_Border::BORDER_MEDIUMDASHDOT;
		}else if( $size == 'medium' ){
			$this->_globalBorderStyle['borders'][$type]['style'] = PHPExcel_Style_Border::BORDER_MEDIUM;
		}else if( $size == 'hair' ){
			$this->_globalBorderStyle['borders'][$type]['style'] = PHPExcel_Style_Border::BORDER_HAIR;
		}else if( $size == 'double' ){
			$this->_globalBorderStyle['borders'][$type]['style'] = PHPExcel_Style_Border::BORDER_DOUBLE;
		}else if( $size == 'dotted' ){
			$this->_globalBorderStyle['borders'][$type]['style'] = PHPExcel_Style_Border::BORDER_DOTTED;
		}else if( $size == 'dashed' ){
			$this->_globalBorderStyle['borders'][$type]['style'] = PHPExcel_Style_Border::BORDER_DASHED;
		}else if( $size == 'dashDotDot' ){
			$this->_globalBorderStyle['borders'][$type]['style'] = PHPExcel_Style_Border::BORDER_DASHDOTDOT;
		}
	}
}
?>