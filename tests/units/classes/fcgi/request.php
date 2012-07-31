<?php

namespace mageekguy\atoum\tests\units\fcgi;

use
	mageekguy\atoum,
	mock\mageekguy\atoum\fcgi,
	mock\mageekguy\atoum\fcgi\request as testedClass
;

require_once __DIR__ . '/../../runner.php';

class request extends atoum\test
{
	public function test__construct()
	{
		$this
			->if($request = new testedClass())
			->then
				->array($request->getParams())->isEmpty()
				->string($request->getStdin())->isEmpty()
		;
	}

	public function test__invoke()
	{
		$this
			->if($this->mockGenerator
				->shunt('openConnection')
				->shunt('closeConnection')
				->shunt('sendData')
				->shunt('receiveData')
			)
			->and($client = new \mock\mageekguy\atoum\fcgi\client())
			->and($client->getMockController()->sendData = $client)
			->and($client->getMockController()->receiveData = false)
			->and($request = new testedClass())
			->then
				->variable($request($client))->isNull()
				->mock($client)->call('sendData')->never()
			/*
			->if($request->stdin = $stdin = uniqid())
			->then
				->object($response = $request($client))->isInstanceOf('mageekguy\atoum\fcgi\response')
				->mock($client)->call('sendData')
					->withArguments("           ")->once()
					->withArguments("    " . $stdin)->once()
					->withArguments("     ")->once()
			->if($client->getMockController()->resetCalls())
			->and($request->CONTENT_LENGTH = 123)
			->then
				->object($response = $request($client))->isInstanceOf('mageekguy\atoum\fcgi\response')
				->mock($client)->call('sendData')
					->withArguments("           ")->once()
					->withArguments("    CONTENT_LENGTH123")->once()
					->withArguments("     ")
					->withArguments("    " . $stdin)->once()
					->withArguments("     ")->once()
			*/
		;
	}

	public function test__set()
	{
		$this
			->if($request = new testedClass())
			->and($request->stdin = $stdin = uniqid())
			->then
				->string($request->getStdin())->isIdenticalTo($stdin)
			->if($request->content_length = $contentLength = uniqid())
			->then
				->array($request->getParams())->isEqualTo(array(
						'CONTENT_LENGTH' => $contentLength
					)
				)
		;
	}

	public function test__get()
	{
		$this
			->if($request = new testedClass())
			->then
				->string($request->stdin)->isEmpty()
			->if($request->stdin = $stdin = uniqid())
			->then
				->string($request->stdin)->isEqualTo($stdin)
			->if($request->content_length = $contentLength = uniqid())
			->then
				->string($request->content_length)->isEqualTo($contentLength)
				->string($request->cONTENT_LENGTH)->isEqualTo($contentLength)
		;
	}

	public function test__isset()
	{
		$this
			->if($request = new testedClass())
			->then
				->boolean(isset($request->stdin))->isFalse()
				->boolean(isset($request->content_length))->isFalse()
				->boolean(isset($request->CONTENT_LENGTH))->isFalse()
			->if($request->stdin = uniqid())
			->then
				->boolean(isset($request->stdin))->isTrue()
				->boolean(isset($request->content_length))->isFalse()
				->boolean(isset($request->CONTENT_LENGTH))->isFalse()
			->if($request->content_length = uniqid())
			->then
				->boolean(isset($request->stdin))->isTrue()
				->boolean(isset($request->content_length))->isTrue()
				->boolean(isset($request->CONTENT_LENGTH))->isTrue()
		;
	}

	public function test__unset()
	{
		$this
			->if($request = new testedClass())
			->when(function() use ($request) { unset($request->stdin); })
			->then
				->boolean(isset($request->stdin))->isFalse()
			->when(function() use ($request) { unset($request->content_length); })
			->then
				->boolean(isset($request->content_length))->isFalse()
			->when(function() use ($request) { unset($request->CONTENT_LENGTH); })
			->then
				->boolean(isset($request->CONTENT_LENGTH))->isFalse()
			->if($request->stdin = uniqid())
			->when(function() use ($request) { unset($request->stdin); })
			->then
				->boolean(isset($request->stdin))->isFalse()
			->when(function() use ($request) { unset($request->content_length); })
			->then
				->boolean(isset($request->content_length))->isFalse()
			->when(function() use ($request) { unset($request->CONTENT_LENGTH); })
			->then
				->boolean(isset($request->CONTENT_LENGTH))->isFalse()
			->if($request->content_length = uniqid())
			->when(function() use ($request) { unset($request->stdin); })
			->then
				->boolean(isset($request->stdin))->isFalse()
			->when(function() use ($request) { unset($request->content_length); })
			->then
				->boolean(isset($request->content_length))->isFalse()
			->when(function() use ($request) { unset($request->CONTENT_LENGTH); })
			->then
				->boolean(isset($request->CONTENT_LENGTH))->isFalse()
		;
	}

	public function testSetStdin()
	{
		$this
			->if($request = new testedClass())
			->then
				->object($request->setStdin($stdin = uniqid()))->isIdenticalTo($request)
				->string($request->getStdin())->isEqualTo($stdin)
		;
	}

	public function testSendWithClient()
	{
		$this
			->if($this->mockGenerator
				->shunt('openConnection')
				->shunt('closeConnection')
				->shunt('sendData')
				->shunt('receiveData')
			)
			->and($client = new \mock\mageekguy\atoum\fcgi\client())
			->and($client->getMockController()->openConnection = $client)
			->and($client->getMockController()->closeConnection = $client)
			->and($client->getMockController()->sendData = $client)
			->and($client->getMockController()->receiveData[1] = "\001\006\000\001\000b\006\000")
			->and($client->getMockController()->receiveData[2] = "X-Powered-By: PHP/5.4.5\r\nContent-type: text/html\r\n\r\narray(1) {\n  [\"query\"]=>\n  string(4) \"1234\"\n}\n\000\000\000\000\000\000")
			->and($client->getMockController()->receiveData[3] = "\001\003\000\001\000b\000\000")
			->and($client->getMockController()->receiveData[4] = "\000\000\000\000\000\000\000\000")
			->and($request = new testedClass())
			->then
				->variable($request->sendWithClient($client))->isNull()
				->mock($client)->call('sendData')->never()
			->if($request->stdin = $stdin = uniqid())
			->then
				->object($response = $request->sendWithClient($client))->isInstanceOf('mageekguy\atoum\fcgi\response')
				->mock($client)->call('sendData')
					->withArguments("           ")->once()
					->withArguments("    " . $stdin)->once()
					->withArguments("     ")->once()
			->if($client->getMockController()->resetCalls())
			->and($request->CONTENT_LENGTH = 123)
			->then
				->object($response = $request->sendWithClient($client))->isInstanceOf('mageekguy\atoum\fcgi\response')
				->mock($client)->call('sendData')
					->withArguments("           ")->once()
					->withArguments("    CONTENT_LENGTH123")->once()
					->withArguments("     ")
					->withArguments("    " . $stdin)->once()
					->withArguments("     ")->once()
		;
	}
}
