<?php
/**
* Class that handles multiple users, adding and removing and editing users.
*/
class CUserAdmin extends CUser{
	
	private $db;
	
	public function __construct($db){
		$this->db = $db;
	}
	
	public function createUser($acronym, $name, $pwd) {
		$output = null;
			
		$sql = "SELECT * FROM user WHERE acronym = ?;";
		$params = array($acronym);
		$res = $this->db->ExecuteSelectQueryAndFetchAll($sql, $params);
		if(isset($res[0]))
		{
			return $output .= "<div class='alert alert-danger' role='alert'>Det gick inte att skapa ditt konto då det redan finns ett konto med acronymet {$acronym}</div>";
		}
		
		$sql = "INSERT INTO user (acronym, name, salt) values (?, ?, unix_timestamp());";
		$params = array($acronym, $name);
		$this->db->ExecuteQuery($sql, $params);
		
		$sql = "UPDATE user SET password = md5(concat('{$pwd}', salt)) WHERE acronym = '{$acronym}';";
		$this->db->ExecuteQuery($sql);
		$output = "<div class='alert alert-success' role='alert'>Välkommen {$name}! Logga in med dina uppgifter under fliken <a href='login.php'>Logga in</a></div>";
		return $output;
		
	}
	
	public function removeUser($id) {
		$sql = 'DELETE FROM user WHERE id = ? LIMIT 1';
		$this->db->ExecuteQuery($sql, array($id));
		
		header('Location: admin.php');
	}
	
	public function updateUser ($acronym, $name, $id) {
		$sql = 'UPDATE user SET acronym  = ?, name = ? WHERE id = ?;';
		$params = array($acronym, $name, $id);
		$this->db->ExecuteQuery($sql, $params);
	}
	
	public function showAllContent(){
		$sql = 'SELECT * FROM user;';
		$res = $this->db->ExecuteSelectQueryAndFetchAll($sql);
		
		if($res)
		{
			$list = null;
			foreach($res as $key => $val)
			{
				$list .= "<li>". htmlentities($val->acronym, null, 'UTF-8') . "({$val->name})" . " (<a href='edit_user.php?id={$val->id}'>editera</a> <a href=user.php?id={$val->id}>visa</a> <a href='delete_user.php?id={$val->id}'>ta bort</a>)</li>\n";
			}
		}
		return $list;
	}
	
	public function showAllContentInTable(){
		$sql = 'SELECT * FROM user;';
		$res = $this->db->ExecuteSelectQueryAndFetchAll($sql);
		
		if($res)
		{
			$table = "<thead><tr><th>Namn</th><th>Användarnamn</th><th>Användartyp</th></tr></thead>";
			foreach($res as $key => $val)
			{
				$table .= "<tr><td><a href='user.php?id={$val->id}'>{$val->name}</a></td><td>{$val->acronym}</td><td>{$val->usertype}</td></tr>";
			}
		}
		return $table;
	}
	
	public function showContent($id){
		$sql = "SELECT * FROM user WHERE id = ?";
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
}