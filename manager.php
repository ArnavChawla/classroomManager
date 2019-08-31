
<?php /**
 * Plugin Name: Classroom Manager
 * Plugin URI: http://www.mywebsite.com/my-first-plugin
 * Description: The very first plugin that I have ever created.
 * Version: 1.0
 * Author: Arnav Chawla
 * Author URI: http://www.mywebsite.com
 */ global $jal_db_version; $jal_db_version = '1.0';
 function do_first() {
	global $wpdb;
	global $jal_db_version;
	$table_name = $wpdb->prefix . 'student';

	$charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		name text NOT NULL,
		class text NOT NULL,
		classId text NOT NULL,
		email text NOT NULL,
		phone text NOT NULL,
		day text NOT NULL,
		active text NOT NULL,
		vacation DATE NULL,
		PRIMARY KEY (id)
	) $charset_collate;";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	add_option( 'jal_db_version', $jal_db_version );
}
function do_second() {
 global $wpdb;
 global $jal_db_version;
 $table_name = $wpdb->prefix . 'classes';

 $charset_collate = $wpdb->get_charset_collate();
 $sql = "CREATE TABLE $table_name (
	 class text NOT NULL,
	 id text NOT NULL,
	 day text NOT NULL,
	 PRIMARY KEY (id)
 ) $charset_collate;";

 require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
 dbDelta( $sql );
 add_option( 'jal_db_version', $jal_db_version );
}
global $options;
foreach ($options as $value) {
    if (get_option($value['id']) === FALSE) {
        $$value['id'] = $value['std'];
    }
    else {
        $$value['id'] = get_option( $value['id'] );
    }
}
// function do_thing() {
// 	global $wpdb;
//
// 	$welcome_name = 'Varun';
// 	$student_class = 'FTC';
// 	$student_day = 'Sunday';
// 	$student_email = 'arnav@justadev.club';
// 	$student_phone = '4255245667';
// 	$table_name = $wpdb->prefix . 'student';
//
// 	$wpdb->insert(
// 		$table_name,
// 		array(
// 			'time' => current_time( 'mysql' ),
// 			'name' => $welcome_name,
// 			'class' => $student_class,
// 			'email' => $student_email,
// 			'phone' => $student_phone,
// 			'day' => $student_day,
//
// 		)
// 	);
// }
function data_func( $atts ){
	global $wpdb;
	if ($_POST['form_name'] == "")
	{
		return "";
	}
		$welcome_name = 'Varun';
		$student_class = 'FTC';
		$student_day = 'Sunday';
		$student_email = 'arnav@justadev.club';
		$student_phone = '4255245667';
		$table_name = $wpdb->prefix . 'student';
		$string = $_POST['form_class'];
		$str_arr = explode (",", $string);
	$wpdb->insert(
		$table_name,
		array(
			'name' => $_POST['form_name'],
			'class' => $str_arr[0],
			'classId' => $str_arr[1],
			'email' => $_POST['form_email'],
			'phone' => $_POST['form_phone'],
			'day' => $_POST['form_day'],
			'active' => "yes",

		)
	);
	return '<font color="green">Information Saved!</font>';

}
function html_form_code() {
//	echo (date('Y-m-d'));
	global $wpdb;
	echo '<form action="" method="post">';
	echo '<p>';
	echo 'Your Name (required) <br/>';
	echo '<input type="text" name="form_name" pattern="[a-zA-Z0-9 ]+" value="" size="40" />';
	echo '</p>';
	echo '<p>';
	echo 'Your Email (required) <br/>';
	echo '<input type="email" name="form_email" value="" size="40" />';
	echo '</p>';
	echo '<p>';
	echo 'Class (required) <br/>';
	$results = $wpdb->get_results( "SELECT * FROM wp_classes");
	echo '<select name="form_class">';
  foreach($results as $row){
	echo "<option value=\"". $row->class . ",". $row->id ."\">" .$row->class. "</option>";
	}
	echo '</select>';
	echo '</p>';
	echo '<p>';
	echo 'Prefered class Day (required) <br/>';
	echo '<input type="text" name="form_day" value="" size="40" />';
	echo '</p>';
	echo '<p>';
	echo '<p>';
	echo 'Phone Number(required) <br/>';
	echo '<input type="text" name="form_phone" value="" size="40" />';
	echo '</p>';
	echo '<p><input type="submit" name="cf-submitted" value="Send"></p>';
	echo '</form>';
}

