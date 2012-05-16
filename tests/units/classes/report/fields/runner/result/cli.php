<?php

namespace mageekguy\atoum\tests\units\report\fields\runner\result;

use
	mageekguy\atoum,
	mageekguy\atoum\locale,
	mageekguy\atoum\cli\prompt,
	mageekguy\atoum\cli\colorizer,
	mageekguy\atoum\report\fields\runner,
	mageekguy\atoum\tests\units
;

require_once __DIR__ . '/../../../../../runner.php';

class cli extends atoum\test
{
	public function testClass()
	{
		$this->testedClass->isSubClassOf('mageekguy\atoum\report\field');
	}

	public function test__construct()
	{
		$this
			->if($field = new runner\result\cli())
			->then
				->object($field->getPrompt())->isEqualTo(new prompt())
				->object($field->getSuccessColorizer())->isEqualTo(new colorizer())
				->object($field->getFailureColorizer())->isEqualTo(new colorizer())
				->object($field->getLocale())->isEqualTo(new locale())
				->variable($field->getTestNumber())->isNull()
				->variable($field->getTestMethodNumber())->isNull()
				->variable($field->getFailNumber())->isNull()
				->variable($field->getErrorNumber())->isNull()
				->variable($field->getExceptionNumber())->isNull()
				->array($field->getEvents())->isEqualTo(array(atoum\runner::runStop))
			->if($field = new runner\result\cli(null, null, null, null))
			->then
				->object($field->getPrompt())->isEqualTo(new prompt())
				->object($field->getSuccessColorizer())->isEqualTo(new colorizer())
				->object($field->getFailureColorizer())->isEqualTo(new colorizer())
				->object($field->getLocale())->isEqualTo(new locale())
				->variable($field->getTestNumber())->isNull()
				->variable($field->getTestMethodNumber())->isNull()
				->variable($field->getFailNumber())->isNull()
				->variable($field->getErrorNumber())->isNull()
				->variable($field->getExceptionNumber())->isNull()
				->array($field->getEvents())->isEqualTo(array(atoum\runner::runStop))
			->if($field = new runner\result\cli($prompt = new prompt(), $successColorizer = new colorizer(), $failureColorizer = new colorizer(), $locale = new locale()))
			->then
				->object($field->getLocale())->isIdenticalTo($locale)
				->variable($field->getTestNumber())->isNull()
				->variable($field->getTestMethodNumber())->isNull()
				->variable($field->getFailNumber())->isNull()
				->variable($field->getErrorNumber())->isNull()
				->variable($field->getExceptionNumber())->isNull()
				->object($field->getPrompt())->isIdenticalTo($prompt)
				->object($field->getSuccessColorizer())->isIdenticalTo($successColorizer)
				->object($field->getFailureColorizer())->isIdenticalTo($failureColorizer)
				->array($field->getEvents())->isEqualTo(array(atoum\runner::runStop))
		;
	}

	public function testSetPrompt()
	{
		$this
			->if($field = new runner\result\cli())
			->then
				->object($field->setPrompt($prompt = new prompt()))->isIdenticalTo($field)
				->object($field->getPrompt())->isEqualTo($prompt)
			->if($field = new runner\result\cli(new prompt()))
			->then
				->object($field->setPrompt($prompt = new prompt()))->isIdenticalTo($field)
				->object($field->getPrompt())->isEqualTo($prompt)
		;
	}

	public function testSetSuccessColorizer()
	{
		$this
			->if($field = new runner\result\cli())
			->then
				->object($field->setSuccessColorizer($colorizer = new colorizer()))->isIdenticalTo($field)
				->object($field->getSuccessColorizer())->isIdenticalTo($colorizer)
			->if($field = new runner\result\cli(null, new colorizer()))
			->then
				->object($field->setSuccessColorizer($colorizer = new colorizer()))->isIdenticalTo($field)
				->object($field->getSuccessColorizer())->isIdenticalTo($colorizer)
		;
	}

