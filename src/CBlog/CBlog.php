<?php 
/* 
 * Class to represent content on webpage 
 */ 
  
class CBlog extends CContent { 

	/**
	* Members
	*/
	private $db;
	private $filter;
	private $title;
	
	
    /** 
    * Constructor 
    */ 
    public function __construct($db, $filter=null) { 
        $this->db = $db; 
        $this->filter = $filter;     
    }  
	
	/** 
	* Show blog post 
	*  
	* @param string $slug for post 
	*  
	*/ 
	public function showPost($slug, $acronym)
	{
		$content = null;
		$editLink = null;
		$deleteLink = null;
		
		$slugsql = $slug ? 'slug = ?' : 1;
		$sql = "
		SELECT *
		FROM Content
		WHERE
		  type2 = 'post' AND
		  $slugsql AND
		  published <= NOW()
		ORDER BY updated DESC
		;
		";
		$params = array($slug);
		$res = $this->db->ExecuteSelectQueryAndFetchAll($sql, $params);
		
		if(isset($res[0])) 
		{
			foreach($res as $c) 
			{
				// Sanitize content before using it.
				$title  = htmlentities($c->title, null, 'UTF-8');
				$this->title = $title;
				$data   = $this->filter->doFilter(htmlentities($c->data2, null, 'UTF-8'), $c->filter);
				$readmore = null;
				if(!$slug)
				{
					$data = implode(' ', array_slice(explode(' ', $data), 0, 15));
					$data .= "....";
					$readmore = "<hr></hr><p><a class='btn btn-default' href='blog.php?slug={$c->slug}'>Läs mer >></a><p>";
				}	
				if($c->updated != null) 
				{ 
					$c->updated = "| Uppdaterad $c->updated";
				}
				if($acronym)
				{
					$editLink = "<hr></hr><a href='edit.php?id={$c->id}'>Uppdatera nyheten </a>";
					$deleteLink = "<a href='delete.php?id={$c->id}' style='margin-left:25px;'> Ta bort nyheten</a>";
				}
				
				
			    $content .= " 
				<section style='border:1px solid #EEEEEE; margin-bottom:25px; padding-left:10px; padding-bottom:20px'> 
					<article class='bloggen'>
						
						<header> 
							<h1><a href='blog.php?slug={$c->slug}'>{$c->title}</a></h1>
							<hr></hr>
							<p class='published'>Publicerad $c->published $c->updated </p>  
						</header>
						

							{$data}
							{$readmore}
						 
						<footer>
							
							{$editLink}{$deleteLink}
						</footer>
					 
					</article> 
				</section> 
				"; 
			}
		}
		elseif($slug)
		{
			$content .= "Det fanns inte en sådan bloggpost.";
		}
		else 
		{
			$content .= "Det fanns inga bloggposter.";
		}
		return $content;
	}
	
	public function showHomePost() {
		
		$sql = "
		SELECT *
		FROM Content
		WHERE
		  type2 = 'post' AND
		  published <= NOW()
		ORDER BY updated DESC
		LIMIT 3;
		";
		$res = $this->db->ExecuteSelectQueryAndFetchAll($sql);
		
		$content = "<div class='col-md-12 text-center frontpageheader'><h1>Senaste Nyheter</h1></div>";
		
		
		foreach($res as $c)
		{
			$content .= "
			<div class='col-md-12 b'>
				<section> 
					<article>
						<header> 
							<h2 class='c' style='border:1px solid #EEEEEE;padding:25px;'><a href='blog.php?slug={$c->slug}'>{$c->title}</a><a href='blog.php?slug={$c->slug}' class='btn btn-default pull-right'>Läs mer >></a></h2>
						</header>
					</article> 
				</section> 
			</div>
			";
		}
		
		
		
		return $content;
	
	}


	/**
	* Create a breadcrumb to the blog.
	*
	* @slug, the current slug to the post
	* @return string html with ul/li to display the thumbnail.
	*/
	public function createBreadcrumb($slug = null) {
	  //$parts = explode('/', trim(substr($path, strlen(GALLERY_PATH) + 1), '/')); //var tvungen att ändra '/' till '\\' då fungerade det för mig
	  $breadcrumb = "<ul class='breadcrumb'>\n<li><a href='?'>Nyheter</a></li>\n";
	 
	  if(!empty($slug)) {
		//$combine .= ($combine ? '/' : null) . $part;
		$breadcrumb .= "<li><a href='?slug={$slug}'>$this->title</a> </li>\n";
	  }
	 
	  $breadcrumb .= "</ul>\n";
	  return $breadcrumb;
	}
}