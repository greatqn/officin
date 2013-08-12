<?php
 

class StackTest2 extends PHPUnit_Framework_TestCase
{
	public function testAA()
	{
		$stack = array();
        $this->assertEquals(10, count($stack));
	}

    public function testPushAndPop()
    {
        $stack = array();
        $this->assertEquals(10, count($stack));
 
        array_push($stack, 'foo');
        $this->assertEquals('foo', $stack[count($stack)-1]);
        $this->assertEquals(1, count($stack));
 
        $this->assertEquals('foo', array_pop($stack));
        $this->assertEquals(0, count($stack));
    }
}

class PerformanceTest extends PHPUnit_Extensions_PerformanceTestCase
{
	public function testPerformance()
	{
		$this->setMaxRunningTime(2);
		sleep(1);
	}
}
?>