	public function testSetFailureColorizer()
	{
		$this
			->if($field = new runner\result\cli())
			->then
				->object($field->setFailureColorizer($colorizer = new colorizer()))->isIdenticalTo($field)
				->object($field->getFailureColorizer())->isIdenticalTo($colorizer)
			->if($field = new runner\result\cli(null, null, new colorizer()))
			->then
				->object($field->setFailureColorizer($colorizer = new colorizer()))->isIdenticalTo($field)
				->object($field->getFailureColorizer())->isIdenticalTo($colorizer)
		;
	}

	public function testHandleEvent()
	{

		$this
			->if($score = new \mock\mageekguy\atoum\score())
			->and($score->getMockController()->getAssertionNumber = $assertionNumber = rand(1, PHP_INT_MAX))
			->and($score->getMockController()->getFailNumber = $failNumber = rand(1, PHP_INT_MAX))
			->and($score->getMockController()->getErrorNumber = $errorNumber = rand(1, PHP_INT_MAX))
			->and($score->getMockController()->getExceptionNumber = $exceptionNumber = rand(1, PHP_INT_MAX))
			->and($runner = new \mock\mageekguy\atoum\runner())
			->and($runner->setScore($score))
			->and($runner->getMockController()->getTestNumber = $testNumber = rand(1, PHP_INT_MAX))
			->and($runner->getMockController()->getTestMethodNumber = $testMethodNumber = rand(1, PHP_INT_MAX))
			->and($field = new runner\result\cli())
			->then
				->boolean($field->handleEvent(atoum\runner::runStart, $runner))->isFalse()
				->variable($field->getTestNumber())->isNull()
				->variable($field->getTestMethodNumber())->isNull()
				->variable($field->getAssertionNumber())->isNull()
				->variable($field->getFailNumber())->isNull()
				->variable($field->getErrorNumber())->isNull()
				->variable($field->getExceptionNumber())->isNull()
				->boolean($field->handleEvent(atoum\runner::runStop, $runner))->isTrue()
				->integer($field->getTestNumber())->isEqualTo($testNumber)
				->integer($field->getTestMethodNumber())->isEqualTo($testMethodNumber)
				->integer($field->getAssertionNumber())->isEqualTo($assertionNumber)
				->integer($field->getFailNumber())->isEqualTo($failNumber)
				->integer($field->getErrorNumber())->isEqualTo($errorNumber)
				->integer($field->getExceptionNumber())->isEqualTo($exceptionNumber)
		;
	}

