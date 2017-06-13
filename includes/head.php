<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
            <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>

            <link rel="stylesheet" type="text/css" href="css/style1.css">
                <SCRIPT src="js/ajax.js" language="javascript" type="text/javascript"></SCRIPT>
                <SCRIPT src="js/common.js" language="javascript" type="text/javascript"></SCRIPT>
                <script language='javascript' type='text/javascript' src='js/perpage.js'></script>

                <!--script language="javascript" src="js/requiredValidation.js"></script--> 
                <script language="javascript" src="js/validation.js"></script>
                <script language="javascript" src="js/country.js"></script>
				

                    <script type="text/javascript" src="js/jquery-1.9.1.js" />
                    <script language='javascript' type='text/javascript' src='images/resources/navBar.js'></script>
					<SCRIPT src="js/support.js"></SCRIPT>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            $('div.Success-Msg').delay(3000).fadeOut(3000, function() {
                                $(this).hide(3000);
                            });
							$('div.Error-Msg').delay(3000).fadeOut(3000, function() {  
                                $(this).hide(3000); 
                            });
                            $('div.sanotifBtn').mouseleave(function() {
                                //$( "div.sautilDropDwn" ).trigger( "mouseleave" );	
                            });	

							$('pre').each(function(index) { 
								$(this).html($(this).text()); 
							}); 

							$.tablesorter.addParser({
								id: "datetime",
								is: function(s) {
									return false; 
								},
								format: function(s,table) {
									s = s.replace(/\-/g,"/");
									s = s.replace(/(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})/, "$3/$2/$1");
									return $.tablesorter.formatFloat(new Date(s).getTime());
								},
								type: "numeric"
							});			
							
                        });



                        function divHide(divToHideOrShow)
                        {
                            var div = document.getElementById(divToHideOrShow);
                            div.style.display = "none";
                        }

                        $("div.sautilDropDwn").mouseleave(function() {
                            $('div.sautilDropDwn').hide();
                        });

                        function divShow(divToHideOrShow)
                        {
                            $('div.sautilDropDwn').hide();
                            $("div.sautilDropDwn").mouseleave(function() {
                                $('div.sautilDropDwn').hide();
                            });

                            var div = document.getElementById(divToHideOrShow);
                            div.style.display = "block";
                        }
						
					/* 	function hideAlert() 
                        {
                            $('div#alert-home').delay(500).fadeOut(500, function() {  
                                $(this).hide(500);   
                            });                        
                        } */

                    </script>


                    <!-- Custom js for header menu -->

                    <script type="text/javascript">
                        $(function() {
                            var $oe_menu = $('#oe_menu');
                            var $oe_menu_items = $oe_menu.children('li');
                            var $oe_overlay = $('#oe_overlay');
                            var $img = '';


                            $oe_menu_items.bind('mouseenter', function() {
                                var $this = $(this);
                                $this.addClass('slided selected');
                                //	$this.children('div').css('border','1px solid #025FBC');
                                $this.children('div').css('z-index', '9999').stop(true, true).show(1, function() {
                                    $oe_menu_items.not('.slided').children('div').hide();
                                    $this.removeClass('slided');
                                });
                                // img over
                                $img = $this.find('a:first').css('background-image');
                                bg = $img.replace(/^url\(['"]?/, '').replace(/['"]?\)$/, '').replace(/^.*\/|\.[^.]*$/g, '');
                                $url = "images/resources/" + bg + "-hover.png";
                                $this.find('a:first').css('background-image', 'none');
                                $this.find('a:first').css('background-image', "url('" + $url + "')");

                            }).bind('mouseleave', function() {
                                var $this = $(this);
                                $this.removeClass('selected').children('div').css('z-index', '1');

                                // img leave	
                                $img = $this.find('a:first').css('background-image');
                                bg = $img.replace(/^url\(['"]?/, '').replace(/['"]?\)$/, '').replace(/^.*\/|\.[^.]*$/g, '').replace('-hover', '');
                                $url = "images/resources/" + bg + ".png";
                                $this.find('a:first').css('background-image', 'none');
                                $this.find('a:first').css('background-image', "url('" + $url + "')");
                            });

                            $oe_menu.bind('mouseenter', function() {
                                var $this = $(this);
                                $oe_overlay.stop(true, true).fadeTo(200, 0.6);
                                $this.addClass('hovered');

                            }).bind('mouseleave', function() {
                                var $this = $(this);
                                $this.removeClass('hovered');
                                $oe_overlay.stop(true, true).fadeTo(200, 0);
                                $oe_menu_items.children('div').hide();
                            })
                        });
                    </script>
                    <!--light box end-->

                    <!-- Google Analytics -->

                    <script>
                    (function(i,s,o,g,r,a,m){
                        i['GoogleAnalyticsObject']=r;
                        i[r]=i[r]||function(){
                            (i[r].q=i[r].q||[]).push(arguments)
                        },i[r].l=1*new Date();
                        a=s.createElement(o),
                        m=s.getElementsByTagName(o)[0];
                        a.async=1;a.src=g;
                        m.parentNode.insertBefore(a,m)
                    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

                     ga('create', 'UA-51795296-1', 'netapp.com');
               //     ga('create', 'UA-51795296-1', 'auto');
                    ga('send', 'pageview');

                    </script>

                    <!-- Google Analytics end -->