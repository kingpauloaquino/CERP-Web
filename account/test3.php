<html>
<head>
    <link rel="stylesheet" href="estilo.css"/>
    <meta charset="UTF-8"/>
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script type="text/javascript">
    	// $(function(){
			    // $(".tile").mousedown(function(){      
			        // $(this).addClass("selecionado");
			    // });
// 			  
			    // $(".tile").mouseup(function(){    
			        // $(this).removeClass("selecionado");
			    // });
			// });
    </script>
    <style>
/*    	@font-face { font-family: Century; src: url('GOTHIC.ttf'); }*/
  
			body{
			    font-family: Century;
			    background: rgb(51,51,51);
			    color: #fff;
			    padding:20px;
			}
			  
			.pagina{
			    width:auto;
			    height:auto;    
			}
			  
			.linha{
			    width:auto;
			    padding:5px;
			    height:auto;
			    display:table;     
			}
			
			.tile{
			    height:100px;  
			    width:100px; 
			    float:left;
			    margin:0 5px 0 0;
			    padding:2px;
			}
			  
			.tileLargo{
			    width:210px;
			}

			.amarelo{
			    background:#DAA520;
			}
			  
			.vermelho{
			    background:#CD0000; 
			}
			  
			.azul{
			    background:#4682B4;
			}
			  
			.verde{
			    background-color: #2E8B57;
			}

    </style>
</head>
<body>
  <div class="pagina">
    <div class="linha">         
        <div class="tile amarelo">
        </div>
        <div class="tile azul">
        </div>
        <div class="tile tileLargo vermelho">
        </div>
        <div class="tile verde">
        </div>          
        <div class="tile tileLargo amarelo">
        </div>
    </div>
    <div class="linha">         
        <div class="tile tileLargo amarelo">
        </div>
        <div class="tile azul">
        </div>          
        <div class="tile verde">
        </div>
        <div class="tile vermelho">
        </div>          
        <div class="tile tileLargo verde">
        </div>
    </div>      
    <div class="linha">         
        <div class="tile amarelo">
        </div>
        <div class="tile verde">
        </div>
        <div class="tile vermelho">
        </div>          
        <div class="tile tileLargo verde">
        </div>
        <div class="tile azul">
        </div>
        <div class="tile verde">
        </div>  
    </div>
</div>

</body>
</html>


