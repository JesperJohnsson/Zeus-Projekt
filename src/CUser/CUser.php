<?php
/**
 * Helperclass for login/logout
 *
 */
class CUser {

    /**
	* Members
	*/
    private $db;
	private $acronym;
    
    /**
    * Constructor .
    */
    public function __construct($database) {
        //Create a instance of db to handle db connection
        $this->db = new CDatabase($database);
        //Check Status.
        if(isset($_POST['login'])) {
            $acronym = isset($_POST['acronym']) ? $_POST['acronym'] : null;
            $password = "Intentionally removed by CSource";
            $this->Login($acronym, $password);
            //header('Location: movie_login.php')
            header('Location: ?');
        }
        elseif(isset($_POST['logout'])) 
        {
            $this->Logout();
            header('Location: ?');
        }
    }
    
	/*
	* Function to display the settings of the current user
	*/
	public function userSettings(){
		$sql = "SELECT * FROM user WHERE acronym = '{$this->GetAcronym()}';";
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql);
		
		$this->setName($res[0]->acronym);
		$html = null;
		

			$html = "
			<div class='row'>
				<div class='col-md-offset-3 col-md-6'>
					<table class='table table-bordered table-hover'>
					  <tr>
						<th>Namn:</th>
						<td>{$res[0]->name}</td>
					  </tr>
					  <tr>
						<th>Användarnamn:</th>
						<td>{$res[0]->acronym}</td>
					  </tr>
					  <tr>
						<th>Användartyp:</th>
						<td>{$res[0]->usertype}</td>
					  </tr>
					</table>
				</div>
			</div>
			";
		
		return $html;
	}
	
    /*
    * Function to check login information
    */
    public function Login($user, $password)
    {
        $sql = "SELECT id, acronym, name, usertype FROM user WHERE acronym = ? AND password = md5(concat(?, salt))";
        $res = $this->db->ExecuteSelectQueryAndFetchAll($sql, array($user, $password));
        
        if(isset($res[0])) {
            $_SESSION['user'] = $res[0];
			//$this->acronym = $_SESSION['user']->acronym;
        }
    }
    /*
    * Function to logout
    */
    public function Logout() 
    {
        $_SESSION['user'] = null;
    }

    /*
    * Function to check if user is logged in
    */
    public function IsAuthenticated()
    {
        return isset($_SESSION['user']);
    }
	
	/*
    * Function to get user type
    */
    public function GetUserType()
    {
		return isset($_SESSION['user']) ? $_SESSION['user']->usertype : null;
    }
	
	
	public function IsAdmin()
	{
		if($this->GetUserType() == "admin")
			return true;
		return false;
	}
	
    /*
    * Function to get acronym of the logged in user
    */
    public function GetAcronym() 
    {
        return isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;
    }

    /*
    * Function to get the name of the logged in user
    */
    public function GetName()
    {
        return isset($_SESSION['user']) ? $_SESSION['user']->name : null;
    }
	
	/*
    * Function to get the id of the logged in user
    */
    public function GetId()
    {
        return isset($_SESSION['user']) ? $_SESSION['user']->id : null;
    }
	
	public function setName($acronym){
		$_SESSION['user']->acronym = $acronym;
	}
}
