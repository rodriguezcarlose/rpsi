<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h2 class="show-for-small-only"></h2>

<br>

<div class="container">

	<div class="row">
    	<div class="col-md-12">
    		<div class="page-header">
				<h3>Editar Usuario</h3>
				<?= form_open() ?>
    				<div class="field small-3 columns">
                		<label for="String:">Documento Identidad:</label>
                		<input id="documentob" name="documentob" type="text" value="" onkeypress="return validar_texto(event)"/>
            		</div>
            		<input id="buscar" name="buscar" type="hidden" value="b"/>
            		<input type="submit" id="btnEnviar" class="button small" value="Buscar"/>
            	<?= form_close() ?>
						
    		</div>
    	</div>
    </div>
    <?php if (isset($id_usuario)){?>			
    	<div class="row">
    		<div class="col-md-12">
    		<h3>Usuario</h3>
    		<br>
    		<?= form_open() ?>
    				<input id="id_usuario" name="id_usuario" type="hidden" value="<?= isset($id_usuario)?$id_usuario:""; ?>"/>
    				<input id="id_empleado" name="id_empleado" type="hidden" value="<?= isset($id_empleado)?$id_empleado:""; ?>">
    				
    				<div class="field small-3 columns">
                		<label for="String:">Nombre:</label>
                		<input id="nombre" name="nombre" type="text" value="<?= isset($nombre)?$nombre:""; ?>"/>
            		</div>
            		<div class="field small-3 columns">
                		<label for="String:">Apellido:</label>
                		<input id="apellido" name="apellido" type="text" value="<?= isset($apellido)?$apellido:""; ?>" />
            		</div>
            		
            		 <div class="field small-3 columns">
                		<label for="String:">Tipo Documento:</label>
                		<select id="tipo_doc" name="tipo_doc" >
    						<?php 
    						if (isset($tipoDocumentoIdentidad)) { 
                                $selected = false;
                                foreach ($tipoDocumentoIdentidad->result() as $data) { 
                                    if ($data->id == $tipo_doc){
                            	       $selected = true;
                            ?>
                            			<option selected="selected" value="<?= $data->id ?>"><?= $data->descripcion ?></option>
                            <?php 
                                    }else{
                            ?>
                            			<option value="<?= $data->id ?>"><?= $data->descripcion ?></option>
                            <?php 
                                    } 
                                }
                                if ($selected){
                            ?>
                            		<option value="">Seleccione</option>
                            <?php 
                                }else{
                            ?>
                            		<option selected="selected" value="">Seleccione</option>
                            <?php 
                                }
                            }
                            ?>
                    	</select>
                	</div>
                	
                	<div class="field small-3 columns">
                		<label for="String:">Documento Identidad:</label>
                		<input id="documento" name="documento" type="text" value="<?= isset($documento)?$documento:""; ?>" maxlength = "9" onkeypress="return validar_texto(event)"/>
            		</div>
            		
            		 <div class="field small-3 columns">
    					<label for="username">Email:</label>
    					<input id="email" name="email" type="text" value="<?= isset($email)?$email:""; ?>"/>
    				</div>        		
            		
            		
                	
                	
            		 
    
    				
    				<div class="field small-3 columns">
                		<label for="String:">Rol:</label>
                		<select id="rol" name="rol" >
    						<?php 
    						if (isset($rollist)) { 
                                $selected = false;
                                foreach ($rollist->result() as $data) { 
                                    if ($data->id == $rol){
                            	       $selected = true;
                            ?>
                            			<option selected="selected" value="<?= $data->id ?>"><?= $data->nombre ?></option>
                            <?php 
                                    }else{
                            ?>
                            			<option value="<?= $data->id ?>"><?= $data->nombre ?></option>
                            <?php 
                                    } 
                                }
                                if ($selected){
                            ?>
                            		<option value="">Seleccione</option>
                            <?php 
                                }else{
                            ?>
                            		<option selected="selected" value="">Seleccione</option>
                            <?php 
                                }
                            }
                            ?>
                    	</select>
                	</div>
                	
                	<div class="field small-3 columns">
                		<label for="String:"><input id="resetear" name="resetear" type="checkbox" value="resetear"/> Resetear Contraseña</label>
                	</div>
                	<div class="field small-3 columns">
                		<label for="String:"><input id="eliminar" name="eliminar" type="checkbox" value="eliminar"/> Eliminar Usuario</label>
                	</div>
                	
    
    				<div class="small-12 column text-right buttonPanel">
                        <input type="submit" id="btnCloseModalEditor" class="button small right alert" value="Cancelar" formaction="<?= base_url()?>">
                        <input type="submit" id="btnEnviar" class="button small right" value="Aceptar"/>
           			</div>
    
    			<?= form_close() ?>
    		</div>
    	</div><!-- .row -->
    <?php }?>
</div><!-- .container -->