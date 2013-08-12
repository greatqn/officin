<?php
 
//d:\xampp\php\phpunit stringtest
class StringTest extends PHPUnit_Framework_TestCase
{
	function str_cut($s,$a,$d="")
	{
		$f=strpos($s,$a)+strlen($a);
		$l=strpos($s,$d);
		if($l)
			$out= substr($s,$f,$l-$f);

		return $out;
	}
	public function testAA()
	{


		$stack = "[,3,]|{,2,}";
		$this->assertEquals("3", $this->str_cut($stack,"[,",",]"));
		$stack = "{,2,}";
		$this->assertEquals("", $this->str_cut($stack,"[,",",]"));
		$stack = "[,2,3,]|{,2,}";
		$this->assertEquals("2,3", $this->str_cut($stack,"[,",",]"));
	}

	public function testBB()
	{


		$stack = "[,3,]|{,2,}";
		$this->assertEquals("2", $this->str_cut($stack,"{,",",}"));
		$stack = "{,2,}";
		$this->assertEquals("2", $this->str_cut($stack,"{,",",}"));
		$stack = "[,2,3,]|{,2,1,}";
		$this->assertEquals("2,1", $this->str_cut($stack,"{,",",}"));
	}

	public function testCC()
	{


		$stack = "2";
		var_dump(explode(",",$stack));
		$stack = "2,3";
		var_dump(explode(",",$stack));

		$this->assertEquals("2", $this->str_cut($stack,"{,",",}"));
		$stack = "{,2,}";
		$this->assertEquals("2", $this->str_cut($stack,"{,",",}"));
		$stack = "[,2,3,]|{,2,1,}";
		$this->assertEquals("2,1", $this->str_cut($stack,"{,",",}"));
	}

}

?>