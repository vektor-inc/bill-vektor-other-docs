<?php
/*
  編集画面 _ 領収書発行のボタン追加
/*-------------------------------------------*/
/**
 * 「その他の書類」編集画面の下部に「この書類を複製」ボタンを出力する関数
 *
 * post_submitbox_start フックで呼ばれ、others 投稿タイプかつ編集権限を持つユーザーにのみ
 * 複製リンクを表示する。リンクには CSRF 対策として bill-vektor テーマの bill_copy_redirect() が
 * 検証する `bill_copy_{ID}` 名前空間の nonce を付与する。
 *
 * @return void
 */
add_action( 'post_submitbox_start', 'bvr_duplicate_others' );
function bvr_duplicate_others() {
	global $post;

	// others 投稿タイプ以外では何も出力しない
	if ( get_post_type() !== 'others' ) {
		return;
	}

	// 編集権限のないユーザーには複製ボタンを表示しない
	if ( ! current_user_can( 'edit_post', $post->ID ) ) {
		return;
	}

	// CSRF 対策として nonce を生成し、リスト画面側と同じ 'bill_copy_{ID}' 名前空間で発行する
	$nonce = wp_create_nonce( 'bill_copy_' . $post->ID );

	// リスト画面側（bill_row_actions_add_duplicate_link）と同じ順序・パラメータで URL を組み立てる
	$links = admin_url() . 'post-new.php?post_type=others'
		. '&master_id=' . $post->ID
		. '&table_copy_type=all&duplicate_type=full'
		. '&_wpnonce=' . $nonce;
	?>

	<div class="duplicate-section">

	<a href="<?php echo esc_url( $links ); ?>" class="button button-default button-block">この書類を複製</a>

	</div><!-- [ / #duplicate-section ] -->
	<?php
}
