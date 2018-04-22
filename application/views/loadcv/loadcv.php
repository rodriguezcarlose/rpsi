<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

 
</br>
<div class="container">
	<div class="col-md-20">


			<div class="row">
				<div class="col-md-12">
					<div class="page-header">
						<?= form_open('loadCV/downloads')?>
    						<h3>Descargar Plantilla</h3>
                            <input type="submit" value="Descargar" id="btnCargar" class="button small"  />
                       	<?= form_close()?>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-12">
					<div class="page-header">
						<?php echo form_open_multipart('loadCV/do_upload','id="formulario_ajax"');?>
    						<h3>Cargar M&aacute;quinas de Votaci&oacute;n</h3>
    						<div class="field small-3 column">
                            	<label for="Enum:">Archivo:</label>
    							<input type="file" name="userfile" size="20"/>
    						</div>
                            <input type="submit" value="Cargar" id="btnCargar" class="button small"  />
                            <input type="hidden" name="grabar" />
                       	<?= form_close()?>
					</div>
				</div>
			</div>

	 
		 <?php 
		 
                if (isset($results)) { 
                    echo form_open("LoadCV/guardar");
                	if (isset($links)) {?>
                	
                	    <div class = "row">
                	    	<div class="small-6 columns">
                	    		<div class="dataTables_info" id="dataTable_info" role="status" aria-live="polite">
                	    			Mostrando  <?= count($results)?> de <?= $total_records ?> registros 
                	    		</div>
                	    	</div>
                	    	<?= $links?>
                	    </div>
        	           <?php  } ?>
        	           
           
 
                	<table id="dataTable">
                		<thead>
                			<tr>
                				<td>ID</td>
                 				<!--td>Cod. Estado</td-->
                    			<td>Estado</td>
                                <!--td>Cod. Municipio</td-->
                                <td>Municipio</td>
                                <!--td>cod. Parroquia</td-->
                                <td>Parroquia</td>
                                <td>cod. CV</td>
                                <td>Centro de Votaci&oacute;n</td>
                                <td>Mesa</td>
                                <td>Cod. Instalaci&oacute;n</td>
                                <td>Cod. Apertura</td>
                                <td>Cod. Cierre</td>
                                <td>Cod. Transmici&oacute;n</td>
                                <td>Modelo M&aacute;quina</td>
                                <td>Estatus M&aacute;quina</td>
                                <td>N&uacute;mero Meson</td>
                  			</tr>
                		</thead>
                		<tbody>
                        <?php 
                            $i = 0;
                            foreach ($results as $data) { 
                                $i++;
                            ?>
                                <tr>
                                	<td>
                                		<?= $i?>
                                	</td>
                                
                                    <!--td>
                                    	<?php //$data->codigo_estado ?>
                                    </td-->
                                    <td>
                                    	<?= $data->estado ?>
                                    </td>
                                    <!--td>
                                    <?php //$data->codigo_municipio ?>
                                    </td-->
        							<td>
                                            <?= $data->municipio?>
                                    </td> 
                                    
                                    <!--td>
                                    <?php //$data->codigo_parroquia ?>
                                    </td-->
                                    <td>
                                            <?= $data->parroquia?>
                                    </td> 
                                    
                                    <td>
                                            <?= $data->codigo_centrovotacion?>
                                    </td>  
                                    
                                    <td>
                                    	<?= $data->centro_votacion ?>
                                    </td>
                                    <td>
                                    	<?= $data->mesa ?>
                                    </td> 
                                    <td>
                                    	<?= $data->codigo_instalacion ?>
                                    </td> 
                                    <td>
                                    	<?= $data->codigo_apertura ?>
                                    </td> 
                                    <td>
                                    	<?= $data->codigo_cierre ?>
                                    </td> 
                                    <td>
                                    	<?= $data->codigo_transmision ?>
                                    </td> 
                                    <td>
                                    	<?= $data->modelo_maquina ?>
                                    </td> 
                                    <td>
                                    	<?= $data->id_estatus_maquina ?>
                                    </td> 
                                    <td>
                                    	<?= $data->numero_meson ?>
                                    </td> 
                                </tr>
                                
                        <?php } ?>
    
                		</tbody>
                	</table>
               		 <?php  if (isset($links)) {?>
                	    <div class = "row">
                	    	<div class="small-6 columns">
                	    		<div class="dataTables_info" id="dataTable_info" role="status" aria-live="polite">
                	    			Mostrando  <?= count($results)?> de <?= $total_records ?> registros 
                	    		</div>
                	    	</div>
                	    	<?= $links?>
                	    </div>
        	           <?php  } ?>
        	           
                	<div class="small-12 column text-right buttonPanel">
	  	            	<input type="submit" id="btnEnviar" class="button small right" value="Guardar"  />
                    </div>   	
		 <?php 
                echo form_close();
                }  ?>
	</div>
</div>

