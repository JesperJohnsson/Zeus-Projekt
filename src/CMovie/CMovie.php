<?php
/* 
 * Class to represent movies 
 */ 
class CMovie {
	
	/**
	* Members
	*/
	private $db;
	
	
	
	/**
	* Constructor
	*/
	public function __construct($db) {
		$this->db = $db;
	}
	
	/**
	* Function showMovie, handles showing single movie pages
	*
	* @param $title, a title of a movie
	* @param $acronym, if user is logged in
	*/
	public function showMovie($id, $acronym = null) {
		
		$content = null;
		$sql = '
            SELECT *
			FROM VMovie
			WHERE
			id = ?;
        ';
		$params = array($id);
		
		$res = $this->db->ExecuteSelectQueryAndFetchAll($sql, $params);
		
		$editLink = null;
		$deleteLink = null;
		$breadcrumb = null;
		
		if(isset($res[0])) 
		{
			foreach($res as $c)
			{
				// Sanitize content before using it.
				if($acronym) {
					$editLink = "<a href='edit_movie.php?id={$c->id}'><span class='glyphicon glyphicon-edit' aria-hidden='true'></span></a>";
					$deleteLink = "<a href='delete_movie.php?id={$c->id}'><span class='glyphicon glyphicon-remove-sign' aria-hidden='true'></span></a>";
				}
				$breadcrumb = "<ul class='breadcrumb'>\n<li><a href='movies.php?'>Filmer</a></li><li><a href='?id={$c->id}'>{$c->title}</a></li></ul>";
				
				$content .= " 
				{$breadcrumb}
				<section> 
					<article>
					<div class='row'>
						<div class='col-md-offset-1 col-md-3 hidden-xs hidden-sm'>
							<figure>
								<img class='blueborder' src='img.php?src={$c->image}&amp;width=350&amp;height=500&amp;crop-to-fit&amp;sharpen'>
								<figcaption style='margin-top:20px;margin-left:70px;'>
									<a href='#' class='btn btn-danger btn-lg'>HYR {$c->price}kr / 48h</a>
								</figcaption>
							</figure>
						</div>
						<div class='col-md-offset-1 col-md-6' style='border:1px solid #EEEEEE;'>
							
							<div class='row text-right'>
								{$editLink} {$deleteLink}
							</div>
							
							<header class='text-center'> 
								<h2>{$c->title}<p><small> {$c->year2} | {$c->genre}</small></p></h2> 
								
								<hr></hr>
							</header>
							
							<p><strong>Regissör : </strong> {$c->director}</p>
							<hr></hr>
							<p><strong>Längd : </strong> {$c->length}min</p>
							<hr></hr>
							<p><strong>Text : </strong> {$c->subtext}</p>
							<hr></hr>
							<p><strong>Språk : </strong> {$c->speech}</p>
							<hr></hr>
							<p><strong>Kvalitet  : </strong> {$c->quality}</p>
							<hr></hr>
							<p><strong>Imdb  : </strong><a href='{$c->imdb}' target='blank'>{$c->imdb}</a></p>
							<hr></hr>
							<p><strong>Trailer  : </strong><a href='{$c->youtube}' target='blank'>{$c->youtube}</a></p>
							<hr></hr>
							<p><strong>Om filmen  : </strong> {$c->plot}</p>							
							
						</div>
					</div>
					<footer>
						
					</footer>
					 
					</article> 
				</section>

				"; 
			}
			
		}
		elseif($title)
		{
			header("Location: movies.php");
		}
		else 
		{
			header("Location: movies.php");
		}
		return $content;
		
	}
	
	public function ShowHomeMovies() {
	
		$sql = '
            SELECT *
			FROM VMovie
			ORDER BY id DESC
			LIMIT 3;
        ';
		$res = $this->db->ExecuteSelectQueryAndFetchAll($sql);
		$content = "<div class='col-md-12 text-center frontpageheader'  style='margin-top: 0;'><h1>Senaste Filmer</h1></div>";
		$content .= "<div class='row text-center'>";
		
		foreach($res as $c) 
		{
			$content .= "
			<div class='col-md-4 a'>
				<figure style='border:1px solid #EEEEEE;padding-top:4px;box-shadow: 2px 2px 1px #d3d3d3;'>
					<div class='pricetag'><h3 style='color:#FFF;line-height:35px;'>{$c->price}kr</h3></div>
					<img class='blueborder' src='img.php?src={$c->image}&amp;width=350&amp;height=500&amp;crop-to-fit&amp;save-as=jpg&amp;sharpen'>
					<figcaption>
						<h3>{$c->title} <small>{$c->year2}</small></h3>
					</figcaption>
					<a href='movie.php?id={$c->id}'>
						<span class='link-spanner'></span>
					</a>
				</figure>
			</div>
			";
		}
		
		$content .= "</div>";
		
		return $content;
	}
	
	public function ShowMostPopularMovie() {
		
		$sql = '
            SELECT *
			FROM VMovie
			WHERE id = 12
			LIMIT 1;
        ';
		$res = $this->db->ExecuteSelectQueryAndFetchAll($sql);
		$content = null;
		
		foreach($res as $c)
		{
			$content .= "
			<div class='col-md-12'>
				<h1>Populäraste Filmen</h1>
			</div>
			<div class='col-md-12 a'>
				<figure style='border:1px solid #EEEEEE;padding-top:4px;box-shadow: 2px 2px 1px #d3d3d3;'>
					<div class='pricetag'><h3 style='color:#FFF;line-height:35px;'>{$c->price}kr</h3></div>
					<img class='blueborder' src='img.php?src={$c->image}&amp;width=350&amp;height=500&amp;crop-to-fit&amp;save-as=jpg&amp;sharpen'>
					<figcaption>
						<h3>{$c->title} <small>{$c->year2}</small></h3>
					</figcaption>
					<a href='movie.php?id={$c->id}'>
						<span class='link-spanner'></span>
					</a>
				</figure>
			</div>
			";
		}
		
		return $content;
	}
	
	public function LastRentedMovie() {
		
		$sql = '
            SELECT *
			FROM VMovie
			ORDER BY RAND()
			LIMIT 1;
        ';
		$res = $this->db->ExecuteSelectQueryAndFetchAll($sql);
		$content = null;
		
		foreach($res as $c)
		{
			$content .= "
			<div class='col-md-12'>
				<h1>Senaste Hyrda Filmen</h1>
			</div>
			<div class='col-md-12 a'>
				<figure style='border:1px solid #EEEEEE;padding-top:4px;box-shadow: 2px 2px 1px #d3d3d3;'>
					<div class='pricetag'><h3 style='color:#FFF;line-height:35px;'>{$c->price}kr</h3></div>
					<img class='blueborder' src='img.php?src={$c->image}&amp;width=350&amp;height=500&amp;crop-to-fit&amp;save-as=jpg&amp;sharpen'>
					<figcaption>
						<h3>{$c->title} <small>{$c->year2}</small></h3>
					</figcaption>
					<a href='movie.php?id={$c->id}'>
						<span class='link-spanner'></span>
					</a>
				</figure>
			</div>
			";
		}
		
		return $content;
	}
	
}