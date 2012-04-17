<?php defined('SYSPATH') or die('No direct script access.') ?>

<?php 
$image_path = 'debugbar/media/img/';
?>

<!-- CSS styles (if not added to <head>) -->
<link type="text/css" href="<?php echo Url::base() . 'debugbar/media/css/debugbar.css' ?>" rel="stylesheet" media="screen" />

<script type="text/javascript" src="<?php echo Url::base() . 'debugbar/media/js/debugbar.js' ?>"></script>

<div id="kohana-debugbar">

	<!-- Toolbar -->
	<div id="debugbar" class="debugbar-align-<?php echo $align ?>">
	
		<!-- Kohana link -->
		<?php echo html::image(
			$image_path . 'kohana.png',
			array('onclick' => 'debugbar.collapse()')
		) ?>
		
		<!-- Kohana icon -->
		<?php // if (Kohana::config('debugbar.minimized') === TRUE): ?>
		<?php if (Kohana::$config->load('debugbar')->minimized === TRUE): ?>
			<ul id="debugbar-menu" class="menu" style="display: none">
		<?php else: ?>
			<ul id="debugbar-menu" class="menu">
		<?php endif ?>
			
			<!-- Kohana version -->
			<li>
				<?php echo html::anchor("http://kohanaframework.org", Kohana::VERSION, array('target' => '_blank')) ?>
			</li>
			
			<!-- Benchmarks -->
			<?php // if (Kohana::config('debugbar.panels.benchmarks')): ?>
			<?php if (Kohana::$config->load('debugbar')->panels['benchmarks']): ?>
				<!-- Time -->
				<li id="time" onclick="debugbar.show('debug-benchmarks'); return false;">
					<?php echo html::image($image_path . 'time.png', array('alt' => 'time')) ?>
					<?php echo round(($benchmarks['application']['total_time'])*1000, 2) ?> ms
				</li>
				<!-- Memory -->
				<li id="memory" onclick="debugbar.show('debug-benchmarks'); return false;">
					<?php echo html::image($image_path . 'memory.png', array('alt' => 'memory')) ?>
					<?php echo text::bytes($benchmarks['application']['total_memory']) ?>
				</li>
			<?php endif ?>
			
			<!-- Queries -->
			<?php // if (Kohana::config('debugbar.panels.database')): ?>
			<?php if (Kohana::$config->load('debugbar')->panels['database']): ?>
				<li id="toggle-database" onclick="debugbar.show('debug-database'); return false;">
					<?php echo html::image($image_path . 'database.png', array('alt' => 'queries')) ?>
					<?php echo isset($queries) ? $query_count : 0 ?>
				</li>
			<?php endif ?>
			
			<!-- Vars -->
			<?php // if (Kohana::config('debugbar.panels.vars')): ?>
			<?php if (Kohana::$config->load('debugbar')->panels['vars']): ?>
				<li id="toggle-vars" onclick="debugbar.show('debug-vars'); return false;">
					<?php echo html::image($image_path . 'config.png', array('alt' => 'vars')) ?>
					vars
				</li>
			<?php endif ?>
			
			<!-- Ajax -->
			<?php // if (Kohana::config('debugbar.panels.ajax')): ?>
			<?php if (Kohana::$config->load('debugbar')->panels['ajax']): ?>
				<li id="toggle-ajax" onclick="debugbar.show('debug-ajax'); return false;" style="display: none">
					<?php echo html::image($image_path . 'ajax.png', array('alt' => 'ajax')) ?>
					ajax (<span>0</span>)
				</li>
			<?php endif ?>
			
			<!-- Files -->
			<?php // if (Kohana::config('debugbar.panels.files')): ?>
			<?php if (Kohana::$config->load('debugbar')->panels['files']): ?>
				<li id="toggle-files" onclick="debugbar.show('debug-files'); return false;">
					<?php echo html::image($image_path . 'page_copy.png', array('alt' => 'files')) ?>
					files
				</li>
			<?php endif ?>

			<!-- Modules -->
			<?php // if (Kohana::config('debugbar.panels.modules')): ?>
			<?php if (Kohana::$config->load('debugbar')->panels['modules']): ?>
				<li id="toggle-modules" onclick="debugbar.show('debug-modules'); return false;">
					<?php echo html::image($image_path . 'module.png', array('alt' => 'modules')) ?>
					modules
				</li>
			<?php endif ?>

			<!-- Routes -->
			<?php // if (Kohana::config('debugbar.panels.routes')): ?>
			<?php if (Kohana::$config->load('debugbar')->panels['routes']): ?>
				<li id="toggle-routes" onclick="debugbar.show('debug-routes'); return false;">
					<?php echo html::image($image_path . 'route.png', array('alt' => 'routes')) ?>
					routes
				</li>
			<?php endif ?>

			<!-- Custom data -->
			<?php // if (Kohana::config('debugbar.panels.customs')): ?>
			<?php if (Kohana::$config->load('debugbar')->panels['customs']): ?>
				<li id="toggle-customs" onclick="debugbar.show('debug-customs'); return false;">
					<?php echo html::image($image_path . 'custom.png', array('alt' => 'customs')) ?>
					customs
				</li>
			<?php endif ?>

			<!-- Swap sides -->
			<li onclick="debugbar.swap(); return false;">
				<?php echo html::image($image_path . 'text_align_left.png', array('alt' => 'align')) ?>
			</li>
			
			<!-- Close -->
			<li class="last" onclick="debugbar.close(); return false;">
				<?php echo html::image($image_path . 'close.png', array('alt' => 'close')) ?>
			</li>
		</ul>
	</div>
	
	<!-- Benchmarks -->
	<?php // if (Kohana::config('debugbar.panels.benchmarks')): ?>
	<?php if (Kohana::$config->load('debugbar')->panels['benchmarks']): ?>
		<div id="debug-benchmarks" class="top" style="display: none;">
			<h1>Benchmarks</h1>
			<table cellspacing="0" cellpadding="0">
				<tr>
					<th align="left">benchmark</th>
					<th align="right">count</th>
					<th align="right">avg time</th>
					<th align="right">total time</th>
					<th align="right">avg memory</th>
					<th align="right">total memory</th>
				</tr>
				<?php if (count($benchmarks)):
					$application = array_pop($benchmarks);?>
					<?php foreach ((array)$benchmarks as $group => $marks): ?>
						<tr>
							<th colspan="6"><?php echo $group?></th>
						</tr>
						<?php foreach($marks as $benchmark): ?>
						<tr class="<?php echo text::alternate('odd','even')?>">
							<td align="left"><?php echo $benchmark['name'] ?></td>
							<td align="right"><?php echo $benchmark['count'] ?></td>
							<td align="right"><?php echo sprintf('%.2f', $benchmark['avg_time'] * 1000) ?> ms</td>
							<td align="right"><?php echo sprintf('%.2f', $benchmark['total_time'] * 1000) ?> ms</td>
							<td align="right"><?php echo text::bytes($benchmark['avg_memory']) ?></td>
							<td align="right"><?php echo text::bytes($benchmark['total_memory']) ?></td>
						</tr>
						<?php endforeach; ?>
					<?php endforeach; ?>
						<tr>
							<th colspan="2" align="left">APPLICATION</th>
							<th align="right"><?php echo sprintf('%.2f', $application['avg_time'] * 1000) ?> ms</th>
							<th align="right"><?php echo sprintf('%.2f', $application['total_time'] * 1000) ?> ms</th>
							<th align="right"><?php echo text::bytes($application['avg_memory']) ?></th>
							<th align="right"><?php echo text::bytes($application['total_memory']) ?></th>
						</tr>
				<?php else: ?>
					<tr class="<?php echo text::alternate('odd','even') ?>">
						<td colspan="6">no benchmarks to display</td>
					</tr>
				<?php endif ?>
			</table>
		</div>
	<?php endif ?>
	
	<!-- Database -->
	<?php // if (Kohana::config('debugbar.panels.database')): ?>
	<?php if (Kohana::$config->load('debugbar')->panels['database']): ?>
		<div id="debug-database" class="top" style="display: none;">
			<h1>SQL Queries</h1>
			<table cellspacing="0" cellpadding="0">
				<tr align="left">
					<th>#</th>
					<th>query</th>
					<th>time</th>
					<th>memory</th>
				</tr>
				<?php foreach ($queries as $db_profile => $stats):
					list($sub_count, $sub_time, $sub_memory) = array_pop($stats); ?>
				<tr align="left">
					<th colspan="4">DATABASE "<?php echo strtoupper($db_profile) ?>"</th>
				</tr>
					<?php foreach ($stats as $num => $query): ?>
					<tr class="<?php echo text::alternate('odd','even') ?>">
						<td><?php echo $num+1 ?></td>
						<td><?php echo $query['name'] ?></td>
						<td><?php echo number_format($query['time'] * 1000, 3) ?> ms</td>
						<td><?php echo number_format($query['memory'] / 1024, 3) ?> kb</td>
					</tr>
					<?php	endforeach;	?>
					<tr>
						<th>&nbsp;</th>
						<th><?php echo $sub_count ?> total</th>
						<th><?php echo number_format($sub_time * 1000, 3) ?> ms</th>
						<th><?php echo number_format($sub_memory / 1024, 3) ?> kb</th>
					</tr>
				<?php endforeach; ?>
				<?php if (count($queries) > 1): ?>
					<tr>
						<th colspan="2" align="left"><?php echo $query_count ?> TOTAL</th>
						<th><?php echo number_format($total_time * 1000, 3) ?> ms</th>
						<th><?php echo number_format($total_memory / 1024, 3) ?> kb</th>
					</tr>
				<?php endif; ?>
			</table>
		</div>
	<?php endif ?>
	
	<!-- Vars -->
	<?php // if (Kohana::config('debugbar.panels.vars')): ?>
	<?php if (Kohana::$config->load('debugbar')->panels['vars']): ?>
		<div id="debug-vars" class="top" style="display: none;">
			<h1>Vars</h1>
			<ul class="varmenu">
				<li onclick="debugbar.showvar(this, 'vars-post'); return false;">POST</li>
				<li onclick="debugbar.showvar(this, 'vars-get'); return false;">GET</li>
				<li onclick="debugbar.showvar(this, 'vars-files'); return false;">FILES</li>
				<li onclick="debugbar.showvar(this, 'vars-server'); return false;">SERVER</li>
				<li onclick="debugbar.showvar(this, 'vars-cookie'); return false;">COOKIE</li>
				<li onclick="debugbar.showvar(this, 'vars-session'); return false;">SESSION</li>
			</ul>
			<div style="display: none;" id="vars-post">
				<?php echo isset($_POST) ? Debug::vars($_POST) : Debug::vars(array()) ?>
			</div>
			<div style="display: none;" id="vars-get">
				<?php echo isset($_GET) ? Debug::vars($_GET) : Debug::vars(array()) ?>
			</div>
			<div style="display: none;" id="vars-files">
				<?php echo isset($_FILES) ? Debug::vars($_FILES) : Debug::vars(array()) ?>
			</div>
			<div style="display: none;" id="vars-server">
				<?php echo isset($_SERVER) ? Debug::vars($_SERVER) : Debug::vars(array()) ?>
			</div>
			<div style="display: none;" id="vars-cookie">
				<?php echo isset($_COOKIE) ? Debug::vars($_COOKIE) : Debug::vars(array()) ?>
			</div>
			<div style="display: none;" id="vars-session">
				<?php echo isset($_SESSION) ? Debug::vars($_SESSION) : Debug::vars(array()) ?>
			</div>
		</div>
	<?php endif ?>
	
	<!-- Ajax Requests -->
	<?php // if (Kohana::config('debugbar.panels.ajax')): ?>
	<?php if (Kohana::$config->load('debugbar')->panels['ajax']): ?>
		<div id="debug-ajax" class="top" style="display:none;">
			<h1>Ajax</h1>
			<table cellspacing="0" cellpadding="0">
				<tr align="left">
					<th width="1%">#</th>
					<th width="150">source</th>
					<th width="150">status</th>
					<th>request</th>
				</tr>
			</table>
		</div>
	<?php endif ?>
	
	<!-- Included Files -->
	<?php // if (Kohana::config('debugbar.panels.files')): ?>
	<?php if (Kohana::$config->load('debugbar')->panels['files']): ?>
		<div id="debug-files" class="top" style="display: none;">
			<h1>Files</h1>
			<table cellspacing="0" cellpadding="0">
				<tr align="left">
					<th>#</th>
					<th>file</th>
					<th>size</th>
					<th>lines</th>
				</tr>
				<?php $total_size = $total_lines = 0 ?>
				<?php foreach ((array)$files as $id => $file): ?>
					<?php
					$size = filesize($file);
					$lines = count(file($file));
					?>
					<tr class="<?php echo text::alternate('odd','even')?>">
						<td><?php echo $id + 1 ?></td>
						<td><?php echo $file ?></td>
						<td><?php echo $size ?></td>
						<td><?php echo $lines ?></td>
					</tr>
					<?php
					$total_size += $size;
					$total_lines += $lines;
					?>
				<?php endforeach; ?>
				<tr align="left">
					<th colspan="2">total</th>
					<th><?php echo text::bytes($total_size) ?></th>
					<th><?php echo number_format($total_lines) ?></th>
				</tr>
			</table>
		</div>
	<?php endif ?>

	<!-- Module list -->
	<?php // if (Kohana::config('debugbar.panels.modules')):
			// $mod_counter = 0; ?>
	<?php if (Kohana::$config->load('debugbar')->panels['modules']):
			$mod_counter = 0; ?>
		<div id="debug-modules" class="top" style="display: none;">
			<h1>Modules</h1>
			<table cellspacing="0" cellpadding="0">
				<tr align="left">
					<th>#</th>
					<th>name</th>
					<th>rel path</th>
					<th>abs path</th>
				</tr>
				<?php foreach($modules as $name => $path): ?>
				<tr class="<?php echo text::alternate('odd','even')?>">
					<td><?php echo ++$mod_counter ?></td>
					<td><?php echo $name ?></td>
					<td><?php echo $path ?></td>
					<td><?php echo realpath($path) ?></td>
				</tr>
				<?php endforeach ?>
			</table>
		</div>
	<?php endif ?>

	<!-- Routes -->
	<?php //if (Kohana::config('debugbar.panels.routes')):
			//$r_counter = 0; ?>
	<?php if (Kohana::$config->load('debugbar')->panels['routes']):
			$r_counter = 0; ?>
		<div id="debug-routes" class="top" style="display: none;">
			<h1>Routes</h1>
			<table cellspacing="0" cellpadding="0">
				<tr align="left">
					<th>#</th>
					<th>name</th>
				</tr>
				<?php foreach($routes as $name => $route):
						$class = ($route == Request::initial()->route() ? ' current' : ''); ?>
				<tr class="<?php echo text::alternate('odd','even').$class?>">
					<td><?php echo ++$r_counter ?></td>
					<td><?php echo $name ?></td>
				</tr>
				<?php endforeach ?>
			</table>
		</div>
	<?php endif ?>

	<!-- Custom data-->
	<?php // if (Kohana::config('debugbar.panels.customs') && count($customs) > 0):
			// $r_counter = 0; ?>
	<?php if (Kohana::$config->load('debugbar')->panels['customs'] && count($customs) > 0):
			$r_counter = 0; ?>
		<div id="debug-customs" class="top" style="display: none;">
			<h1>Custom data</h1>
			<ul class="sectionmenu">
				<?php foreach($customs as $section => $data): ?>
				<li onclick="debugbar.showvar(this, 'customs-<?php echo $section ?>'); return false;"><?php echo $section ?></li>
				<?php endforeach; ?>
			</ul>
			<?php foreach($customs as $section => $data): ?>
			<div style="display: none;" id="customs-<?php echo $section ?>">
					<pre><?php echo $data ?></pre>
			</div>
			<?php endforeach; ?>
		</div>
	<?php endif ?>
</div>