	public function test__toString()
	{
		$score = new \mock\mageekguy\atoum\score();
		$scoreController = $score->getMockController();
		$scoreController->getAssertionNumber = 1;
		$scoreController->getFailNumber = 0;
		$scoreController->getErrorNumber = 0;
		$scoreController->getExceptionNumber = 0;

		$runner = new \mock\mageekguy\atoum\runner();
		$runnerController = $runner->getMockController();
		$runnerController->getScore = $score;
		$runnerController->getTestNumber = 1;
		$runnerController->getTestMethodNumber = 1;

		$locale = new \mock\mageekguy\atoum\locale();
		$localeController = $locale->getMockController();
		$localeController->_ = function ($string) use (& $noTestRunningString, & $successString, & $failureString) {
			switch ($string)
			{
				case 'No test running.':
					return $noTestRunningString = uniqid();

				case 'Success (%s, %s, %s, %s, %s) !':
					return $successString = uniqid();

				case 'Failure (%s, %s, %s, %s, %s) !':
					return $failureString = uniqid();

				default:
					return uniqid();
			}
		};

		$localeController->__ = function($singularString, $pluralString, $number) use (& $testString, & $testMethodString, & $assertionString, & $errorString, & $exceptionString) {
			switch ($singularString)
			{
				case '%s test':
					return $testString = uniqid();

				case '%s method':
					return $testMethodString = uniqid();

				case '%s assertion':
					return $assertionString = uniqid();

				case '%s error':
					return $errorString = uniqid();

				case '%s exception':
					return $exceptionString = uniqid();

				default:
					return uniqid();
			}
		};

		$prompt = new \mock\mageekguy\atoum\cli\prompt();
		$promptController = $prompt->getMockController();
		$promptController->__toString = $promptString = uniqid();

		$successColorizer = new \mock\mageekguy\atoum\cli\colorizer();
		$successColorizerController = $successColorizer->getMockController();
		$successColorizerController->colorize = $colorizedSuccessString = uniqid();

		$failureColorizer = new \mock\mageekguy\atoum\cli\colorizer();
		$failureColorizerController = $failureColorizer->getMockController();
		$failureColorizerController->colorize = $colorizedFailureString = uniqid();

		$this
			->startCase('Success with one test, one method and one assertion, no fail, no error, no exception')
			->if($localeController->resetCalls())
			->and($successColorizerController->resetCalls())
			->and($failureColorizerController->resetCalls())
			->and($promptController->resetCalls())
			->and($field = new runner\result\cli($prompt, $successColorizer, $failureColorizer, $locale))
			->then
				->castToString($field)->isEqualTo($promptString . $noTestRunningString . PHP_EOL)
				->mock($locale)->call('_')->withArguments('No test running.')->once()
				->mock($successColorizer)->call('colorize')->withArguments($noTestRunningString)->never()
				->mock($failureColorizer)->call('colorize')->never()
				->mock($prompt)->call('__toString')->once()
			->if($localeController->resetCalls())
			->and($successColorizerController->resetCalls())
			->and($failureColorizerController->resetCalls())
			->and($promptController->resetCalls())
			->and($field->handleEvent(atoum\runner::runStart, $runner))
			->then
				->castToString($field)->isEqualTo($promptString . $noTestRunningString . PHP_EOL)
				->mock($locale)->call('_')->withArguments('No test running.')->once()
				->mock($successColorizer)->call('colorize')->withArguments($noTestRunningString)->never()
				->mock($failureColorizer)->call('colorize')->never()
				->mock($prompt)->call('__toString')->once()
			->if($localeController->resetCalls())
			->and($successColorizerController->resetCalls())
			->and($failureColorizerController->resetCalls())
			->and($promptController->resetCalls())
			->and($field->handleEvent(atoum\runner::runStop, $runner))
			->then
				->castToString($field)->isEqualTo($promptString . $colorizedSuccessString . PHP_EOL)
				->mock($locale)
					->call('__')->withArguments('%s test', '%s tests', 1)->once()
					->call('__')->withArguments('%s/%s method', '%s/%s methods', 1)->once()
					->call('__')->withArguments('%s assertion', '%s assertions', 1)->once()
					->call('__')->withArguments('%s error', '%s errors', 0)->once()
					->call('__')->withArguments('%s exception', '%s exceptions', 0)->once()
					->call('_')->withArguments('Success (%s, %s, %s, %s, %s) !')->once()
				->mock($successColorizer)
					->call('colorize')->withArguments($noTestRunningString)->never()
					->call('colorize')->withArguments($successString)->once()
				->mock($failureColorizer)->call('colorize')->never()
				->mock($prompt)->call('__toString')->once()
		;

		$runnerController->getTestNumber = $testNumber = rand(2, PHP_INT_MAX);
		$runnerController->getTestMethodNumber = $testMethodNumber = rand(2, PHP_INT_MAX);
		$scoreController->getAssertionNumber = $assertionNumber = rand(2, PHP_INT_MAX);

		$this
			->startCase('Success with several tests, several methods and several assertions,  no fail, no error, no exception')
			->if($localeController->resetCalls())
			->and($successColorizerController->resetCalls())
			->and($failureColorizerController->resetCalls())
			->and($promptController->resetCalls())
			->and($field = new runner\result\cli($prompt, $successColorizer, $failureColorizer, $locale))
			->then
				->castToString($field)->isEqualTo($promptString . $noTestRunningString . PHP_EOL)
				->mock($locale)->call('_')->withArguments('No test running.')->once()
				->mock($successColorizer)->call('colorize')->withArguments($noTestRunningString)->never()
				->mock($failureColorizer)->call('colorize')->never()
				->mock($prompt)->call('__toString')->once()
			->if($localeController->resetCalls())
			->and($successColorizerController->resetCalls())
			->and($failureColorizerController->resetCalls())
			->and($promptController->resetCalls())
			->and($field->handleEvent(atoum\runner::runStart, $runner))
			->then
				->castToString($field)->isEqualTo($promptString . $noTestRunningString . PHP_EOL)
				->mock($locale)->call('_')->withArguments('No test running.')->once()
				->mock($successColorizer)->call('colorize')->withArguments($noTestRunningString)->never()
				->mock($failureColorizer)->call('colorize')->never()
				->mock($prompt)->call('__toString')->once()
			->if($localeController->resetCalls())
			->and($successColorizerController->resetCalls())
			->and($failureColorizerController->resetCalls())
			->and($promptController->resetCalls())
			->and($field->handleEvent(atoum\runner::runStop, $runner))
				->castToString($field)->isEqualTo($promptString . $colorizedSuccessString . PHP_EOL)
				->mock($locale)
					->call('__')->withArguments('%s test', '%s tests', $testNumber)->once()
					->call('__')->withArguments('%s/%s method', '%s/%s methods', $testMethodNumber)->once()
					->call('__')->withArguments('%s assertion', '%s assertions', $assertionNumber)->once()
					->call('__')->withArguments('%s error', '%s errors', 0)->once()
					->call('__')->withArguments('%s exception', '%s exceptions', 0)->once()
					->call('_')->withArguments('Success (%s, %s, %s, %s, %s) !')->once()
				->mock($successColorizer)
					->call('colorize')->withArguments($noTestRunningString)->never()
					->call('colorize')->withArguments($successString)->once()
				->mock($failureColorizer)->call('colorize')->never()
				->mock($prompt)->call('__toString')->once()
		;

		$scoreController->getFailNumber = 1;
		$scoreController->getErrorNumber = 1;
		$scoreController->getExceptionNumber = 1;
		$scoreController->getUncompletedMethodNumber = 1;

		$this
			->startCase('Failure with several tests, several methods and several assertions, one fail, one error, one exception')
			->if($localeController->resetCalls())
			->and($successColorizerController->resetCalls())
			->and($failureColorizerController->resetCalls())
			->and($promptController->resetCalls())
			->and($field = new runner\result\cli($prompt, $successColorizer, $failureColorizer, $locale))
			->then
				->castToString($field)->isEqualTo($promptString . $noTestRunningString . PHP_EOL)
				->mock($locale)->call('_')->withArguments('No test running.')->once()
				->mock($successColorizer)->call('colorize')->withArguments($noTestRunningString)->never()
				->mock($failureColorizer)->call('colorize')->never()
				->mock($prompt)->call('__toString')->once()
			->if($localeController->resetCalls())
			->and($successColorizerController->resetCalls())
			->and($failureColorizerController->resetCalls())
			->and($promptController->resetCalls())
			->and($field->handleEvent(atoum\runner::runStart, $runner))
			->then
				->castToString($field)->isEqualTo($promptString . $noTestRunningString . PHP_EOL)
				->mock($locale)->call('_')->withArguments('No test running.')->once()
				->mock($successColorizer)->call('colorize')->withArguments($noTestRunningString)->never()
				->mock($failureColorizer)->call('colorize')->never()
				->mock($prompt)->call('__toString')->once()
			->if($localeController->resetCalls())
			->and($successColorizerController->resetCalls())
			->and($failureColorizerController->resetCalls())
			->and($promptController->resetCalls())
			->and($field->handleEvent(atoum\runner::runStop, $runner))
			->then
				->castToString($field)->isEqualTo($promptString . $colorizedFailureString . PHP_EOL)
				->mock($locale)
					->call('__')->withArguments('%s test', '%s tests', $testNumber)->once()
					->call('__')->withArguments('%s/%s method', '%s/%s methods', $testMethodNumber)->once()
					->call('__')->withArguments('%s failure', '%s failures', 1)->once()
					->call('__')->withArguments('%s error', '%s errors', 1)->once()
					->call('__')->withArguments('%s exception', '%s exceptions', 1)->once()
					->call('_')->withArguments('Failure (%s, %s, %s, %s, %s) !')->once()
				->mock($failureColorizer)
					->call('colorize')->withArguments($noTestRunningString)->never()
					->call('colorize')->withArguments($failureString)->once()
				->mock($successColorizer)->call('colorize')->never()
				->mock($prompt)->call('__toString')->once()
		;

		$scoreController->getFailNumber = $failNumber = rand(2, PHP_INT_MAX);
		$scoreController->getErrorNumber = $errorNumber = rand(2, PHP_INT_MAX);
		$scoreController->getExceptionNumber = $exceptionNumber = rand(2, PHP_INT_MAX);
		$scoreController->getUncompletedMethodNumber = $uncompletedTestNumber = rand(2, PHP_INT_MAX);

		$this
			->startCase('Failure with several tests, several methods and several assertions, several fails, several errors, several exceptions')
			->if($localeController->resetCalls())
			->and($successColorizerController->resetCalls())
			->and($failureColorizerController->resetCalls())
			->and($promptController->resetCalls())
			->and($field = new runner\result\cli($prompt, $successColorizer, $failureColorizer, $locale))
			->then
				->castToString($field)->isEqualTo($promptString . $noTestRunningString . PHP_EOL)
				->mock($locale)->call('_')->withArguments('No test running.')->once()
				->mock($successColorizer)->call('colorize')->withArguments($noTestRunningString)->never()
				->mock($failureColorizer)->call('colorize')->never()
				->mock($prompt)->call('__toString')->once()
			->if($localeController->resetCalls())
			->and($successColorizerController->resetCalls())
			->and($failureColorizerController->resetCalls())
			->and($promptController->resetCalls())
			->and($field->handleEvent(atoum\runner::runStart, $runner))
			->then
				->castToString($field)->isEqualTo($promptString . $noTestRunningString . PHP_EOL)
				->mock($locale)->call('_')->withArguments('No test running.')->once()
				->mock($successColorizer)->call('colorize')->withArguments($noTestRunningString)->never()
				->mock($failureColorizer)->call('colorize')->never()
				->mock($prompt)->call('__toString')->once()
			->if($localeController->resetCalls())
			->and($successColorizerController->resetCalls())
			->and($failureColorizerController->resetCalls())
			->and($promptController->resetCalls())
			->and($field->handleEvent(atoum\runner::runStop, $runner))
			->then
				->castToString($field)->isEqualTo($promptString . $colorizedFailureString . PHP_EOL)
				->mock($locale)
					->call('__')->withArguments('%s test', '%s tests', $testNumber)->once()
					->call('__')->withArguments('%s/%s method', '%s/%s methods', $testMethodNumber)->once()
					->call('__')->withArguments('%s failure', '%s failures', $failNumber)->once()
					->call('__')->withArguments('%s error', '%s errors', $errorNumber)->once()
					->call('__')->withArguments('%s exception', '%s exceptions', $exceptionNumber)->once()
					->call('_')->withArguments('Failure (%s, %s, %s, %s, %s) !')->once()
				->mock($failureColorizer)
					->call('colorize')->withArguments($noTestRunningString)->never()
					->call('colorize')->withArguments($failureString)->once()
				->mock($successColorizer)->call('colorize')->never()
				->mock($prompt)->call('__toString')->once()
		;
	}
}

?>
