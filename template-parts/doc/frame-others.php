<?php global $post; ?>
<div class="bill-wrap">
<div class="container">
<div class="row">
<div class="col-xs-6">
<?php
if ( $post->bill_document_name ) {
	echo '<h1 class="bill-title">';
	echo esc_html( $post->bill_document_name );
	echo '</h1>';
}
?>

<?php if ( $post->bill_client ) : ?>
<h2 class="bill-destination">
<span class="bill-destination-client">
<?php echo esc_html( get_the_title( $post->bill_client ) ); ?>
</span>
<span class="bill-destination-honorific">
<?php
$client_honorific = esc_html( get_post_meta( $post->bill_client, 'client_honorific', true ) );
if ( $client_honorific ) {
	echo $client_honorific;
} else {
	echo '御中';
}
?>
</span>
</h2>
<?php endif; ?>

<div class="bill-message">
<?php echo apply_filters( 'the_content', $post->bill_message ); ?>
</div>


</div><!-- [ /.col-xs-6 ] -->

<div class="col-xs-5 col-xs-offset-1">
<table class="bill-info-table">
<tr>
<th>発行日</th>
<td><?php the_date(); ?></td>
</tr>
</table>

<div class="bill-address-own">
<?php $options = get_option( 'bill-setting', Bill_Admin::options_default() ); ?>
<h4><?php echo esc_html( $options['own-name'] ); ?></h4>
<div class="bill-address"><?php echo nl2br( esc_textarea( $options['own-address'] ) ); ?></div>
<?php
if ( isset( $options['own-seal'] ) && $options['own-seal'] ) {
	$attr = array(
		'id'    => 'bill-seal',
		'class' => 'bill-seal',
		'alt'   => trim( strip_tags( get_post_meta( $options['own-seal'], '_wp_attachment_image_alt', true ) ) ),
	);
	echo wp_get_attachment_image( $options['own-seal'], 'medium', false, $attr );
}
?>
</div><!-- [ /.address-own ] -->
</div><!-- [ /.col-xs-5 col-xs-offset-1 ] -->
</div><!-- [ /.row ] -->
</div><!-- [ /.container ] -->

<div class="container">
	<div class="bill-content">
	<?php the_content(); ?>
	</div><!-- [ /.content ] -->
<?php if ( $post->bill_remarks ) : ?>
<dl class="bill-remarks">
<dt>備考</dt>
<dd>
<?php echo apply_filters( 'the_content', $post->bill_remarks ); ?>
</dd>
</dl>
<?php endif; ?>
</div><!-- [ /.container ] -->
</div><!-- [ /.bill-wrap ] -->
