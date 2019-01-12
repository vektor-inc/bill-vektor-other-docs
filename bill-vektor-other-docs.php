<?php
/**
 * Plugin Name:     BillVektor Other Docs
 * Plugin URI:
 * Description:     BillVektorでその他の書類を発行する事が出来るようになるプラグインです。プラグインを有効化したら「設定 > パーマリンク設定」を一度保存してください。
 * Author:          Vektor,Inc.
 * Author URI:      https://billvektor.com/
 * Text Domain:     bill-vektor-others
 * Domain Path:     /languages
 * Version:         1.1.1
 *
 * @package         Bill_Vektor_Other_Docs
 */

 /*
	 テーマがBillVektorじゃない時は誤動作防止のために読み込ませない
 --------------------------------------------- */
add_action(
	'after_setup_theme', function() {
		if ( ! function_exists( 'bill_get_post_type' ) ) {
			// 読み込まずに終了
			return;
		}
	}
);

 /*
  ---------------------------------------------
	 updater
 --------------------------------------------- */
 require 'inc/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/vektor-inc/bill-vektor-other-docs',
	__FILE__, // Full path to the main plugin file or functions.php.
	'bill-vektor-other-docs'
);
 $myUpdateChecker->setBranch( 'master' );

require_once( 'inc/duplicate-doc.php' );
// require_once( 'inc/custom-field-others/custom-field-others.php' );
require_once( 'inc/custom-field-others/custom-field-others-normal.php' );
add_filter( 'bill-vektor-doc-change', 'bvot_doc_change' );
function bvot_doc_change( $doc_change ) {
	if ( get_post_type() == 'others' ) {
		$doc_change = true;
	}
	  return$doc_change;
}

	add_action( 'bill-vektor-doc-frame', 'bvot_doc_frame_others' );
function bvot_doc_frame_others() {
	if ( get_post_type() == 'others' ) {
		require_once( 'template-parts/doc/frame-others.php' );
	}
}


	/*
	-------------------------------------------
	Add Post Type Receipt
	-------------------------------------------
	*/
	add_action( 'init', 'bill_add_post_type_others', 0 );
function bill_add_post_type_others() {
	register_post_type(
		'others',
		array(
			'labels'             => array(
				'name'         => 'その他の書類',
				'edit_item'    => 'その他の書類の編集',
				'add_new_item' => 'その他の書類の作成',
			),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'has_archive'        => true,
			'supports'           => array( 'title', 'editor' ),
			'menu_icon'          => 'dashicons-media-spreadsheet',
			'menu_position'      => 7,
		// 'show_in_rest'       => true,
		// 'rest_base'          => 'others',
		)
	);
		register_taxonomy(
			'others-cat',
			'others',
			array(
				'hierarchical'          => true,
				'update_count_callback' => '_update_post_term_count',
				'label'                 => 'その他の書類カテゴリー',
				'singular_label'        => 'その他の書類カテゴリー',
				'public'                => true,
				'show_ui'               => true,
			)
		);
}

function bvot_remove_meta_boxes() {
	remove_meta_box( 'commentstatusdiv', 'others', 'normal' );
}
	add_action( 'admin_menu', 'bvot_remove_meta_boxes' );

function bvot_bill_vektor_post_types_custom( $post_type_array ) {
	$post_type_array['others'] = 'その他の書類';
	return $post_type_array;
}
	add_filter( 'bill_vektor_post_types', 'bvot_bill_vektor_post_types_custom' );
