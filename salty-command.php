<?php

use Symfony\Component\Yaml\Yaml;

/**
 * Manage salts.
 *
 * @author Vlad
 */
class Salty_Command extends WP_CLI_Command
{

	/**
	 * Generates salts to a file
	 *
	 * @when before_wp_load
	 *
	 * @synopsis [--file=<foo>]
	 *
	 */
	function generate($args, $assoc_args)
	{
		$api = 'https://api.wordpress.org/secret-key/1.1/salt/';

		if (isset($assoc_args['file'])){
			$files = [$assoc_args['file']];
		} else {
			$files = ['develop.wp-cli.yml', 'production.wp-cli.yml', 'staging.wp-cli.yml'];
		}
		
		foreach($files as $file) {

			if (!file_exists($file))
				WP_CLI::error('File is not exists: ' . $file);
			
			$data = file_get_contents($api);
			$output = Yaml::parseFile($file);
			
			if (!is_writable($file))
				WP_CLI::error('File is not writable or path is not correct: ' . $file);

			$check = strpos($output['core config']['extra-php'], 'AUTH_KEY');
			
			if($check === false) {
				$output['core config']['extra-php'] .= $data;

				$yaml = Yaml::dump($output, 2, 4, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK);
				$result = file_put_contents($file, $yaml);
				if (!$result) {
					WP_CLI::error('could not write salts to: ' . $file);
				}

				WP_CLI::success('Added salts to: ' . $file);
			} else{
				echo 'Error: Salt already exist in: ' . $file, PHP_EOL;
			}
		}
		return;
	}
}

WP_CLI::add_command('salty', 'Salty_Command');
