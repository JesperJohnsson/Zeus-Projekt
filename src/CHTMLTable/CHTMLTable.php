<?php 
/** 
 *    Class to present results from database query 
 */ 
class CHTMLTable extends CMovieSearch{ 

    /** 
     *    Members 
     */ 
    private $hits; 
    private $page; 
    /** 
     * Functions 
     */ 
    public function __construct($hits=8, $page=1) { 
        $this->hits = $hits; 
        $this->page = $page; 
    } 

    /** 
     *    Create links for htis per page. 
     * 
     *    @param array $hits a list of hits-options to display 
     *    @param array $current value. 
     *    @return string as a linke to this page. 
     */ 
    private function getHitsPerPage($hits, $current=null) { 
		$this->hits = $hits;
        $nav = "Träffar per sida: "; 
        foreach ($hits AS $val) { 
            if ($current == $val) { 
                $nav .= "$val "; 
            } else { 
                $nav .= "<a href='" . parent::getQueryString(array('hits' => $val)) . "'>$val</a> "; 
            } 
        } 
        return $nav; 
    } 

    /** 
     *    Create navigation among pages 
     * 
     *    @param integer $hits per page. 
     *    @param integer $page current page. 
     *    @param integer $max number of pages. 
     *    @param integer $min is the first page number, usually 0 or 1. 
     *    @return string as a link to this page. 
     */ 
    private function getPageNavigation($hits, $page, $max, $min=1) { 
        $nav = "<li><a href='" . parent::getQueryString(array('page' => $min)) . "'>&lt;&lt;</a></li> "; 
        $nav .= "<li><a href='" . parent::getQueryString(array('page' => ($page > $min ? $page - 1 : $min))) . "'>&lt;</a></li> "; 

        for ($i=$min; $i<=$max; $i++) { 
            $nav .= "<li><a href='" . parent::getQueryString(array('page' => $i)) . "'>$i</a></li> "; 
        } 

        $nav .= "<li><a href='" . parent::getQueryString(array('page' => ($page < $max ? $page + 1 : $max))) . "'>&gt;</a></li> "; 
        $nav .= "<li><a href='" . parent::getQueryString(array('page' => $max)) . "'>&gt;&gt;</a></li> "; 

        return $nav; 
    } 

    /** 
     *    Sets parameters for ordering 
     * 
     *    @param string $column is the column that it should be sorted by. 
     *    @return returns a span that can be used for output. 
     */ 
    public function orderby($column) { 
        $nav = "<a href='" . parent::getQueryString(array('orderby'=>$column, 'order'=>'asc')) . "'>&darr;</a>"; 
        $nav .= "<a href='" . parent::getQueryString(array('orderby'=>$column, 'order'=>'desc')) . "'>&uarr;</a>"; 
        return "<span class='orderby'>" . $nav . "</span>"; 
    } 

    /** 
     *    Renders the output table of all the querys 
     * 
     *    @param SQL result $res is the result from a database query. 
     *    @param integer $max is the max amount of pages. 
     *    @param integer $rows the number of rows that should be fetched. 
     *    @return returns $html, contains the table. 
     */ 

    public function RenderTable($res, $max, $rows, $acronym) { 
	
		$hitsPerPage = $this->getHitsPerPage(array(2, 4, 8), $this->hits);
        $navigatePage = $this->getPageNavigation($this->hits, $this->page, $max);
		
		if($rows > 1) { $nrOfRows = '<strong>'. $rows .'</strong> Träffar.';}else{$nrOfRows = '<strong>'. $rows .'</strong> Träff.';}
		
        $table = "<div class='row text-center'>";
		$tableh = "<thead><th width='66'></th><th width='500'>Titel " . $this->orderby('title') . "</th><th width='50'>År " . $this->orderby('year2') . "</th><th>Pris ". $this->orderby('price') ."</th></thead>";
		$i = 1;
		foreach($res AS $key => $val) {

			$table .= "
			<div class='col-md-3 a'>
				<figure style='border:1px solid #EEEEEE;padding-top:2px;padding-left:1px;padding-bottom:1px;box-shadow: 2px 2px 1px #d3d3d3;'>
					<div class='pricetagsmall'><p style='color:#FFF;line-height:50px;'>{$val->price}kr</p></div>
					<img class='blueborder' src='img.php?src={$val->image}&amp;width=252&amp;height=400&amp;crop-to-fit&amp;save-as=jpg&amp;sharpen'>
					<a href='movie.php?id={$val->id}'>
						<span class='link-spanner'></span>
					</a>
				</figure>
			</div>";
			
			if(($i % 4) === 0) 
			{ 
				$table .= "<div class='col-md-12'><hr></hr></div>"; 
			}
			$i++;
		}
		
		$table .= "</div>";
		
		$html = <<<EOD
		<div class="row">
			<div class="col-md-6">
				<div>{$tableh}</div>
			</div>
			<div class="col-md-6 text-right">
				<div>{$nrOfRows} {$hitsPerPage}</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<hr></hr>
				{$table}
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 text-center">
				<div><ul class="pagination">{$navigatePage}<ul></div>
			</div>
		</div>
EOD;

		return $html;
	}
}
