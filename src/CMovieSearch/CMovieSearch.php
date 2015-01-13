<?php 
/** 
 *    A Class for outputting HTML-form for movie  
 */ 
class CMovieSearch { 
    /** 
     *    Members 
     */ 
    private $title         = null; 
    private $genre         = null; 
    private $year1        = null; 
    private $year2        = null; 
    private $hits        = null; 
    private $page        = null; 
    private $sqlQuery    = null; 
    private $params        = array(); 
    private $db            = null; 
    private $rows         = null; 
    private $max         = null; 

    /** 
     *    Functions 
     */ 

    /** 
     *    Contructor 
     * 
     *    @param database connection $database. 
     *    @param string $title sets the title for the search. 
     *    @param string $genre sets the genre for the search. 
     *    @param string $year1 sets the start year for the search. 
     *    @param string $year2 sets the end year for the search. 
     *    @param integer $hits the amount of objects fetched. 
     *    @param integer $page the page number of the search. 
     */ 

    public function __construct($database=null, $title=null, $genre=null, $year1=null,$year2=null, $hits=8, $page=null) { 
        // Connect to the database objekt passed as a parameter 
        $this->db = $database; 
        $this->title = $title; 
        $this->genre = $genre; 
        $this->year1 = $year1; 
        $this->year2 = $year2; 
        $this->hits = $hits; 
        $this->page = $page; 
    } 
	
	
	public function getActiveGenres(){
		// Gets the available genres 
        $genreSql = ' 
            SELECT DISTINCT G.name 
            FROM Genre as G 
                INNER JOIN Movie2Genre AS M2G 
                    ON G.id = M2G.idGenre 
        '; 
        // Queries the database 
        $res = $this->db->ExecuteSelectQueryAndFetchAll($genreSql); 

        $genres = null; 
        foreach($res as $val) { 
             if($val->name == $this->genre) { 
                $genres .= "$val->name "; 
              } 
              else { 
                $genres .= "<a href='movies.php" . self::getQueryString(array('genre' => $val->name)) . "'>{$val->name} </a> "; 
              } 
        }
		return $genres;
	}
	
	
    /** 
     *    Renders the search form 
     * 
     *    @return returns $html with the form. 
     */ 

    public function RenderSearchForm() { 

        // Builds the form 
        $html = <<<EOD
			<hr></hr>
			<form>
			<div class="row">
				<div class="col-sm-6">
					<div id="imaginary_container"> 
						<div class="input-group stylish-input-group">
							<input type="search" id="title" name='title' value='{$this->title}' class="form-control"  placeholder="Sök med titel (delsträng, använd % som *)" >
							<span class="input-group-addon">
								<button type="submit">
									<span class="glyphicon glyphicon-search"></span>
								</button>  
							</span>
						</div>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="input-group stylish-input-group">
						<input type="text" name='year1' class="form-control" placeholder="Från år" value="{$this->year1}">  
					</div>
				</div>
				<div class="col-sm-2">
					<div class="input-group stylish-input-group">
						<input type="text" name='year2' class="form-control" placeholder="Till år" value="{$this->year2}">
					</div>
				</div>
				<div class="col-sm-1">
					<input type="submit" name='submit' class="form-control btn-primary" value="Sök">
				</div>
				<div class="col-sm-1">
					<a class="btn btn-primary btn-md" href='?'>Visa alla</a> 
				</div>

			</div>
			<hr></hr>
			<div class="row">
				<div class="col-sm-12">
					Genre: {$this->getActiveGenres()}
					<hr></hr>
				</div>
			</div>
			</form>	
EOD;
        return $html; 
    } 

    /** 
     *    Builds the query that should be used to search for the movie 
     * 
     *    @param string $orderby what the query should be ordered by. 
     *    @param string $order ascending or descending order. 
     */ 
    public function BuildQuery($orderby, $order) { 
        // Prepare the query based on incoming arguments 
        $sqlOrig = ' 
            SELECT  
                M.*, 
                GROUP_CONCAT(G.name) AS genre 
            FROM Movie AS M 
                LEFT OUTER JOIN Movie2Genre AS M2G 
                    ON M.id = M2G.idMovie 
                INNER JOIN Genre AS G 
                    ON M2G.idGenre = G.id 
        '; 

        $where             = null; 
        $groupby         = ' GROUP BY M.id'; 
        $limit            = null; 
        $sort             = " ORDER BY $orderby $order"; 
        $this->params     = array(); 

        // Select by title 
        if ($this->title) { 
            $where .= ' AND title LIKE ?'; 
            $this->params[] = $this->title; 
        } 

        // Select by year 
        if($this->year1) { 
          $where .= ' AND year2 >= ?'; 
          $this->params[] = $this->year1; 
        }  
        if($this->year2) { 
          $where .= ' AND year2 <= ?'; 
          $this->params[] = $this->year2; 
        }  
        // Select by genre 
        if ($this->genre) { 
            $where .= ' AND G.name = ?'; 
            $this->params[] = $this->genre; 
        } 

        // Pagination 
        if ($this->hits && $this->page) { 
            $limit = " LIMIT $this->hits OFFSET " . (($this->page - 1) * $this->hits); 
        } 

        // Complete the SQL statement 
        $where = $where ? " WHERE 1 {$where}" : null; 
        // Set the sql query 
        $this->sqlQuery = $sqlOrig . $where . $groupby . $sort . $limit; 
        // Get the number of rows and set it to the property rows 
        $rowSql = " 
            SELECT 
            COUNT(id) AS rows 
            FROM 
            ( 
                $sqlOrig $where $groupby  
            ) AS Movie 
        "; 
        $rowRes = $this->db->ExecuteSelectQueryAndFetchAll($rowSql, $this->params); 
        $this->rows = $rowRes[0]->rows; 
        $this->max = ceil($this->rows / $this->hits); 
    } 

    /** 
     *    Gets the number of rows from the CMovieSearch object. 
     *     
     *    @return integer $rows. 
     */ 
    public function GetRows() { 
        return $this->rows; 
    } 

    /** 
     *    Gets the max objects for a CMovieSearch object. 
     *     
     *    @return integer $max. 
     */ 
    public function GetMax() { 
        return $this->max; 
    } 

    /** 
     *    Gets the query from the CMovieSearch object. 
     *     
     *    @return string $sqlQuery. 
     */ 
    public function GetQuery() { 
        return $this->sqlQuery; 
    } 

    /** 
     *    Gets the params from the CMovieSearch object. 
     *     
     *    @return mixed array $params. 
     */ 
    public function GetParams() { 
        return $this->params; 
    }
	
	public function getQueryString($options=array(), $prepend='?') {
		// parse query string into array
		$query = array();
		parse_str($_SERVER['QUERY_STRING'], $query);

		// Modify the existing query string with new options
		$query = array_merge($query, $options);

		// Return the modified querystring
		return $prepend . htmlentities(http_build_query($query));
	}

} 

?> 