<?php

/* Change the default tab opened when looking at a user’s profile */
define( 'BP_DEFAULT_COMPONENT', 'profile' );

/* restrict post revisions */
define('WP_POST_REVISIONS', 5);

/* change post autosave interval */
define('AUTOSAVE_INTERVAL', 300 );

/* disable admin bar */
add_filter( 'show_admin_bar', '__return_false' );

/* enable old theme directory */
add_filter( 'bp_do_register_theme_directory', '__return_true' );

/*
 * password strength meter
 * js code: wp-admin/js/password-strength-meter.js
 */
function check_password_strength( $username, $password1, $password2 ) {
	$short  = 1;
	$bad    = 2;
	$good   = 3;
	$strong = 4;

	//password < 4
	if ( strlen( $password1 ) < 4)
		return $short;

	//password1 == username
	if ( strtolower( $password1 ) == strtolower( $username ) )
		return $bad;

	$symbolSize = 0;
	if ( preg_match( '/[0-9]/', $password1 ) )
		$symbolSize += 10;
	if ( preg_match( '/[a-z]/', $password1 ) )
		$symbolSize += 26;
	if ( preg_match( '/[A-Z]/', $password1 ) )
		$symbolSize += 26;
	if ( preg_match( '/[^a-zA-Z0-9]/', $password1 ) )
		$symbolSize += 31;

	$natLog = log( pow( $symbolSize, strlen( $password1 ) ) );
	$score = $natLog / log( 2 );

	if ( $score < 40 )
		return $bad;

	if ( $score < 56 )
		return $good;

	return $strong;
}

if ( !function_exists('wp_new_user_notification') ) :
/**
 * Notify the blog admin of a new user, normally via email.
 *
 * @since 2.0
 *
 * @param int $user_id User ID
 * @param string $plaintext_pass Optional. The user's plaintext password
 */
function wp_new_user_notification($user_id, $plaintext_pass = '') {
	$user = new WP_User($user_id);

	$user_login = stripslashes($user->user_login);
	$user_email = stripslashes($user->user_email);
	$nice_name = stripslashes($user->user_nicename);

	// The blogname option is escaped with esc_html on the way into the database in sanitize_option
	// we want to reverse this for the plain text arena of emails.
	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	$message  = sprintf(__('New user registration on your site %s:'), $blogname) . "\r\n\r\n";
	$message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
	$message .= sprintf(__('E-mail: %s'), $user_email) . "\r\n";

	@wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration'), $blogname), $message);

	if ( empty($plaintext_pass) )
		return;

	$message =
		'クローバーカフェのアカウントを作成しました。'. "\r\n" .
		'みなさまの情報共有に役立てていただければ幸いです。' . "\r\n\r\n" .
		'------------------------------------------------' . "\r\n" .
		sprintf(__('Username: %s'), $user_login) . "\r\n" .
		sprintf(__('Password: %s'), $plaintext_pass) . "\r\n\r\n" .
		'下記のURLにアクセスしてログインしてください。' . "\r\n" .
		site_url() . "\r\n" .
		'------------------------------------------------' . "\r\n\r\n" .
		'▽パスワードを変更する' . "\r\n" .
		site_url() . '/members/' . $nice_name . '/settings/' . "\r\n\r\n" .
		'▽プロフィールを変更する' . "\r\n" .
		site_url() . '/members/' . $nice_name . '/profile/' . "\r\n\r\n" .
		'------------------------------------------------' . "\r\n" .
		'本メールにお心当たりがない場合は、恐れ入りますが' . "\r\n" .
		'下記の連絡先までご連絡ください。' . "\r\n" .
		'------------------------------------------------' . "\r\n";

	$message = apply_filters( 'cc_new_user_notification_message', $message );

	wp_mail($user_email, 'アカウント登録のお知らせ', $message);

}
endif;

/* for DEBUG */
function console_log( $msg ) {
	echo '<script type="text/javascript">console.log(';
	if ( is_array( $msg ) ) {
		$string = '"';
		foreach ($msg as $item) {
			$string .= $item . ' | ';
		}
		$string .= '"';
		echo $string;
	} else {
		echo '"' . $msg . '"';
	}
	echo ');</script>';
}

?>
