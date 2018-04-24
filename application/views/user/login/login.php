<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h2 class="show-for-small-only"></h2>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h3>Ingresar</h3>
			</div>
			<?= form_open() ?>
				<div class="large-4 medium-4 columns">
					<label for="username"><span style="color:red;">*</span>Usuario</label>
					<input type="text" class="form-control" id="username" name="username" placeholder="Usuario">
				</div>
				
				<div class="large-4 medium-4 columns">
					<label for="password"><span style="color:red;">*</span>Clave</label>
					<input type="password" class="form-control" id="password" name="password" placeholder="Clave">
				</div>
				<div class="large-4 medium-4 columns">
                    <label><span style="color:red;">*</span> N&uacute;mero de Meson</label>
                    <input type="text" class="form-control" id="numero_meson" name="numero_meson" placeholder="N&uacute;mero Meson" maxlength = "3" onkeypress="return validar_texto(event)">
                    
                </div>

				<!--  center>
				<div class="g-recaptcha" data-sitekey="6Lco3UwUAAAAALLL9KeaIHOD4Bg6iS0Bwv1HehNC"></div>
				</center-->

				<div class="small-12 column text-right buttonPanel">
                    <input type="submit" id="btnEnviar" class="button small right" value="Aceptar"/>
       			</div>
       			
			<?= form_close() ?>
		</div>
	</div><!-- .row -->
</div><!-- .container -->