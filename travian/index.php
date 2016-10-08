<?php
	use yii\helpers\Html;
	use yii\grid\GridView;
	use yii\widgets\Pjax;
	use yii\bootstrap\Modal;
	
	\yii\bootstrap\BootstrapPluginAsset::register( $this );
	
	$controller = $this->context;

	$this->title = "Battle Angel Travian TOOLS";

	$js = <<< 'SCRIPT'
	$( "#add" ).on( 'click', function(){
		$.ajax({
			type     :'POST',
			dataType : 'json',
			data : ({type: 'add', id: '0'}),
			url  : '/scale/ajaxgetaddmodal',
			success  : function( response )
			{
				$( "#modal-well" ).html( response.html );
				$( '#section-modal' ).modal( 'show' );
			}
		});
	});
	
	$( "#modal-save" ).on( 'click', function(){
		$.ajax({
			type     :'POST',
			dataType : 'json',
			data : ({name: $( '#section-name' ).val(), id: $( '#section-name' ).attr( 'data' )}),
			url  : '/scale/ajaxsectionsave',
			success  : function( response )
			{
				if( response.error )
				{
					$( '#successalert' ).css( 'display', 'none' );
					$( '#erroralert' ).html( response.html );
					$( '#erroralert' ).css( 'display', 'block' );
					$( '#section-modal' ).modal( 'hide' );
				}
				else
				{
					$( '#successalert' ).css( 'display', 'block' );
					$( '#successalert' ).html( response.html );
					$( '#erroralert' ).css( 'display', 'none' );
					$( '#section-modal' ).modal( 'hide' );
					$.pjax.reload({container: '#scale-section', timeout: false});
				}
			}
		});
	});
	
SCRIPT;

	$this->registerJs( $js, yii\web\View::POS_READY );
?>
<style>
</style>
<div class="container col-sm-12" style="margin-top: 10px">
	<div class="alert alert-danger" role="alert" id="erroralert" style="display:none">
	</div>
	<div class="alert alert-success" role="alert" id="successalert" style="display:none">
	</div>
	<?php
		if( sizeof( $error ) > 0 )
		{
			?>
				<div class="alert alert-danger" role="alert">
					Some error(s) found
					 :
					<ul>
					<?php
						for( $a = 0; $a < sizeof( $error ); $a++ )
						{
							?>
								<li>
									<?php echo $error[$a];?>
								</li>
							<?php
						}
					?>
					</ul>
				</div>
			<?php
		}
		
		if( sizeof( $success ) > 0 )
		{
			?>
				<div class="alert alert-success" role="alert">
					<?php echo $success[0]?>
				</div>
			<?php
		}
	?>
	
	<div class="page-header no-top-margin">
		<h4><b><?php echo strtoupper($this->title);?></b></h4>
	</div>
</div>
<?php
	Modal::begin([
		'header' => '<h3 id="modal-header">Add new section</h3>',
		'id' => 'section-modal',
		'footer' => '<button type="button" class="btn btn-default xbtn-save" id="modal-save" style="margin-right: 10px">Save</button><button type="button" class="btn btn-default" data-dismiss="modal" id="modal-close">Cancel</button>',
		'closeButton' => false,
		'options' => ['data-backdrop' => 'static', 'data-keyboard' => false],
	]);
?>
		<div class="well" id="modal-well">
			
		</div>
<?php
	Modal::end();
?>