
<?php /**
 * Plugin Name: Classroom Manager
 * Plugin URI: http://www.mywebsite.com/my-first-plugin
 * Description: The very first plugin that I have ever created.
 * Version: 1.0
 * Author: Arnav Chawla
 * Author URI: http://www.mywebsite.com
 */ global $jal_db_version; $jal_db_version = '1.0'; function do_first() {
	global $wpdb;
	global $jal_db_version;
	$table_name = $wpdb->prefix . 'student';

	$charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		name text NOT NULL,
		class text NOT NULL,
		email text NOT NULL,
		phone text NOT NULL,
		day text NOT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	add_option( 'jal_db_version', $jal_db_version );
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
	$wpdb->insert(
		$table_name,
		array(
			'time' => current_time( 'mysql' ),
			'name' => $_POST['form_name'],
			'class' => $_POST['form_class'],
			'email' => $_POST['form_email'],
			'phone' => $_POST['form_phone'],
			'day' => $_POST['form_day'],

		)
	);
	return '<font color="green">Information Saved!</font>';

}
function html_form_code() {
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
	echo '<select name="form_class">';
	echo '<option value="FTC1">FTC1</option>';
	echo '<option value="FTC2">FTC2</option>';
	echo '<option value="FTC3">FTC3</option>';
	echo '<option value="FTC4">FTC4</option>';
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
    $results = $wpdb->get_results( "SELECT * FROM wp_student"); // Query to fetch data from database table and storing in $results

    if(!empty($results))                        // Checking if $results have some values or not
    {
        echo "<table width='100%' border='0'>"; // Adding <table> and <tbody> tag outside foreach loop so that it wont create again and again
        echo "<tbody>";
        foreach($results as $row){            //putting the user_ip field value in variable to use it later in update query
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
function awesome_page_create() {
    $page_title = 'ClassroomManager';
    $menu_title = 'Classroom Manager';
    $capability = 'edit_posts';
    $menu_slug = 'classroom-manager';
    $function = 'my_awesome_page_display';
    $icon_url = '';
    $position = 61;

    add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
}

add_shortcode( 'form', 'html_form_code');
add_shortcode( 'foobar', 'data_func' );
register_activation_hook( __FILE__, 'do_first' );
add_action('admin_menu', 'awesome_page_create');
