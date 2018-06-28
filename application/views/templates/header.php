<!DOCTYPE html>
<html>
        <head>
                <title>Registro Pruebas Sistema Integrado</title>
                    <link href="<?= base_url()?>Content/foundation/foundation.css" rel="stylesheet"/>
                    <link href="<?= base_url()?>Content/foundation/foundation.mvc.css" rel="stylesheet"/>
                    <link href="<?= base_url()?>Content/foundation/foundation-icons.css" rel="stylesheet"/>
                    <link href="<?= base_url()?>Content/xd.datetimepicker/jquery.datetimepicker.css" rel="stylesheet" />
                    <link href="<?= base_url()?>Content/excle/excle.toast.css" rel="stylesheet"/>
                    <link href="<?= base_url()?>Content/excle/excle.autocomplete.css" rel="stylesheet" />
                    <link href="<?= base_url()?>Content/excle/excle.autocomplete.multiple.css" rel="stylesheet" />
                    <link href="<?= base_url()?>Content/Site.css" rel="stylesheet"/>
                   <!--  <script src='https://www.google.com/recaptcha/api.js'></script> -->
                    <script>
                        function validar_texto(e){
                            tecla = (document.all) ? e.keyCode : e.which;
                        
                            //Tecla de retroceso para borrar, siempre la permite
                            if (tecla==8){
                                return true;
                            }
                                
                            // Patron de entrada, en este caso solo acepta numeros
                            patron =/[A-Za-z0-9]/;
                            
                            tecla_final = String.fromCharCode(tecla);
                            
                            return patron.test(tecla_final);
                        }

                        function validar_numeros(e){
                            tecla = (document.all) ? e.keyCode : e.which;

                            //Tecla de retroceso para borrar, siempre la permite
                            if (tecla==8){
                                return true;
                            }

                            // Patron de entrada, en este caso solo acepta numeros
                            patron =/[0-9]/;

                            tecla_final = String.fromCharCode(tecla);

                            return patron.test(tecla_final);
                        }
					</script>
                
        </head>
        <body>
        	
            <script src="<?= base_url()?>Scripts/jquery-1.10.2.js"></script>
            <script src="<?= base_url()?>Scripts/modernizr-2.6.2.js"></script>
        
            <script src="<?= base_url()?>Scripts/foundation/fastclick.js"></script>
            <script src="<?= base_url()?>Scripts/foundation/foundation.js"></script>
            <script src="<?= base_url()?>Scripts/foundation/foundation.abide.js"></script>
            <script src="<?= base_url()?>Scripts/foundation/foundation.accordion.js"></script>
            <script src="<?= base_url()?>Scripts/foundation/foundation.alert.js"></script>
            <script src="<?= base_url()?>Scripts/foundation/foundation.clearing.js"></script>
            <script src="<?= base_url()?>Scripts/foundation/foundation.dropdown.js"></script>
            <script src="<?= base_url()?>Scripts/foundation/foundation.equalizer.js"></script>
            <script src="<?= base_url()?>Scripts/foundation/foundation.interchange.js"></script>
            <script src="<?= base_url()?>Scripts/foundation/foundation.joyride.js"></script>
            <script src="<?= base_url()?>Scripts/foundation/foundation.magellan.js"></script>
            <script src="<?= base_url()?>Scripts/foundation/foundation.offcanvas.js"></script>
            <script src="<?= base_url()?>Scripts/foundation/foundation.orbit.js"></script>
            <script src="<?= base_url()?>Scripts/foundation/foundation.reveal.js"></script>
            <script src="<?= base_url()?>Scripts/foundation/foundation.slider.js"></script>
            <script src="<?= base_url()?>Scripts/foundation/foundation.tab.js"></script>
            <script src="<?= base_url()?>Scripts/foundation/foundation.tooltip.js"></script>
            <script src="<?= base_url()?>Scripts/foundation/foundation.topbar.js"></script>
        
            <script src="<?= base_url()?>Scripts/moment.js"></script>
            <script src="<?= base_url()?>Scripts/moment-with-locales.js"></script>
        
            <script src="<?= base_url()?>Scripts/jquery.validate.js"></script>
            <script src="<?= base_url()?>Scripts/app.js"></script>
            <script src="<?= base_url()?>Scripts/jquery.validate.unobtrusive.js"></script>
            <script src="<?= base_url()?>Scripts/jquery.unobtrusive-ajax.js"></script>
            <script src="<?= base_url()?>Scripts/CustomValidations/CustomValidations-Date-ChromeFix.js"></script>
            <script src="<?= base_url()?>Scripts/CustomValidations/CustomValidations-dateformat.js"></script>
            <script src="<?= base_url()?>Scripts/CustomValidations/CustomValidations-daterange.js"></script>
            <script src="<?= base_url()?>Scripts/CustomValidations/CustomValidations-mindate.js"></script>
            <script src="<?= base_url()?>Scripts/CustomValidations/CustomValidations-requiredIf.js"></script>
            <script src="<?= base_url()?>Scripts/CustomValidations/CustomValidations-showErrors.js"></script>
        
            <script src="<?= base_url()?>Scripts/xd.datetimepicker/jquery.datetimepicker.full.min.js"></script>
            <script src="<?= base_url()?>Scripts/excle/jquery.excle.toast.js"></script>
            <script src="<?= base_url()?>Scripts/excle/jquery.excle.confirm-with-reveal.js"></script>
            
            <script src="<?= base_url()?>Scripts/excle/jquery.excle.xdsoft.datetimepicker.js"></script>
            <script src="<?= base_url()?>Scripts/excle/jquery.excle.sidebar.js"></script>
            <script>
                $(document).foundation();
        
                jQuery.excle.sideBar({
                    element: '#EC-Menu'
                });
            </script>
            
            
       