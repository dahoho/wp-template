<?php
// ==========================================================================
// 投稿画面でビジュアルモードを非表示にする
// ==========================================================================
// function disable_visual_editor_in_page()
// {
//   global $typenow;
//   if ($typenow == 'page') {
//     add_filter('user_can_richedit', 'disable_visual_editor_filter');
//   }
// }
// function disable_visual_editor_filter()
// {
//   return false;
// }
// add_action('load-post.php', 'disable_visual_editor_in_page');
// add_action('load-post-new.php', 'disable_visual_editor_in_page');
// ==========================================================================
// MW WP FORMのジュアルエディタを非表示にする
// ==========================================================================
// function disable_visual_editor_in_page() {
//   global $typenow;
//   if( in_array( $typenow, array( 'page' ,'mw-wp-form' ) ) ){
//           add_filter('user_can_richedit', 'disable_visual_editor_filter');
//   }
// }
// function disable_visual_editor_filter(){
//   return false;
// }
// add_action('load-post.php', 'disable_visual_editor_in_page');
// add_action('load-post-new.php', 'disable_visual_editor_in_page');
// ==========================================================================
// ビジュアルエディタ非表示
// ==========================================================================
// add_action('init', function () {
//   remove_post_type_support('カスタム投稿名', 'editor');
// }, 99);
// ==========================================================================
// 【管理画面】 投稿デフォルトメニューを非表示
// ==========================================================================
// function remove_menus()
// {
//   global $menu;
//   remove_menu_page('edit.php'); // 投稿を非表示
// }
// add_action('admin_menu', 'remove_menus');
// ==========================================================================
// カスタム投稿タイプのリビジョン有効
// ==========================================================================
// function my_custom_revision()
// {
//   add_post_type_support('カスタム投稿名', 'revisions');
// }
// add_action('init', 'my_custom_revision');
// ==========================================================================
// WPファビコン非表示
// ==========================================================================
function wp_hide_favicon()
{
  exit;
}
add_action('do_faviconico', 'wp_hide_favicon');
// ==========================================================================
// 不要なタグの出力を停止する
// ==========================================================================
remove_action('wp_head', 'wp_generator'); // WordPressのバージョン
remove_action('wp_head', 'wp_shortlink_wp_head'); // 短縮URLのlink
remove_action('wp_head', 'wlwmanifest_link'); // ブログエディターのマニフェストファイル
remove_action('wp_head', 'rsd_link'); // 外部から編集するためのAPI
remove_action('wp_head', 'feed_links_extra', 3); // フィードへのリンク
remove_action('wp_head', 'print_emoji_detection_script', 7); // 絵文字に関するJavaScript
remove_action('wp_head', 'rel_canonical'); // カノニカル
remove_action('wp_print_styles', 'print_emoji_styles'); // 絵文字に関するCSS
remove_action('admin_print_styles', 'print_emoji_styles'); // 絵文字に関するCSS
remove_filter('comment_text_rss', 'wp_staticize_emoji');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_filter('the_content_feed', 'wp_staticize_emoji');
remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
remove_action('admin_print_scripts', 'print_emoji_detection_script'); // 絵文字に関するJavaScript

// ==========================================================================
// <head>〜</head>内の「global-styles-inline-css」を削除 WordPressバージョン5.9〜 （プロックエディタを使用する場合はコメントアウト）
// ==========================================================================
add_action('wp_enqueue_scripts', 'remove_my_global_styles');
function remove_my_global_styles()
{
  wp_dequeue_style('global-styles');
}
// ==========================================================================
// ビジュアルエディターの自動整形のp削除
// ==========================================================================
function override_mce_options($init_array)
{
  //グローバル変数の宣言
  global $allowedposttags;
  //エディタのビジュアル/テキスト切替でコード消滅を防止（自動整形無効化）
  $init_array['valid_elements']          = '*[*]';
  $init_array['extended_valid_elements'] = '*[*]';
  //aタグ内ですべてのタグを仕様可能に
  $init_array['valid_children']          = '+a[' . implode('|', array_keys($allowedposttags)) . ']';
  $init_array['indent']                  = true;
  //pタグの自動挿入を無効化
  $init_array['wpautop']                 = false;
  $init_array['force_p_newlines']        = false;
  //改行をbrタグに置き換える
  $init_array['force_br_newlines']       = true;
  $init_array['forced_root_block']       = '';
  return $init_array;
}
add_filter('tiny_mce_before_init', 'override_mce_options');

// 固定ページのみ自動的に付与されるpタグやbrタグを無効にする場合
function disable_page_wpautop()
{
  if (is_page()) {
    remove_filter('the_content', 'wpautop');
    remove_filter('the_excerpt', 'wpautop');
  }
}
add_action('wp', 'disable_page_wpautop');
