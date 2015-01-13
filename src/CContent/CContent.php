<?php
/**
 *	CContent, a class to store content in a database
 *
 */
class CContent {

	/**
	*	Members
	*/
	private $db;
	
	
	/**
	*	Constructor
	*
	*
	*/
	public function __construct($db)
	{
		$this->db = $db;
	}
	
	
	
	/**
	*	initiateDatabase, creates the structure of the database content
	*
	*	@returns a bool if succeeded
	*
	*/
	
	public function initiateDatabase()
	{
		
		$sql = " 
		-- Create table for Content 
		DROP TABLE IF EXISTS Content; 
		CREATE TABLE Content
		( 
			id INT AUTO_INCREMENT PRIMARY KEY NOT NULL, 
			slug CHAR(80) UNIQUE, 
			url CHAR(80) UNIQUE, 

			type2 CHAR(80), 
			title VARCHAR(80),
			data2 TEXT, 
			filter CHAR(80), 

			published DATETIME, 
			created DATETIME, 
			updated DATETIME, 
			deleted DATETIME 

		) ENGINE INNODB CHARACTER SET utf8; 
		"; 
		if($this->db->ExecuteQuery($sql))
		{
			$sql = "SHOW TABLES LIKE 'Content'";
			$res = $this->db->ExecuteSelectQueryAndFetchAll($sql);
			if($res)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}
	
	
	/**
	*	resetDatabase, restores the original values of the database table content
	*	
	*	@returns a message if succeeded
	*
	*/
	public function resetDatabase()
	{
		
		$initiateDatabase = $this->initiateDatabase();
		if($initiateDatabase)
		{
			$sql = " 
                INSERT INTO Content (slug, url, type2, title, data2, filter, published, created) VALUES 
  ('blogpost-1', NULL, 'post', 'Välkommen till min blogg!', 'Detta är en bloggpost.\n\nNär det finns länkar till andra webbplatser så kommer de länkarna att bli klickbara.\n\nhttp://dbwebb.se är ett exempel på en länk som blir klickbar.', 'link,nl2br', NOW(), NOW()), 
  ('blogpost-2', NULL, 'post', 'Nu har sommaren kommit', 'Detta är en bloggpost som berättar att sommaren har kommit, ett budskap som kräver en bloggpost.', 'nl2br', NOW(), NOW()), 
  ('blogpost-3', NULL, 'post', 'Nu har hösten kommit', 'Detta är en bloggpost som berättar att sommaren har kommit, ett budskap som kräver en bloggpost', 'nl2br', NOW(), NOW()); 
            "; 
             
            $this->db->ExecuteQuery($sql); 
			return "Databasen kunde återställas.";
		}
		else
		{
			return "Databasen kunde inte återställas.";
		}
	}
	
	
	/**
	 * Create a link to the content, based on its type.
	 *
	 * @param object $content to link to.
	 * @return string with url to display content.
	 */
	public function getUrlToContent($content) 
	{
		switch($content->type2) 
		{
			case 'page': return "page.php?url={$content->url}"; break;
			case 'post': return "blog.php?slug={$content->slug}"; break;
			default: return null; break;
		}
	}
	
	
    /** 
     * Get all content as a list 
     *  
     * @return string html code for list with items 
     */ 	
	public function showAllContent()
	{
		$sql = "SELECT *, (published <= NOW()) AS available, (deleted <= NOW()) AS removed FROM Content;";
		$res = $this->db->ExecuteSelectQueryAndFetchAll($sql);
		
		if($res)
		{
			$list = null;
			foreach($res as $key => $val)
			{
				$list .= "<li>" . ($val->removed ? ' (borttagen) ' : null) . "(" . (!$val->available ? 'inte ' : null) . "publicerad): " . htmlentities($val->title, null, 'UTF-8') . " (<a href='edit.php?id={$val->id}'>editera</a>". (!$val->removed ? " <a href='" . $this->getUrlToContent($val) . "'>visa</a> <a href='delete.php?id={$val->id}'>ta bort</a>" : "<a href='restore.php?id={$val->id}'> återställ</a>") . ")</li>\n";
			}
		}
		return $list;
	}
	
	
	/** 
     * Get content for id 
     *  
     * @params int $id for content to find 
     * @return all content for specific id 
     */ 
	public function showContent($id)
	{
		$sql = "SELECT * FROM Content WHERE id = ?";
		$params = array($id);
		$res = $this->db->ExecuteSelectQueryAndFetchAll($sql, $params);
		
		if(isset($res[0]))
		{
			$c = $res[0];
		}
		else
		{
			die('Misslyckades: id saknas.');
		}
		return $c;
	}
	
	
    /** 
     * Uppdate content 
     *  
     * @params array $params values to be updated 
     * $return string message if update is executed or not 
     */	
	public function updateContent()
	{
		//Get the parameters of the form, only valid if the form has been submitted
		$id = isset($_POST['id'])    ? strip_tags($_POST['id']) : (isset($_GET['id']) ? strip_tags($_GET['id']) : null);
		$title = isset($_POST['title']) ? $_POST['title'] : null;
		$slug = isset($_POST['slug'])  ? $_POST['slug']  : null;
		$url = isset($_POST['url'])   ? strip_tags($_POST['url']) : null;
		$data2 = isset($_POST['data2'])  ? $_POST['data2'] : array();
		$type2 = isset($_POST['type2'])  ? strip_tags($_POST['type2']) : array();
		$filter = isset($_POST['filter']) ? $_POST['filter'] : array();
		$published = isset($_POST['published'])  ? strip_tags($_POST['published']) : array();
		
		
		$sql = '
			UPDATE Content SET
				title = ?,
				slug = ?,
				url = ?,
				data2 = ?,
				type2 = ?,
				filter = ?,
				published = ?,
				updated = NOW()
			WHERE 
				id = ?';
				
		$url = empty($url) ? null : $url;
		$filter = empty($filter) ? 'markdown' : $filter;
		$params = array($title, $slug, $url, $data2, $type2, $filter, $published, $id);
		
		$res = $this->db->ExecuteQuery($sql, $params);
		if($res) 
		{
			$output = 'Informationen sparades.';
		}
		else 
		{
			$output = 'Informationen sparades EJ.<br><pre>' . print_r($db->ErrorInfo(), 1) . '</pre>';
		}
		
		return $output;
	}
	
	/** 
	* Delete content 
	*  
	* @param int the content id to be deleted 
	* @return string 
	*/
	public function deleteContent($id, $title)
	{
		$sql =  '
        UPDATE Content SET 
            published = null, 
            deleted = NOW() 
        WHERE  
            id = ?'; 
        $params = array($id); 
        $res = $this->db->ExecuteQuery($sql, $params); 
         
        if($res) { 
            return '"' . $title . '"' . ' har tagits bort'; 
        } 
        else { 
            return "Sidan kan inte tas bort"; 
        }
	}
	
	/** 
	* Restore content 
	*  
	* @param int the content id to be restored 
	* @return string 
	*/
	public function restoreContent($id, $title)
	{
		$sql =  '
        UPDATE Content SET 
            published = NOW(),
			updated = null,
            deleted = null
        WHERE  
            id = ?'; 
        $params = array($id); 
        $res = $this->db->ExecuteQuery($sql, $params); 
         
        if($res) { 
            return '"' . $title . '"' . ' har återställts bort'; 
        } 
        else { 
            return "Sidan kan inte återställas"; 
        }
	}


    /** 
    * Insert title and slug into database  
    *  
    * @param string the title 
    * @return string the title created 
    */ 
    public function create($title) { 
         
        $slug = strip_tags($title); 
        $slug = $this->slugify($title); 
         
        $sql = "INSERT INTO Content(title, slug, created) VALUES(?, ?, NOW())"; 
         
        $params = array($title, $slug); 
        $res = $this->db->ExecuteQuery($sql, $params); 
         
		header('Location: edit.php?id=' . $this->db->LastInsertId());
    } 	
	
	/** 
    * Create a slug of a string, to be used as url. 
    *  
    * @param string $str the string to format as slug. 
    * @returns str the formatted slug. 
    */ 
    public function slugify($str) { 
         
        // String to lowercase and trim whitespace 
        $str = mb_strtolower(preg_replace("/(^\s+)|(\s+$)/us", "", $str), "UTF-8"); 
        // Replace å, ä, ö with a, a, o 
        $arr = preg_split('//u', $str, -1, PREG_SPLIT_NO_EMPTY); 
        $len = count($arr); 
        for($i=0; $i<$len; $i++) { 
            if($arr[$i] == 'å') { 
                $arr[$i] = 'a'; 
            } 
         
            if($arr[$i] == 'ä') { 
                $arr[$i] = 'a'; 
            } 
         
            if($arr[$i] == 'ö') { 
                $arr[$i] = 'o'; 
            } 
        } 
        $str = implode('',$arr); 
         
        $str = preg_replace('/[^a-z0-9-]/', '-', $str); 
        $str = trim(preg_replace('/-+/', '-', $str), '-'); 
        return $str; 
    } 
	
	
	
}