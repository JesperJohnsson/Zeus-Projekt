<?php
/**
*	CDiceGame class
*
*/
 
class CDiceGame{ 
	/**
	*	Properties
	*
	*/
	//private $currentTurnPoint;
	private $currentRoundPoints;
	private $savedPoints;
	private $index;
	/**
	*	Constructor
	*
	*/
	public function __construct()
	{
		$this->index 				= 1;
		$this->currentRoundPoints 	= 0;
		//$this->savedPoints 			= 0;
	}
	
	/**
	*	setPoints 
	*
	*	@param int $points 
	*/
    public function setPoints($points)
	{
		if($points != 1)
        {
            $this->currentRoundPoints += $points;
        }
        else
        {
            $this->currentRoundPoints = 0;
			$this->index++;
			return $html = '<div style="margin-top:10px;" class="alert alert-danger text-center" role="alert"><p>Du fick en etta dina poäng denna rundan har försvunnit!</p></div>';
        }
		
    } 
	public function getIndex(){
		return $this->index;
	
	}
	public function getPoints(){
		return $this->currentRoundPoints;
    }

    public function getTotalPoints(){
		return $this->savedPoints + $this->currentRoundPoints;
    }

    public function savePoints(){
		$this->savedPoints += $this->currentRoundPoints;
		$this->currentRoundPoints = 0;
		$this->index++;
    }

    public function resetPoints(){
		$this->index 				= 1;
		$this->currentRoundPoints 	= 0;
		$this->savedPoints 			= 0;
    } 
}
?>