<?php

/* Change the default tab opened when looking at a user’s profile */
define( 'BP_DEFAULT_COMPONENT', 'profile' );

/* disable admin bar */
add_filter( 'show_admin_bar', '__return_false' );

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
		'------------------------------------------------' . "\r\n\r\n" .
		"-- \r\n" . 'クローバーカフェ運営チーム一同' . "\r\n" .
		'連絡先：' . get_option('admin_email') . "\r\n";

	wp_mail($user_email, 'アカウント登録のお知らせ', $message);

}
endif;

?>
