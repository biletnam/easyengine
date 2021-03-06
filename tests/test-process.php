<?php

use EE\Process;
use EE\Utils;

class ProcessTests extends PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider data_process_env
	 */
	function test_process_env( $cmd_prefix, $env, $expected_env_vars, $expected_out ) {
		$code = vsprintf( str_repeat( 'echo getenv( \'%s\' );', count( $expected_env_vars ) ), $expected_env_vars );

		$cmd = $cmd_prefix . ' ' . escapeshellarg( Utils\get_php_binary() ) . ' -r ' . escapeshellarg( $code );
		$process_run = Process::create( $cmd, null /*cwd*/, $env )->run();

		$this->assertSame( $process_run->stdout, $expected_out );
	}

	function data_process_env() {
		return array(
			array( '', array(), array(), '' ),
			array( 'ENV=blah', array(), array( 'ENV' ), 'blah' ),
			array( 'ENV="blah blah"', array(), array( 'ENV' ), 'blah blah' ),
			array( 'ENV_1="blah1 blah1" ENV_2="blah2" ENV_3=blah3', array( 'ENV' => 'in' ), array( 'ENV', 'ENV_1', 'ENV_2', 'ENV_3' ), 'inblah1 blah1blah2blah3' ),
			array( 'ENV=blah', array( 'ENV_1' => 'in1', 'ENV_2' => 'in2' ), array( 'ENV_1', 'ENV_2', 'ENV' ), 'in1in2blah' ),
		);
	}
}
