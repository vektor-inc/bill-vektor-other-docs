<?php
/*
  編集画面 _ 領収書発行のボタン追加
/*-------------------------------------------*/
add_action( 'post_submitbox_start', 'bvr_duplicate_others' );
/**
 * その他の書類編集画面のサブミットボックスに複製ボタンを追加する関数
 *
 * @return void
 */
function bvr_duplicate_others() {
	global $post;

	if ( empty( $post ) ) {
		return;
	}

	if ( get_post_type() == 'others' ) {
		if ( ! current_user_can( 'edit_post', $post->ID ) ) {
			return;
		}

		$nonce = wp_create_nonce( 'bill_copy_' . $post->ID );
		$links = admin_url() . 'post-new.php?master_id=' . $post->ID . '&_wpnonce=' . $nonce;
	?>

	<div class="duplicate-section">

	<a href="<?php echo esc_url( $links ) . '&post_type=others&table_copy_type=all&duplicate_type=full'; ?>" class="button button-default button-block">この書類を複製</a>

	</div><!-- [ / #duplicate-section ] -->
	<?php } ?>
	<?php
}
