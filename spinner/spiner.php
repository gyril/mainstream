<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>MNT Spinner</title>
		<link rel="stylesheet" type="text/css" media="screen" href="atd.css" />
		<style type="text/css">
			#wrapper {
				width:500px;
				margin:0 auto;
				padding:10px;
			}
			
			#id_texte {
				display:block;
				width:500px;
				height:300px;
				margin:0 auto;
			}
			
			input[type=text] {
				height:30px;
				width:120px;
				margin:2px;
				border-radius:2px;
				border:1px solid lightgrey;
			}
			
			input[type=button] {
				height:30px;
				width:120px;
				border:0;
				margin:4px;
				border-radius:10px;
				background-color:lightblue;
				color:white;
				font-weight:bold;
				text-shadow:0 -1px steelblue;
				box-shadow:0 1px 3px 0 black;
				cursor:pointer;
			}
			
			input[type=button]:hover {
				box-shadow:0 2px 6px 0 black;
			}
			
			.choisir {
				position:fixed;
				left:300px;
				top:300px;
			}
		</style>

		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
		<script src="jquery.atd.js"></script>
		<script src="jquery.atd.textarea.js"></script>
		<script src="csshttprequest.js"></script>
		<script type="text/javascript">
		var liens = [<?php 
		$text = file('lien');
		shuffle($text);
		foreach($text as $line) {
			echo "'" . rtrim($line) . "',";
		}
		echo "];";
		?>
		var cliques = [];
		var strTemp = "Entrez le texte original ici.";
		var numlien=0;

		function insertClique() {
			var selection = $("#id_selection").val();
			var regselection = new RegExp(selection, 'gi');
		    var pattern = "{";
		    var clique = getClique();
		    for(var i in clique){
		        pattern+=clique[i]+"|";
		    }
		    pattern=pattern.substr(0,pattern.length-1);
		    pattern+= "}";
		    strTemp = strTemp.replace(regselection,pattern);
		    $("#id_texte").val(strTemp);
		    $("#id_modif").click();
		    $("#id_texte").blur();
		}
		
		function getClique() {
			if(document.resultats.clique.length)
				for(var i=0 ; i<document.resultats.clique.length ; i++) {
				  if (document.resultats.clique[i].checked)
		      	  {
			      	var clique = document.resultats.clique[i].value;
			      	return cliques[clique];
			      }
				}
			else return cliques[0];
		}
		
		function getCliques() {
		    var select = $("#id_selection").val();
		    $.ajax({
		      url: "xhr.php?select="+select,
		      success: function(data){
		 	    match = data.getElementsByTagName("ul")[1];
				cliques = [];
				for (var j=0 ; j<match.getElementsByTagName("li").length ; j++) {
					cliques[j] = [];
					for(var i=0 ; i<match.getElementsByTagName("li")[j].getElementsByTagName("a").length ; i++) {
						cliques[j].push(match.getElementsByTagName("li")[j].getElementsByTagName("a").item(i).innerHTML);
					}
				}
				
				var append = '<form name="resultats">';
				
				for(var a in cliques) {
					var mots = "";

					for(var b in cliques[a]) {
						mots += cliques[a][b] + ", ";
					}

					mots = mots.substr(0, mots.length-2);
					append += '<input type="radio" name="clique" style="float:left;margin:5px" value="'+a+'" />';
					append += '<p>'+mots+'</p>';
				}
				
				append += '</form><br><input type="button" class="choisir" value="choisir" onclick="insertClique()" />';
				$("#id_resultats").html(append).show();
				$("#id_textes").hide();
		      }
		    });
		}
		
		function link() {
			var select = $("#id_selection").val();
			var linked = '<a href="numlien'+numlien+'">'+select+'</a>';
			var regselect = new RegExp(select, 'gi');
			strTemp = strTemp.replace(regselect,linked);
			numlien++;
		    $("#id_texte").val(strTemp);
		    $("#id_modif").click();
		    $("#id_texte").blur();
			
		}
		
		function spin() {
			$.post("xhr.php", { texte: strTemp}, function(data) {
				var resultat = data.split("Outil Content Spinning</h1>");
				data = resultat[1].split("<form method=");
				var output = "<h2>Texte 1</h2>"+data[0];
				$("#id_resultats").hide();
				$("#id_textes").html(output).show();
				var texte = "";
				$("#id_textes textarea").each(function() {
					texte += $(this).text() + " \r\n\r\n";
				});
				for (i=0 ; i<(numlien) ; i++) {
					var link = new RegExp("numlien"+i, 'gi');
					texte = texte.replace(link,liens[i]);
				}
				$("#id_textes").html('<div style="width:500px;height:600px;margin:0 auto;" id="tout">'+texte+'</div');
				AtD.checkCrossAJAX("tout",
				{
					success : function(errorCount) 
			       {
			          if (errorCount == 0)
			          {
			             alert("No writing errors were found");
			          }
			          $("#id_textes").prepend('<textarea style="width:500px;height:600px;display:block;margin:0 auto">'+texte+'</textarea>');
			       },

			       error : function(reason)
			       {
			          alert(reason);
			       }
				});
			});
		}

		function addURL() {
			var url = $("#id_url").val();	
			$.ajax({
		      		url: "url.php?select="+url,
			      	success: function(data){
				$("#id_url").val(data);
			      	}
		    	});
		}
		
		
		</script>
	</head>
	<body>
	
		<script>
	$(document).ready(function() {
		AtD.rpc_css_lang = "fr";
        $("#onBlur").hide();
        $("#id_texte").val(strTemp).blur(function() {
        	if($(this).val()=="")
        		$(this).val("Entrez le texte original ici.");
            $("#id_texte").hide();
            $("#id_blur").hide();
            $("#onBlur").show();
            strTemp = $("#id_texte").val();
            $("#id_switch").text(strTemp);

            var words = $("#id_switch").text().split(/(\,|\.| )+/);
            $("#id_switch").html("");

            $.each(words, function(i, val) {
                //wrap each word in a span tag
                $('<span style="cursor:pointer">'+val+'</span> ').appendTo("#id_switch");
            });

            $("#id_switch span").live("mouseover", function() {
                $(this).css("text-decoration", "underline");
            });

            $("#id_switch span").live("mouseout", function() {
                    $(this).css("text-decoration", "none");
            });

            $("#id_switch span").live("click", function(event) {
                event.stopPropagation();
                var text = $(this).text();
                $("#id_selection").val(text);
            });
            
            $("#id_modif").live("click", function() {
                $("#id_texte").show();
                $("#id_blur").show();
                $("#onBlur").hide();
            });
        }).focus(function() {
        	if ($(this).val() == "Entrez le texte original ici.")
        		$(this).val("");
        });
    });	
		</script>
		
		<div id="wrapper">
			<p style="font: 30pt Inconsolata, Helvetica, Arial;text-align: center;text-shadow: 0 -1px steelblue;color: lightblue;">MNT Fatal Spinner v0.1</p>
			<div id="id_linker">	
				<input type="text" id="id_url" value="Ajouter l'URL de votre dernier post" style="width:500px;margin:0 auto;"></textarea>
	   			<input type="button" value="Add" onclick="addURL()" style="float:right;" />
			</div>
			<br>
			<textarea id="id_texte"></textarea>
			<br>
   			<input type="button" value="OK" onclick="$('#id_texte').blur()" id="id_blur" style="display:block;margin:0 auto;" />
   			<br>
   			
			<div id="onBlur" style="width:500px;margin:0 auto;">
			    <p id="id_switch"></p>
		    	<input id="id_modif" value="modifier" type="button" style="float:left"/>
	   			<input type="button" value="générer" onclick="spin()" style="float:right" />
	   			<br>
	   			<div style="clear:both">
		    		<input id="id_selection" type="text" />
    				<input type="button" value="cliques" onclick="getCliques()" />
    				<input type="button" value="link" onclick="link()" />
    				<div id="id_resultats">
	    			</div>
				</div>
			</div>
			<div id="id_textes">
			</div>
   		</div>
	</body>
</html>
