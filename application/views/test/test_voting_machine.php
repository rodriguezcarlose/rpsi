<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
   <h2 class="show-for-small-only"></h2>    
	<br>
   <div class="container">
    <div class="row">
    	<h3>M&aacute;quina de Votaci&oacute;n</h3>
        
    	<?= form_open('/voting_machine/') ?>

                <div class="large-12 medium-4 columns">
                    <label>Centro de votaci&oacute;n</label>
                    <input type="text" placeholder="" name="centrovotacion" id="centrovotacion" disabled value=""/>
                </div>
                <div class="large-4 medium-4 columns">
                    <label>Modelo M&aacute;quina Votaci&oacute;n</label>
                    <input type="text" placeholder="" name="modelomaquina" id="modelomaquina" disabled value=""/>
                </div>
               <div class="large-4 medium-4 columns">
                    <label>N&uacute;mero de mesa</label>
                    <input type="text" placeholder="" name="mesa" id="mesa" disabled value=""/>
                </div> 
               <div class="large-4 medium-4 columns">
                    <label>Estatus</label>
                    <input type="text" placeholder="" name="estatus" id="estatus" disabled value=""/>
                </div> 
               <div class="large-6 medium-4 columns">
                    <label>C&oacute;digo Validaci&oacute;n</label>
                    <input type="text" placeholder="" name="codigo" id="codigo" value=""/>
                </div> 
               <div class="large-6 medium-4 columns">
                    <label>Medio de Transmisi&oacute;n</label>
					<select name="medio" id="medio">
                        <option value="husker">Husker</option>
                        <option value="starbuck">Starbuck</option>
                        <option value="hotdog">Hot Dog</option>
                        <option value="apollo">Apollo</option>
                    </select>
                </div> 

        <br>
        <h3>Registrar Errores</h3>
			<div class="small-4 columns">
                <label class="inline">Buscar Error</label>
            </div>
            <div class="large-6 medium-4 columns">
                <input type="text" placeholder="" name="error" id="error" value=""/>
            </div>
            <div class="small-2 columns">
                <button class="button postfix">Buscar</button>
            </div>
            
        	<br>    
            <table id="dataTable">
                <thead>
                    <tr>
                        <td>Proyecto</td>
                        <td>Gerencia</td>
                        <td>Rol</td>
                        <td>Banco</td>
                        <td>Nro. Cuenta</td>
                        <td>Fecha</td>
                        <td>Estatus</td>
                        <td>Acci&oacute;n</td>
                    </tr>
                </thead>
     			<tbody>                           
                    <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>                                                
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                    </tr>
             
     			</tbody>
    		</table>
    		
            <div class="small-12 column text-right buttonPanel">
                <input id="btnCloseModalEditor" class="button small right alert" value="Cancelar" type="button">
                <input id="btnEnviar" class="button small right" value="Aceptar" type="submit">
            </div>
        <?= form_close() ?>            

    </div>
</div>
