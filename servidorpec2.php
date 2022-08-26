<?php
/* Functions */

function print_error($message)
{
   echo "<html>\n";
   echo "<head>\n";
   echo "<title>ConsultaDeGenes</title>\n";
   echo "<link href=\"styles.css\" rel=\"stylesheet\">\n";
   echo "</head>\n";
   echo "<body>\n";
   echo "<h1>Consulta de genes: Error found in the query</h1>\n";
   echo "<table>\n";
   echo "<tr><th>$message</th>\n";
   echo "</table>\n";
   echo "</p>\n";
   echo "</body>\n";
   echo "</html>\n";

   exit();
}
/************************************************************************************************************/
/* Step 0. Gathering the values of the form and preparing the variables */

$organismo = $_POST['organismo'];
$gen = $_POST['gen'];


/* Running time */
$times = getdate();
$date = $times["hours"].":".$times["minutes"].":".$times["seconds"]." --- ".$times["mday"]."-".$times["month"]."-".$times["year"];

/* Control of name of sets */
if (strlen($gen)==0)
{
  print_error("<p>Error: gen name is not defined</p>\n");
}
else
{
  /* Escape special characters */
  $gen= str_replace(" ","_",$gen);
  $gen = str_replace("\\","_",$gen);
}
 /***********************************************************************************************************/

/* Step 1. Connection to the database to perform the queries */

$servername = "localhost";
$username = "sergio";
$password = "123456";
$database = "pec3";


/* Connection to the mysql server and selection of the database */
$db = mysqli_connect($servername,$username,$password) or print_error("The connection to the mysql system is not working");
mysqli_select_db($db,$database) or print_error("The database is not accessible");


	
	if ($organismo == 1)
	{
	$table = "humanEj3";	

	}
	elseif ($organismo ==2)
	{
	$table = "mouseEj3";	

		}
	elseif ($organismo ==3)
	{
	$table = "danioEj3";	

		}
	else
	{
	$table = "melanogasterEj3";
		}
		
/* Query (select) about this gen name */

$fields = "name2,name,chrom,strand,txStart,txEnd,cdsStart,cdsEnd,exonCount,GO_ID,Funcion";
$query = "SELECT $fields FROM $table WHERE name2 LIKE '%$gen%';";

$result = mysqli_query($db,$query);
$items = mysqli_affected_rows($db);  
  
if ($items == 0)
{
   print_error("The gen $gen is not found in the RefSeq database");
}
else
{
   $transcripts = $items;}

/*****************************************************************************************************************************/

/* Step 2. First part of the resulting web page */

   echo "<html>\n";
   echo "<head>\n";
   echo "<title>Consulta de genes</title>\n";
   echo "<link href=\"styles.css\" rel=\"stylesheet\">\n";
   echo "</head>\n";
   echo "<body>\n";
   echo "<h1>Consulta para el gen: $gen</h1>";
   
   	if ($organismo == 1)
	{
	echo "<h1>Humano</h1>\n";	
	}
	elseif ($organismo ==2)
	{
	echo "<h1>Ratón</h1>\n";	
		}
	elseif ($organismo ==3)
	{
	echo "<h1>Pez Zebra</h1>\n";	
		}
	else
	{
	echo "<h1>Melanogaster</h1>\n";	
	}

   echo "<table>\n";
   echo "<tr><th>GEN</th><th>TRANSCRIPT</th><th>CHR</th><th>STRAND</th>";
   echo "<th>TrPOS1</th><th>TrPOS2</th><th>CdsStart</th><th>CdsEnd</th><th>EXONS</th><th>GO_ID</th><th>Funcion</th><th>GO</th><th>UCSC</th>\n";

   for ($i=0;$i<$items;$i++)
   {
	$row = mysqli_fetch_array($result);

        $name2 = $row["name2"];
        $name = $row["name"];
        $chrom = $row["chrom"];
        $strand = $row["strand"];
        $txStart = $row["txStart"];
        $txEnd = $row["txEnd"];
	$cdsStart = $row["cdsStart"];
	$cdsEnd = $row["cdsEnd"];
        $exonCount = $row["exonCount"];
	$GO_ID = $row["GO_ID"];
	$Funcion = $row["Funcion"];

   	echo "<tr><td>$name2</td><td>$name</td><td>$chrom</td><td>$strand</td><td>$txStart</td><td>$txEnd</td><td>$cdsStart</td>";
	echo "<td>$cdsEnd</td><td>$exonCount</td><td>$GO_ID</td><td>$Funcion</td>";
	echo "<td><a href=\"http://amigo.geneontology.org/amigo/search/ontology?q=$GO_ID&searchtype=ontology\">Link</a></td>";
	
	
	if ($organismo == 1)
	{
        echo "</td><td><a href=\"https://genome.ucsc.edu/cgi-bin/hgTracks?db=hg38&position=$chrom:$txStart-$txEnd\">Link</a></td>\n";	
	}
	elseif ($organismo ==2)
	{
	 echo "</td><td><a href=\"https://genome.ucsc.edu/cgi-bin/hgTracks?db=mm10&position=$chrom:$txStart-$txEnd\">Link</a></td>\n";	
		}
	elseif ($organismo ==3)
	{
	 echo "</td><td><a href=\"https://genome.ucsc.edu/cgi-bin/hgTracks?db=danRer10&position=$chrom:$txStart-$txEnd\">Link</a></td>\n";	
		}
	else
	{
	 echo "</td><td><a href=\"https://genome.ucsc.edu/cgi-bin/hgTracks?db=dm6&position=$chrom:$txStart-$txEnd\">Link</a></td>\n";	
		}	
   }
   echo "</table><br><br>\n";


/***********************************************************************************************************/
  
/* Step 3. Show the parameters of the web form */

echo "<h1>Parameters of the query:</h1>\n";
echo "<table>\n";
echo "<tr><th>gen</th><td>$gen</td>\n";
echo "<tr><th>TRANSCRIPTS</th><td>$transcripts</td>\n";
echo "<tr><th>SQL QUERY</th><td>$query</td>\n";
echo "<tr><td><br></td></td><td></td><td></td><td></td>\n";

echo "<tr><th>DATE</th><td colspan=3>$date</td>\n";
echo "</table>\n";
echo "<p><br><br></p>\n";

echo "<p class=\"footer\">\n";
echo "Web server designed and implemented by Sergio Carracedo (2019)<br><br>\n";
echo "<img src=\"./dna.jpg\" height=50>\n";
echo "</p>\n";

echo "</body>\n";
echo "</html>\n";
 ?>                                                                          

	
