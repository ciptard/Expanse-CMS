<?php 
if(!defined('EXPANSE')){die('Sorry, but this file cannot be directly viewed.');} 
/*   DB Connection   //-------*/
add_admin_menu('<a href="?cat=admin&amp;sub=dbcnx">'.L_ADMIN_DB_SETTINGS.'</a>',array(),'mysql');
if($admin_sub !== 'dbcnx'){return;}
add_breadcrumb(L_DBCNX_TITLE);
add_title(L_DBCNX_TITLE);
ozone_action('admin_page', 'dbcnx_content');
function dbcnx_content() {
	global $output, $CONFIG;
	if(is_posting(L_BUTTON_EDIT)) {		
		$_POST['pass'] = !empty($_POST['pass']) ? $_POST['pass'] : $CONFIG['pass'];
		if(@!mysql_connect($_POST['host'],$_POST['user'],$_POST['pass'])) {
			$sqlhost 	= $CONFIG['host'];
			$sqluser	= $CONFIG['user'];
			$sqlpass 	= $CONFIG['pass'];
			printOut(FAILURE, L_DB_NEW_CNX_FAILED);
		} elseif (!mysql_select_db($_POST['db'])) {
			$sqldb = $CONFIG['db'];
			printOut(FAILURE, L_DB_NEW_SELECT_FAILED);
		} else {
			$sqlhost 	= $_POST['host'];
			$sqluser	= $_POST['user'];
			$sqlpass 	= $_POST['pass'];
			$sqldb 		= $_POST['db'];
			$sqlprefix 	= $CONFIG['prefix'];			
			$CONFIG['host']		= $_POST['host'];
			$CONFIG['user']		= $_POST['user'];
			$CONFIG['pass']		= $_POST['pass'];
			$CONFIG['db']		= $_POST['db'];
			$fp = fopen(EXPANSEPATH.'/config.php',"w+");	
			$config_to_write =
				"<?php
				/*
				------------------------------------------------------------
				Expanse Config File
				============================================================
				*/
				//DATABASE VARIABLES
				\$CONFIG[\'host\']		= '$sqlhost'; //Database host; usually localhost, or something like mysql.server.com
				\$CONFIG['user']		= '$sqluser'; //Database User
				\$CONFIG['pass']		= '$sqlpass'; //Database Password
				\$CONFIG['db']			= '$sqldb'; //Database Name
				\$CONFIG['prefix']		= '$sqlprefix'; //Table Prefix
				//Stop editing
				\$CONFIG['home']		= dirname(__FILE__);
				?>";
			$config_to_write = applyOzoneAction('config_file', $config_to_write);
			fwrite($fp,$config_to_write);
			fclose($fp);
			printOut(SUCCESS, L_DB_UPDATE_SUCCESS);
		}
	}
	$link = mysql_connect($CONFIG['host'],$CONFIG['user'],$CONFIG['pass']);
	$db = mysql_select_db($CONFIG['db']);
	if($link){
		printOut(SUCCESS,L_DB_CNX_WORKING);
	} else {
		printOut(FAILURE,L_DB_CNX_BROKEN);
	}
	echo $output; ?>
	<div class="row">
		<div class="span16">
			<div class="clearfix">
				<label for="host"><?php echo L_DB_HOSTNAME ?></label>
				<div class="input">
					<input type="text" name="host" class="formfields" id="host" value="<?php echo $CONFIG['host']; ?>" size="40" />
				</div>				
			</div>
			<div class="clearfix">
				<label for="user"><?php echo L_DB_USERNAME ?></label>
				<div class="input">
					<input name="user" type="text" class="formfields" id="user" value="<?php echo $CONFIG['user']; ?>" size="40" />
				</div>
			</div>
			<div class="clearfix">
				<label for="dbpassword"><?php echo L_DB_PASSWORD ?></label>
				<div class="clearfix">
					<input name="pass" type="password" class="formfields" id="pass" size="40" />
					<span class="help-block"><?php echo L_DB_PASSWORD_NOTE ?></span>
				</div>
			</div>
			<div class="clearfix">
				<label for="db"><?php echo L_DB_DATABASE_NAME ?></label>
				<div class="input">
					<input name="db" type="text" class="formfields" id="db" value="<?php echo $CONFIG['db']; ?>" size="31" />
					<span class="help-block"><?php echo L_DB_DATABASE_NAME_HELP ?></span>
				</div>
			</div>
		</div>
	</div>
    <?php 
	if(is_writable(EXPANSEPATH.'/config.php')){
	?>                	
		<div class="actions">
			<input type="submit" name="submit" class="btn primary" value="<?php echo L_BUTTON_EDIT ?>" />
		</div>
	<?php } else {
		printf(ALERT, L_DB_NEEDS_PERMISSIONS);
	}
} 
?>