function my_awesome_page_display() {
    global $wpdb;
		wp_enqueue_style('table_css', plugins_url('/test.css',__FILE__ ));
		if(!array_key_exists('form_class', $_POST) && !array_key_exists('form_date', $_POST))
		{
			$results = $wpdb->get_results( "SELECT * FROM wp_student"); // Query to fetch data from database table and storing in $results
			$classResults = $wpdb->get_results( "SELECT * FROM wp_classes");
	    if(!empty($results))                        // Checking if $results have some values or not
	    {
	        echo "<table width='100%' border='0'>";
					echo "<thead>";
					echo' <tr>';
					echo' <th><h1>ID</h1></th>';
					echo' <th><h1>Name</h1></th>';
					echo' <th><h1>Email</h1></th>';
					echo' <th><h1>Class</h1></th>';
					echo' <th><h1>Phone</h1></th>';
					echo' <th><h1>Day</h1></th>';
					echo' <th><h1>Vacation</h1></th>';
					echo'</tr>';
					echo "</thead>";
					echo '<tbody>';
	        foreach($results as $row){
						if($row->vacation == NULL || ($row->vacation < date('Y-m-d')))
						{
							echo '<tr bgcolor="#008000">';
						}
						else {
							echo '<tr bgcolor="#808080">';
						}
						echo '<td>'.$row->id.'</td>';
						echo '<td>'.$row->name.'</td>';
						echo '<td>'.$row->email.'</td>';
						echo '<td>';
						echo '<form action="" method="post">';
						echo '<input type="hidden" name="id" value="'. $row->id .'">';
						echo '<select name="form_class" onchange="this.form.submit()" >';
						echo "<option value=\"". $row->classId . "\">" .$row->class. "</option>";
						foreach($classResults as $newRow)
						{
							if($newRow->id != $row->classId){
							echo "<option value=\"". $newRow->id . ",". $newRow->class ."\">" .$newRow->class. "</option>";
							}
						}
						echo '</select>';
						echo "</form>";
						echo'</td>';
						echo '<td>'.$row->phone.'</td>';
						echo '<td>'.$row->day.'</td>';
						echo '<td>';
						echo '<form action="" method="post">';
						echo '<input type="hidden" name="id" value="'. $row->id .'">';
						if($row->vacation == NULL)
						{
							echo '<input type="date" name="form_date">';
						}
						else {
							echo '<input type="date" name="form_date" value="' .$row->vacation.'">';
						}

						echo '<input type="submit">';
						echo '</form>';
						echo '</td>';
						echo '</tr>';
	        }
	        echo "</tbody>";
	        echo "</table>";

	    }
	    else
	    {
	        echo 'empty';
	    }
	}
	elseif(array_key_exists('form_class', $_POST)){
		$results = $wpdb->get_results( "SELECT * FROM wp_student"); // Query to fetch data from database table and storing in $results
		$classResults = $wpdb->get_results( "SELECT * FROM wp_classes");
		$string = $_POST['form_class'];
		$str_arr = explode (",", $string);
		$table = 'wp_student';
		$data = array('classId'=>$str_arr[0]);
		$where = array('id'=>$_POST['id']);
		$wpdb->update( $table, $data, $where);
		$table = 'wp_student';
		$data = array('class'=>$str_arr[1]);
		$where = array('id'=>$_POST['id']);
		$wpdb->update( $table, $data, $where);
		if(!empty($results))                        // Checking if $results have some values or not
		{
				echo "<table width='100%' border='0'>";
				echo "<thead>";
				echo' <tr>';
				echo' <th><h1>ID</h1></th>';
				echo' <th><h1>Name</h1></th>';
				echo' <th><h1>Email</h1></th>';
				echo' <th><h1>Class</h1></th>';
				echo' <th><h1>Phone</h1></th>';
				echo' <th><h1>Day</h1></th>';
				echo' <th><h1>Vacation</h1></th>';
				echo'</tr>';
				echo "</thead>";
				echo '<tbody>';
				foreach($results as $row){
					if($row->vacation == NULL || ($row->vacation < date('Y-m-d')))
					{
						echo '<tr bgcolor="#008000">';
					}
					else {
						echo '<tr bgcolor="#808080">';
					}
					echo '<td>'.$row->id.'</td>';
					echo '<td>'.$row->name.'</td>';
					echo '<td>'.$row->email.'</td>';
					echo '<td>';
					echo '<form action="" method="post">';
					echo '<input type="hidden" name="id" value="'. $row->id .'">';
					echo '<select name="form_class" onchange="this.form.submit()" >';
					echo "<option value=\"". $row->classId . "\">" .$row->class. "</option>";
					foreach($classResults as $newRow)
					{
						if($newRow->id != $row->classId){
						echo "<option value=\"". $newRow->id . ",". $newRow->class ."\">" .$newRow->class. "</option>";
						}
					}
					echo '</select>';
					echo "</form>";
					echo'</td>';
					echo '<td>'.$row->phone.'</td>';
					echo '<td>'.$row->day.'</td>';
					echo '<td>';
					echo '<form action="" method="post">';
					echo '<input type="hidden" name="id" value="'. $row->id .'">';
					if($row->vacation == NULL)
					{
						echo '<input type="date" name="form_date">';
					}
					else {
						echo '<input type="date" name="form_date" value="' .$row->vacation.'">';
					}
					echo '<input type="submit">';
					echo '</form>';
					echo '</td>';
					echo '</tr>';
				}
				echo "</tbody>";
				echo "</table>";

		}
		else
		{
				echo 'empty';
		}
	}
	else {
		$results = $wpdb->get_results( "SELECT * FROM wp_student"); // Query to fetch data from database table and storing in $results
		$classResults = $wpdb->get_results( "SELECT * FROM wp_classes");
		$string = $_POST['form_date'];


		$table = 'wp_student';
		$data = array('vacation'=>$string);
		$where = array('id'=>$_POST['id']);
		$wpdb->update( $table, $data, $where);
		if(!empty($results))                        // Checking if $results have some values or not
		{
				echo "<table width='100%' border='0'>";
				echo "<thead>";
				echo' <tr>';
				echo' <th><h1>ID</h1></th>';
				echo' <th><h1>Name</h1></th>';
				echo' <th><h1>Email</h1></th>';
				echo' <th><h1>Class</h1></th>';
				echo' <th><h1>Phone</h1></th>';
				echo' <th><h1>Day</h1></th>';
				echo' <th><h1>Vacation</h1></th>';
				echo'</tr>';
				echo "</thead>";
				echo '<tbody>';
				foreach($results as $row){
					if($row->vacation == NULL || ($row->vacation < date('Y-m-d')))
					{
						echo '<tr bgcolor="#008000">';
					}
					else {
						echo '<tr bgcolor="#808080">';
					}
					echo '<td>'.$row->id.'</td>';
					echo '<td>'.$row->name.'</td>';
					echo '<td>'.$row->email.'</td>';
					echo '<td>';
					echo '<form action="" method="post">';
					echo '<input type="hidden" name="id" value="'. $row->id .'">';
					echo '<select name="form_class" onchange="this.form.submit()" >';
					echo "<option value=\"". $row->classId . "\">" .$row->class. "</option>";
					foreach($classResults as $newRow)
					{
						if($newRow->id != $row->classId){
						echo "<option value=\"". $newRow->id . ",". $newRow->class ."\">" .$newRow->class. "</option>";
						}
					}
					echo '</select>';
					echo "</form>";
					echo'</td>';
					echo '<td>'.$row->phone.'</td>';
					echo '<td>'.$row->day.'</td>';
					echo '<td>';
					echo '<form action="" method="post">';
					echo '<input type="hidden" name="id" value="'. $row->id .'">';
					if($row->vacation == NULL)
					{
						echo '<input type="date" name="form_date">';
					}
					else {
						echo '<input type="date" name="form_date" value="' .$row->vacation.'">';
					}
					echo '<input type="submit">';
					echo '</form>';
					echo '</td>';
					echo '</tr>';
				}
				echo "</tbody>";
				echo "</table>";

		}
		else
		{
				echo 'empty';
		}
	}
}
function display_classes() {
	global $wpdb;
	echo '<form action="" method="post">';
	echo '<p>';
	echo 'Select A Class (required) <br/>';
	$results = $wpdb->get_results( "SELECT * FROM wp_classes");
	echo '<select name="form_class">';
  foreach($results as $row){
	echo "<option value=\"". $row->id . "\">" .$row->class. "</option>";
	}
	echo '</select>';
	echo '</p>';
	echo '<p><input type="submit" name="cf-submitted" value="Go!"></p>';
	echo '</form>';
	if($_POST["form_class"] == "")
	{
		echo " ";
	}
	else {
		$new = $wpdb->get_results( "SELECT * FROM wp_student WHERE classId LIKE " . "'". $_POST['form_class'] ."'");
		if(!empty($new))                        // Checking if $results have some values or not
		{
				echo "<table width='100%' border='0'>"; // Adding <table> and <tbody> tag outside foreach loop so that it wont create again and again
				echo "<tbody>";
				foreach($new as $row){            //putting the user_ip field value in variable to use it later in update query
				echo "<tr>";                           // Adding rows of table inside foreach loop
				echo "<th>ID</th>" . "<td>" . $row->id . "</td>";
				echo "</tr>";
				echo "<td colspan='2'><hr size='1'></td>";
				echo "<tr>";
				echo "<th>Name</th>" . "<td>" . $row->name . "</td>";   //fetching data from user_ip field
				echo "</tr>";
				echo "<td colspan='2'><hr size='1'></td>";
				echo "<tr>";
				echo "<th>Email</th>" . "<td>" . $row->email . "</td>";
				echo "</tr>";
				echo "<td colspan='2'><hr size='1'></td>";
				echo "<tr>";
				echo "<th>Class</th>" . "<td>" . $row->class . "</td>";
				echo "</tr>";
				echo "<td colspan='2'><hr size='1'></td>";
				echo "<tr>";
				echo "<th>Phone</th>" . "<td>" . $row->phone . "</td>";
				echo "</tr>";
				echo "<td colspan='2'><hr size='1'></td>";
				echo "<tr>";
				echo "<th>Day</th>" . "<td>" . $row->day . "</td>";
				echo "</tr>";

				}
				echo "</tbody>";
				echo "</table>";

		}
		else
		{
				echo 'empty';
		}
	}

}
function my_cool_plugin_settings_page() {
global $wpdb;

echo '<div class="wrap">';
echo '<h1>Your Plugin Name</h1>';

echo '<form method="post" action="">';
echo '    <?php settings_fields( \'my-cool-plugin-settings-group\' ); ?>';
echo '    <?php do_settings_sections( \'my-cool-plugin-settings-group\' ); ?>';
echo '    <table class="form-table">';
echo '        <tr valign="top">';
echo '        <th scope="row">Class Name</th>';
echo '        <td><input type="text" name="form_name" value="" /></td>';
echo '        </tr>';

echo '        <tr valign="top">';
echo '        <th scope="row">Class ID</th>';
echo '        <td><input type="text" name="form_id" value="" /></td>';
echo '        </tr>';

echo '        <tr valign="top">';
echo '        <th scope="row">Class Day</th>';
echo '        <td><input type="text" name="form_day" value="" /></td>';
echo '        </tr>';
echo '    </table>';

echo '      <input type="submit" value="Submit">';

echo '</form>';
echo '</div>';

if ($_POST['form_name'] == "")
{
	echo "";
}
else
{

	$wpdb->insert(
		"wp_classes",
		array(
			'class' => $_POST['form_name'],
			'id' => $_POST['form_id'],
			'day' => $_POST['form_day'],

		)
	);
	echo '<font color="green">Information Saved!</font>';
	echo $_POST['form_name']. " ". $_POST['form_id'] . " ". $_POST['form_day'];
}

}

function awesome_page_create() {
    $page_title = 'ClassroomManager';
    $menu_title = 'Classroom Manager';
    $capability = 'edit_posts';
    $menu_slug = 'classroom-manager';
    $function = 'my_awesome_page_display';
    $icon_url = '';
    $position = 61;

    add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
		add_submenu_page('classroom-manager', 'Page1','Add Class', 'edit_posts', 'classroom-manager1','my_cool_plugin_settings_page');
    add_submenu_page('classroom-manager', 'Page2', 'View Classes', 'edit_posts','classroom-manager2', 'display_classes') ;
}

add_shortcode( 'form', 'html_form_code');
add_shortcode( 'foobar', 'data_func' );
register_activation_hook( __FILE__, 'do_second' );
register_activation_hook( __FILE__, 'do_first' );

add_action('admin_menu', 'awesome_page_create');
