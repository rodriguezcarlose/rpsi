<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h2 class="show-for-small-only"></h2>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="page-header">
				<h3>Crear Usuario</h3>
			</div>
			<?= form_open() ?>
				<div class="field small-3 columns">
            		<label for="String:">Nombre:</label>
            		<input id="nombre" name="nombre" type="text" value="" />
        		</div>
        		<div class="field small-3 columns">
            		<label for="String:">Apellido:</label>
            		<input id="apellido" name="apellido" type="text" value="" />
        		</div>
        		
        		 <div class="field small-1 columns">
            		<label for="String:">Tipo</label>
            		<select data-val="true" data-val-required="The Enum field is required." id="Enum" name="Enum">
            			<option selected="selected" value="0">V</option>
						<option value="1">E</option>
						<option value="2">P</option>
						<option value="3">J</option>
					</select>
            	</div>
            	
            	<div class="field small-3 columns">
            		<label for="String:">Documento Identidad:</label>
            		<input id="documento" name="documento" type="text" value="" />
        		</div>
        		
        		 <div class="field small-3 columns">
					<label for="username">Email:</label>
					<input type="text" class="form-control" id="email" name="email" placeholder="Email">
				</div>        		
        		
        		<div class="field small-3 columns">
            		<label for="String:">Cargo:</label>
            		<select data-val="true" data-val-required="The Enum field is required." id="Enum" name="Enum">
            			<option selected="selected" value="0">V</option>
						<option value="1">E</option>
						<option value="2">P</option>
						<option value="3">J</option>
					</select>
            	</div>
            	
            	<div class="field small-3 columns">
            		<label for="String:">Gerencia:</label>
            		<select data-val="true" data-val-required="The Enum field is required." id="Enum" name="Enum">
            			<option selected="selected" value="0">V</option>
						<option value="1">E</option>
						<option value="2">P</option>
						<option value="3">J</option>
					</select>
            	</div>
        		 

				
				<div class="field small-3 columns">
            		<label for="String:">Rol:</label>
            		<select data-val="true" data-val-required="The Enum field is required." id="Enum" name="Enum">
            			<option selected="selected" value="0">V</option>
						<option value="1">E</option>
						<option value="2">P</option>
						<option value="3">J</option>
					</select>
            	</div>

				<div class="small-12 column text-right buttonPanel">
                    <input type="submit" id="btnCloseModalEditor" class="button small right alert" value="Cancelar" formaction="<?= base_url()?>">
                    <input type="submit" id="btnEnviar" class="button small right" value="Aceptar"/>
       			</div>


			<?= form_close() ?>
		</div>
	</div><!-- .row -->
</div><!-- .container -->