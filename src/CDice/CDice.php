<?php
/**
*	CDice class
*
*/

class CDice
{
	/**
	*	Properties
	*
	*/
	private $faces;
	private $value;

	/**
	*	Constructor
	*
	*	@param int $faces the number of faces to use.
	*/
	public function __construct($faces=6) {
		$this->faces = $faces;
	}
	
    public function Roll()
	{
        $this->value = rand(1, 6);
        return $this->value;
    }
	
	public function RenderDice()
	{
        $html = "<p><strong>Senaste Slag:</strong></p><ul class='dice'><li class='dice-".$this->value."'></li></ul><hr>";
        return $html;
    }

	
}