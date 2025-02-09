<script>
	function hasWebAuthnSupport() {
		return ( window.PublicKeyCredential !== undefined || typeof window.PublicKeyCredential === "function" );
	}

	function removeBanner() {
		$( '#browser-supported' ).addClass( 'hidden' );
		Cookies.set( 'dismissAlert', true, { path: '' } );
	}

	$( document ).ready( function() {
		const emailValueInCookie = Cookies.get('email');
		const alertDismissed = Cookies.get( 'dismissAlert' );

		if ( emailValueInCookie ){
			$( '#email' ).val( emailValueInCookie );
			$( '#wachtwoord' ).focus();
		}

		if ( hasWebAuthnSupport() && ! alertDismissed ) {
			$( '#browser-supported' ).removeClass( 'hidden' );
		}

		$( '#form-signin' ).submit( async function( e ) {
			const password = $( '#wachtwoord' ).val();
			const email = $( '#email' ).val();

			Cookies.set( 'email', email, { path: '' } );

			if ( password != "" ) {
				return;
			}

			if ( !hasWebAuthnSupport() ) {
				return;
			}

			e.preventDefault();


			$.ajax( {
				method: "POST",
				url: "/webauthn/prepare_for_login",
				data: { email: email },
				dataType: "json",
				success: function( r ) {
					webauthnAuthenticate( r, function( success, info ) {
						if ( success ) {
							$.ajax( {
								method: "POST",
								url: '/webauthn/authenticate',
								data: { auth: info, email: email },
								dataType: "json",
								success: function() {
									window.location.replace( '/' );
								},
								error: function( xhr, status, error ) {
									alert( "login failed: " + error + ": " + xhr.responseText );
								},
							} );
						} else {
							alert( info );
						}
					} );
				},
				error: function( xhr, status, error ) {
					alert( "couldn't initiate login: " + error + ": " + xhr.responseText );
				}
			} )
		} );
	} );
</script>
<div class="row" style="width: 100%">
	<div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
		<div class="alert alert-info alert-dismissible hidden" id="browser-supported" onclick="removeBanner()" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="removeBanner()">
				<span aria-hidden="true">&times;</span>
			</button>
			<strong><?= lang( 'inloggen_browser_supported' ); ?></strong>
		</div>
		<?php echo form_error( 'wachtwoord' ); ?>
		<?php echo form_error( 'email' ); ?>
		<?php echo form_open( 'inloggen', [ 'class' => 'form-signin', 'id' => 'form-signin' ] ); ?>
		<input type="email" name="email" id="email" class="form-control" placeholder="Email"
		       value="<?php echo set_value( 'email' ); ?>" autofocus autocomplete="username">
		<input type="password" name="wachtwoord" id="wachtwoord" class="form-control"
		       placeholder="<?= lang( 'inloggen_password' ) ?>" autocomplete="current-password" >
		<button class="btn btn-lg btn-primary btn-block" type="submit"><?= lang( 'inloggen_login' ) ?></button>
		<a href="/inloggen/forgot_password"
		   class="pull-right need-help"><?= lang( 'inloggen_forgot_password' ) ?></a><span class="clearfix"></span>
		<input type="hidden" name="redirect" value="<?= $redirect ?>">
		<?= form_close() ?>
	</div>
</div>
