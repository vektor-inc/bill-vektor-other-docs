<?php
/*
* 請求書のカスタムフィールド（品目以外）
*/

class Report_Normal_Custom_Fields {
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_metabox' ), 10, 2 );
		add_action( 'save_post', array( __CLASS__, 'save_custom_fields' ), 10, 2 );
	}

	// add meta_box
	public static function add_metabox() {

		$id            = 'meta_box_bill_normal';
		$title         = '業務月報項目';
		$callback      = array( __CLASS__, 'fields_form' );
		$screen        = 'others';
		$context       = 'advanced';
		$priority      = 'high';
		$callback_args = '';

		add_meta_box( $id, $title, $callback, $screen, $context, $priority, $callback_args );

	}

	public static function fields_form() {
		global $post;

		$custom_fields_array = Report_Normal_Custom_Fields::custom_fields_array();
		$befor_custom_fields = '';
		VK_Custom_Field_Builder::form_table( $custom_fields_array, $befor_custom_fields );
	}

	public static function save_custom_fields() {
		$custom_fields_array = Report_Normal_Custom_Fields::custom_fields_array();
		// $custom_fields_array_no_cf_builder = arra();
		// $custom_fields_all_array = array_merge(  $custom_fields_array, $custom_fields_array_no_cf_builder );
		VK_Custom_Field_Builder::save_cf_value( $custom_fields_array );
	}

	public static function custom_fields_array() {

		$args         = array(
			'post_type'      => 'client',
			'posts_per_page' => -1,
			'order'          => 'ASC',
			'orderby'        => 'title',
		);
		$client_posts = get_posts( $args );
		if ( $client_posts ) {
			$client = array( '' => '選択してください' );
			foreach ( $client_posts as $key => $post ) {
				// プルダウンに表示するかしないかの情報を取得
				$client_hidden = get_post_meta( $post->ID, 'client_hidden', true );
				// プルダウン非表示にチェックが入っていない項目だけ出力
				if ( ! $client_hidden ) {
						$client[ $post->ID ] = $post->post_title;
				}
			}
		} else {
			$client = array( '0' => '請求先が登録されていません' );
		}

		$custom_fields_array = array(
			'bill_document_name' => array(
				'label'       => '書類の表記',
				'type'        => 'text',
				'description' => '',
				'required'    => false,
				'description' => '例）業務月報',
			),
			'bill_client'        => array(
				'label'       => __( '取引先', 'bill-vektor' ),
				'type'        => 'select',
				'description' => '取引先は<a href="' . admin_url( '/post-new.php?post_type=client' ) . '" target="_blank">こちら</a>から登録してください。',
				'required'    => true,
				'options'     => $client,
			),
			'bill_message'       => array(
				'label'       => __( 'メッセージ', 'bill-vektor' ),
				'type'        => 'textarea',
				'description' => '例）以下の通り作業しました',
				'required'    => false,
			),
			'bill_remarks'       => array(
				'label'       => __( '備考', 'bill-vektor' ),
				'type'        => 'textarea',
				'description' => '',
				'required'    => false,
			),
			'bill_memo'          => array(
				'label'       => __( 'メモ', 'bill-vektor' ),
				'type'        => 'textarea',
				'description' => 'この項目は印刷されません。',
				'required'    => false,
			),
			'bill_send_pdf'      => array(
				'label'       => __( '送付済PDF', 'bill-vektor' ),
				'type'        => 'file',
				'description' => '客先に送付したPDFファイルを保存しておく場合に登録してください。',
				'hidden'      => true,
			),
		// 'event_image_main' => array(
		// 'label' => __('メインイメージ','bill-vektor'),
		// 'type' => 'image',
		// 'description' => '',
		// 'hidden' => true,
		// ),
		);
		return $custom_fields_array;
	}

}
Report_Normal_Custom_Fields::init();
