<nav class="top-bar" data-topbar role="navigation">
    <ul class="title-area">
        <li class="name">
            <h1>
                <a href="/" style="float: left">
                    <img src="<?= base_url()?>Content/Images/header-logo.png" />
                </a>
               
                <a href="#" class="show-for-medium-up" style="float: left">  
                        <?php 
                            if (isset($_SESSION['logged_in'])  === true) :
                            echo "Bienvenido: ".$_SESSION['nombre']." ".$_SESSION['apellido'];
                            endif;
                        ?>
                </a>
            </h1>
        </li>
        <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
    </ul>

    <section class="top-bar-section">
        <!-- Right Nav Section -->
        <ul class="right">
				<?php 
                    if (isset($_SESSION['logged_in'])  === true) :
                        // construimos el men�
                        echo '<li class="has-dropdown">';
                        echo '<a href="#">MEN&Uacute</a>';
                        echo '<ul class="dropdown">';

                        $menu = $_SESSION['menu'];
                        $finMenu = false;
                       
                        foreach ($menu as &$valor) {
                            
                            if($valor->nivel === '0'){
                                
                                if ($finMenu){
                                    echo '</ul>';
                                    echo '</li>';
                                }
                                
                                echo '<li class="has-dropdown"><a href="#">'.$valor->nombre.'</a>';
                                echo '<ul class="dropdown">';
                                $finMenu = true;
                            }else{
                                echo '<li><a href="'.base_url().$valor->url.'">'.$valor->nombre.'</a></li>';
                            }
                            
                            
                        }
                        echo '</ul>';
                        echo '</li>';
                        
                        echo '</ul>';
                        echo '</li>';
                    endif;
                ?>
                
		
			<li class="logInOut">
			
    			<?php 
                    if (isset($_SESSION['logged_in'])  === true)
                        echo '<a href="'. base_url().'user/logout">[SALIR]</a>';
                    else 
                        echo '<a href="'. base_url().'user/login">[INGRESAR]</a>';
                ?>
			</li>
        </ul>
    </section>
    
    
</nav>

<div id="toastArea"></div>


<?php if (validation_errors()) : ?>
	<script languaje="javascript">
		var title = "";
		var content = "<?= str_replace("\n", "", validation_errors()); ?>";
		var type = 2;
		toastMessage(title, content, type, null);
	</script>
<?php endif; ?>

<?php if (isset($error)) : ?>
	<script languaje="javascript">
		var title = "";
		var content = "<?= str_replace("\n", "", $error); ?>";
		//var content = "Tets";
		var type = 2;
		toastMessage(title, content, type, null);
	</script>
<?php endif; ?>

<?php if (isset($success)) : ?>
	<script languaje="javascript">
		var title = "";
		var content = "<?= str_replace("\n", "", $success); ?>";
		//var content = "Tets";
		var type = 1;
		toastMessage(title, content, type, null);
	</script>
<?php endif; ?>