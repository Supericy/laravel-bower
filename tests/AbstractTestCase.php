<?php
/**
 * Created by PhpStorm.
 * User: Chad
 * Date: 11/9/2014
 * Time: 6:52 AM
 */

abstract class AbstractTestCase extends PHPUnit_Framework_TestCase {

	protected function assertException(callable $callback, $expectedException = 'Exception', $expectedCode = NULL, $expectedMessage = NULL)
	{
		if (!class_exists($expectedException) || interface_exists($expectedException))
		{
			$this->fail("An exception of type '$expectedException' does not exist.");
		}

		try
		{
			$callback();
		} catch (\Exception $e)
		{
			$class = get_class($e);
			$message = $e->getMessage();
			$code = $e->getCode();

			$extraInfo = $message ? " (message was $message, code was $code)" : ($code ? " (code was $code)" : '');
			$this->assertInstanceOf($expectedException, $e, "Failed asserting the class of exception$extraInfo.");

			if (NULL !== $expectedCode)
			{
				$this->assertEquals($expectedCode, $code, "Failed asserting code of thrown $class.");
			}
			if (NULL !== $expectedMessage)
			{
				$this->assertContains($expectedMessage, $message, "Failed asserting the message of thrown $class.");
			}
			return;
		}

		$extraInfo = $expectedException !== 'Exception' ? " of type $expectedException" : '';
		$this->fail("Failed asserting that exception$extraInfo was thrown.");
	}

} 