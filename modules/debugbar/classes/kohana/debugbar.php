<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Debugbar
{
	protected static $_queries = FALSE;
	protected static $_benchmarks = FALSE;
	protected static $_custom_tabs = array();

	public static $benchmark_name = 'debugbar';

	public static function render() {
	    echo self::generate();
	}
	
	/**
	 * Renders the Debug Toolbar
	 *
	 * @param bool print rendered output
	 * @return string debug toolbar rendered output
	 */
	public static function generate()
	{
	    
	    if(!self::is_enabled())
			return false;
	    
		$token = Profiler::start('custom', self::$benchmark_name);

		$template = new View('debugbar/index');

		// Database panel
//		if (Kohana::config('debugbar.panels.database') === TRUE)
		if (Kohana::$config->load('debugbar')->panels['database'] === TRUE)
		{
			$queries = self::get_queries();
			$template
				->set('queries', $queries['data'])
				->set('query_count', $queries['count'])
				->set('total_time', $queries['time'])
				->set('total_memory', $queries['memory']);
		}

		// Files panel
//		if (Kohana::config('debugbar.panels.files') === TRUE)
		if (Kohana::$config->load('debugbar')->panels['files'] === TRUE)
		{
			$template->set('files', self::get_files());
		}

		// Modules panel
//		if (Kohana::config('debugbar.panels.modules') === TRUE)
		if (Kohana::$config->load('debugbar')->panels['modules'] === TRUE)
		{
			$template->set('modules', self::get_modules());
		}

		// Routes panel
//		if (Kohana::config('debugbar.panels.routes') === TRUE)
		if (Kohana::$config->load('debugbar')->panels['routes'] === TRUE)
		{
			$template->set('routes', self::get_routes());
		}

		// Custom data
//		if (Kohana::config('debugbar.panels.customs') === TRUE)
		if (Kohana::$config->load('debugbar')->panels['customs'] === TRUE)
		{
			$template->set('customs', self::get_customs());
		}

		// FirePHP
//		if (Kohana::config('debugbar.firephp_enabled') === TRUE)
		if (Kohana::$config->load('debugbar')->firephp_enabled === TRUE)
		{
			self::firephp();
		}

		// Set alignment for toolbar
//		switch (Kohana::config('debugbar.align'))
		switch (Kohana::$config->load('debugbar')->align)
		{
			case 'right':
			case 'center':
			case 'left':
				$template->set('align', Kohana::$config->load('debugbar')->align);
				break;
			default:
				$template->set('align', 'left');
		}

		Profiler::stop($token);

		// Benchmarks panel
//		if (Kohana::config('debugbar.panels.benchmarks') === TRUE)
		if (Kohana::$config->load('debugbar')->panels['benchmarks'] === TRUE)
		{
			$template->set('benchmarks', self::get_benchmarks());
		}
		
		return $template->render();
	}

	/**
	 * Adds custom data to render in a separate tab
	 *
	 * @param  string $tab_name
	 * @param  mixed  $data
	 * @return void
	 */
	public static function add_custom($tab_name, $data)
	{
		self::$_custom_tabs[$tab_name] = $data;
	}

	/**
	 * Get user vars
	 *
	 * @return array
	 */
	public static function get_customs()
	{
		$result = array();

		foreach(self::$_custom_tabs as $tab => $data)
		{			
			if (is_array($data) OR is_object($data))
			{
				$data = Debug::dump($data);
			}

			$result[$tab] = $data;
		}

		return $result;
	}

	/**
	 * Retrieves query benchmarks from Database
	 *
	 * @return  array
	 */
	public static function get_queries()
	{
		if (self::$_queries !== FALSE)
		{
			return self::$_queries;
		}

		$result = array();
		$count = $time = $memory = 0;

		$groups = Profiler::groups();
		foreach(Database::$instances as $name => $db)
		{
			$group_name = 'database (' . strtolower($name) . ')';
			$group = arr::get($groups, $group_name, FALSE);

			if ($group)
			{					
				$sub_time = $sub_memory = $sub_count = 0;
				foreach ($group as $query => $tokens)
				{
					$sub_count += count($tokens);
					foreach ($tokens as $token)
					{
						$total = Profiler::total($token);
						$sub_time += $total[0];
						$sub_memory += $total[1];
						$result[$name][] = array('name' => $query, 'time' => $total[0], 'memory' => $total[1]);
					}
				}
				$count += $sub_count;
				$time += $sub_time;
				$memory += $sub_memory;
				$result[$name]['total'] = array($sub_count, $sub_time, $sub_memory);
			}		
		}
		self::$_queries = array('count' => $count, 'time' => $time, 'memory' => $memory, 'data' => $result);

		return self::$_queries;
	}

	/**
	 * Creates a formatted array of all Benchmarks
	 *
	 * @return array formatted benchmarks
	 */
	public static function get_benchmarks()
	{
		if (Kohana::$profiling == FALSE)
		{
			return array();
		}

		if (self::$_benchmarks !== FALSE)
		{
			return self::$_benchmarks;
		}

		$groups = Profiler::groups();
		$result = array();
		foreach(array_keys($groups) as $group)
		{
			if (strpos($group, 'database (') === FALSE)
			{
				foreach($groups[$group] as $name => $marks)
				{
					$stats = Profiler::stats($marks);
					$result[$group][] = array
					(
						'name'			=> $name,
						'count'			=> count($marks),
						'total_time'	=> $stats['total']['time'],
						'avg_time'		=> $stats['average']['time'],
						'total_memory'	=> $stats['total']['memory'],
						'avg_memory'	=> $stats['average']['memory'],
					);
				}
			}
		}
		// add total stats
		$total = Profiler::application();
		$result['application'] = array
		(
			'count'			=> 1,
			'total_time'	=> $total['current']['time'],
			'avg_time'		=> $total['average']['time'],
			'total_memory'	=> $total['current']['memory'],
			'avg_memory'	=> $total['average']['memory'],

		);

		self::$_benchmarks = $result;

		return $result;
	}

	/**
	 * Get list of included files
	 *
	 * @return array file currently included by php
	 */
	public static function get_files()
	{
		$files = (array)get_included_files();
		sort($files);
		return $files;
	}

	/**
	 * Get module list
	 *
	 * @return array  module_name => module_path
	 */
	public static function get_modules()
	{
		return Kohana::modules();
	}

	/**
	 * Returns all application routes
	 *
	 * @return array
	 */
	public static function get_routes()
	{
		return Route::all();
	}

	/**
	 * Add toolbar data to FirePHP console
	 * 
	 */
	private static function firephp()
	{
		$firephp = FirePHP::getInstance(TRUE);
		$firephp->fb('KOHANA DEBUGBAR:');

		// Globals
		$globals = array(
			'Post'    => empty($_POST)    ? array() : $_POST,
			'Get'     => empty($_GET)     ? array() : $_GET,
			'Cookie'  => empty($_COOKIE)  ? array() : $_COOKIE,
			'Session' => empty($_SESSION) ? array() : $_SESSION
		);

		foreach ($globals as $name => $global)
		{
			$table = array();
			$table[] = array($name,'Value');

			foreach((array)$global as $key => $value)
			{
				if (is_object($value))
				{
					$value = get_class($value).' [object]';
				}

				$table[] = array($key, $value);
			}

			$message = "$name: ".count($global).' variables';

			$firephp->fb(array($message, $table), FirePHP::TABLE);
		}

		// Database
		$query_stats = self::get_queries();

		//$total_time = $total_rows = 0;
		$table = array();
		$table[] = array('DB profile', 'SQL Statement','Time','Memory');

		foreach ((array)$query_stats['data'] as $db => $queries)
		{unset($queries['total']);
			foreach ($queries as $query)
			{
				$table[] = array(
					$db,
					str_replace("\n",' ',$query['name']),
					number_format($query['time']*1000, 3),
					number_format($query['memory']/1024, 3),
				);
			}
		}

		$message = 'Queries: '.$query_stats['count'].' SQL queries took '.
			number_format($query_stats['time'], 3).' seconds and '.$query_stats['memory'].' b';

		$firephp->fb(array($message, $table), FirePHP::TABLE);

		// Benchmarks
		$groups = self::get_benchmarks();
		// application benchmarks
		$total = array_pop($groups);

		$table = array();
		$table[] = array('Group', 'Benchmark', 'Count', 'Time', 'Memory');

		foreach ((array)$groups as $group => $benchmarks)
		{
			foreach ((array)$benchmarks as $name => $benchmark)
			{
				$table[] = array(
					ucfirst($group),
					ucwords($benchmark['name']),
					number_format($benchmark['total_time'], 3). ' s',
					text::bytes($benchmark['total_memory']),
				);
			}
		}

		$message = 'Application tooks '.number_format($total['total_time'], 3).' seconds and '.text::bytes($total['total_memory']).' memory';

		$firephp->fb(array($message, $table), FirePHP::TABLE);
	}

	/**
	 * Determines if all the conditions are correct to display the toolbar
	 * (pretty kludgy, I know)
	 *
	 * @returns bool toolbar enabled
	 */
	public static function is_enabled()
	{
	    
        // If controller is debugbar, don't output because it will append to media files
		if(Request::initial()->controller() == 'debugbar')
			return FALSE;
		
		// Don't auto render toolbar for ajax requests
		if (Request::initial()->is_ajax())
			return FALSE;

		// Don't auto render toolbar if $_GET['debug'] = 'false'
		if (isset($_GET['debug']) and strtolower($_GET['debug']) == 'false')
			return FALSE;

		// Don't auto render if auto_render config is FALSE
//		if (Kohana::config('debugbar.auto_render') !== TRUE)
		if (Kohana::$config->load('debugbar')->auto_render !== TRUE)
			return FALSE;

		// Auto render if secret key isset
//		$secret_key = Kohana::config('debugbar.secret_key');
		$secret_key = Kohana::$config->load('debugbar')->secret_key;
		if ($secret_key !== FALSE and isset($_GET[$secret_key]))
			return TRUE;

		// Don't auto render when in PRODUCTION (this can obviously be
		// overridden by the above secret key)
		if (Kohana::$environment == Kohana::PRODUCTION)
			return FALSE;

		return TRUE;
	}
}