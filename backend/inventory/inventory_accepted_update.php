<?php

if(isset($_POST['sku_name']))
{ //check if form was submitted

	include 'db_config.php';

	$sku_name = $_POST['sku_name'];
	$qc_status = $_POST['qc_status'];
	$material = $_POST['material'];
	$measurements = $_POST['measurements'];
	$size = $_POST['size'];
	$special = $_POST['special'];
	$retail_value = $_POST['retail_value'];
	$suggested_price = $_POST['suggested_price'];
	$upload_status = $_POST['upload_status'];
	
	$query = "UPDATE `inventory` SET qc_status =  '$qc_status', material = '$material', measurements = '$measurements', size ='$size', retail_value = '$retail_value', suggested_price = '$suggested_price', upload_status = '$upload_status', special = '$special' WHERE sku_name = '$sku_name'  ";
	$result = mysql_query($query);
	
	echo mysql_error();
	
	if ($result == 'TRUE')
	{
		echo 'Record Updated Successfully';
	}
	else
	{
		echo 'Record Update Failed';
	}
	

	mysql_close();

	
	
	}

include 'db_config.php';
	
$qc_status = 'accepted'; // So that the page refreshes with accepted items again
$inventory_data_status = $_POST['inventory_data_status'];
$sort_by = $_POST['sort_by'];

if ($qc_status == 'accepted' && $inventory_data_status == 'Not complete')
{
	//$query = "SELECT * FROM `inventory` WHERE qc_status = 'accepted'  AND ( material ='' OR measurements ='' OR size = '' OR retail_value ='' OR suggested_price ='' OR upload_status ='') " ;
	$query = "SELECT * FROM `inventory` WHERE qc_status = 'accepted'  AND ( measurements ='' OR size = '' OR retail_value ='' OR suggested_price ='' OR upload_status ='') " ;
	
}

else if ($qc_status == 'accepted' && $inventory_data_status == 'Complete')
{
	//$query = "SELECT * FROM `inventory` WHERE qc_status = 'accepted' AND ( material != '' AND measurements != '' AND size != '' AND retail_value != '' AND suggested_price != '' AND upload_status != '' ) " ;
	$query = "SELECT * FROM `inventory` WHERE qc_status = 'accepted' AND ( measurements != '' AND size != '' AND retail_value != '' AND suggested_price != '' AND upload_status != '' ) " ;
	
}

if($sort_by)
{
	$query .= "ORDER BY ".$sort_by;
}
$result = mysql_query($query);


$numresult = mysql_numrows($result);

if ( $numresult > 0 )
{
	
	?>
	<head>
		<script src="jquery-1.11.1.js"></script>
		<script src="FileSaver.js"></script>
	<head>
	
	<body>
	<div id ="inventory_table">
		<h1>Inventory Details</h1>
		
		<button id="btnExport" onclick="fnExcelReport();" > Export To Excel </button>
		
		<table id="report_table">
			<th>S. No</th>
			<th>SKU Code</th>
			<th>Cust. Email ID</th>
			<th>Name</th>
			<th>
				<form action = "inventory_accepted_update.php" method = "POST">
					<input type = "text" value = 'Brand'  name = "sort_by" readonly ="true"> 
					<input type = "text" value = '<?php echo $inventory_data_status ?>' name = "inventory_data_status" hidden ="true">
					<input type = "Submit" value = "Sort!">
				</form>
			</th>
			<th>Color</th>
			<th>QC Status </th>
			<th>Condition </th>
			<th>Gently Used comments </th>
			<th>Material</th>
			<th>Measurements</th>
			<th>Size</th>
			<th>Special</th>
			<th>Retail Value</th>
			<th>Suggested Price</th>
			<th>Upload Status</th>
			<th hidden = "true">QC Status</th>
			<th hidden = "true">Inventory Data Status</th>
	<?php
	$i = 0;
	while ( $i < $numresult )
	{
			$sku_name = mysql_result($result,$i,'sku_name');
			$customer_email_id = mysql_result($result,$i,'customer_email_id');
			$product_name= mysql_result($result,$i,'product_name');
			$brand = mysql_result($result,$i,'brand');
			$color= mysql_result($result,$i,'color');
			$material = mysql_result($result,$i,'material');
			$measurements = mysql_result($result,$i,'measurements');
			$condition = mysql_result($result,$i,'condition');
			$gently_used_comments = mysql_result($result,$i,'gently_used_comments');
			$size = mysql_result($result,$i,'size');
			$special = mysql_result($result,$i,'special');
			$retail_value = mysql_result($result,$i,'retail_value');
			$suggested_price = mysql_result($result,$i,'suggested_price');
			$upload_status = mysql_result($result,$i,'upload_status');
		
		?>
		
		

		<tr>
			<td> <?php echo $i+1 ?></td>
			<form action = "inventory_accepted_update.php" method = "POST" target="_blank">
			<td><textarea name="sku_name" style ="width:50px;" readonly = "true"><?php echo $sku_name; ?></textarea></td>
			<td><?php echo $customer_email_id ;?></td>
			<td ><?php echo $product_name;?></td>
			<td><?php echo $brand; ?></td>
			<td><?php echo $color; ?></td>
			<td>
			<?php
					$get = mysql_query("SELECT status FROM qc_status where 1");
					$option = '';
					
					while($row = mysql_fetch_assoc($get))
					{
						if($row['status'] != $qc_status)	
							$option .= '<option value = "'.$row['status'].'">'.$row['status'].'</option>';
						else
								$option .= '<option value = "'.$row['status'].'" selected = "selected">'.$row['status'].'</option>';
					}
				?>
				<select name ="qc_status">
					<?php echo $option ?>
				</select>
			<td><?php echo $condition; ?></td>
			<td><?php echo $gently_used_comments; ?></td>			
			<td><textarea name = "material"><?php echo $material?></textarea></td>
 			<td><textarea name ="measurements"><?php echo $measurements?></textarea></td>
			<td>
				<?php
					$get = mysql_query("SELECT type FROM size where 1");
					if( !$size )
						$option = '<option value="" disabled="disabled" selected="selected">Select Size</option>';
					else
						$option = '<option value="" disabled="disabled">Select Size</option>';
					
					while($row = mysql_fetch_assoc($get))
					{
						if($row['type'] != $size)	
							$option .= '<option value = "'.$row['type'].'">'.$row['type'].'</option>';
						else
								$option .= '<option value = "'.$row['type'].'" selected = "selected">'.$row['type'].'</option>';
					}
				?>
				<select name ="size">
					<?php echo $option ?>
				</select>
			</td>
			<td><textarea name = "special"><?php echo $special?></textarea></td>
			<td><input type = "number" value = <?php echo $retail_value ?> name= "retail_value" id = "retail_value" onkeypress='validate(event)' onchange = "FillSuggPrice('<?php echo $sku_name?>');">	</td>
			<td><input type = "number" value = <?php echo $suggested_price ?> name = "suggested_price" id ="suggested_price".'<?php echo $sku_name?>' onkeypress='validate(event)'>	</td> 
			<td><input type = "text" value = '<?php echo $upload_status ?>' name = "upload_status"> </td>
			<!----------------------Dummy values so that on form submit, list with accepted and incomplete data items are loaded--------->
			
			<td hidden ="true"><input type = "text" value = '<?php echo $inventory_data_status ?>' name = "inventory_data_status"> </td>
			
			<td><input type = "Submit" value = "Update!"></td>
			</form>
		</tr>
			
			
	<?php
	$i++;
	}
	mysql_close();
?>
	</table>
	</div>
	</body>

<?php
}
else
{
	echo "No results found";

}

mysql_close();
?>
<style>

body
{
	background-color: #F9FFFB;
}

#seller_pickup_id
{
	width:1%;
	text-align:center;
	
}

h1
{
	background-color: #E3E0FA;
	text-align:center;
	width:40%;
	margin-left:auto;
	margin-right:auto;
	margin-bottom:2em;
	font-family: 'Century Schoolbook';
		

}

table
	{
		margin-left: auto;
		margin-right: auto;
		font-color: #d3d3d3;
		background-color: #ADD8E6;
	
	}
	
th
	{
		background-color: #C0C0C0;
		font-family: 'Georgia';
		font-size:1.2em;
	}
	
td
	{
		text-align : center;
	}
	
	
tr:nth-child(odd)
	{
			background-color: #EAF1FB;
			font-size:1.1em;
	}

tr:nth-child(even)
	{
			background-color: #CEDEF4;
			font-size:1.1em;
	}

tr.highlight   
	{    
		background-color: #063774;   
		color: White;   
	}  

textarea
	{
		height:120px;
	}
	
#btnExport
	{
		display:block;
		margin-left: auto;
		margin-right: auto;
		margin-bottom:2em;
		height: 35px;
		color: white;
		border-radius: 10px;
		text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
		background: rgb(202, 60, 60); /* this is maroon */
	}	

input
{
		width:80%;
	
	
}
</style>

<script>

function fnExcelReport()
{
	var tab_text="<table><tr>";
    var textRange;
    tab = document.getElementById('report_table'); // id of actual table on your page

	console.log(tab.rows.length);
    for(j = 0 ; j < tab.rows.length ; j++) 
    {   
        tab_text=tab_text+tab.rows[j].innerHTML;
        tab_text=tab_text+"</tr><tr>";
    }

    tab_text = tab_text+"</tr></table>";

	var txt = new Blob([tab_text], {type: "text/plain;charset=utf-8"});
	saveAs(txt,"Inventory_Accepted_Report.xls");
}

/********************* Validate numeric entry in form field*********************/
function validate(evt) 
{
	var theEvent = evt || window.event;
	var key = theEvent.keyCode || theEvent.which;
	key = String.fromCharCode( key );
	var regex = /[0-9]|\./;
	if( !regex.test(key) ) 
	{
		theEvent.returnValue = false;
		if(theEvent.preventDefault) theEvent.preventDefault();
	}
}

/********************* Suggested price autofill based on retail value*********************/
function FillSuggPrice(value) 
{
	/*
    var retail_price = document.getElementById('retail_value');
	console.log(value);
    document.getElementById('suggested_price').value=0.3 * retail_price.value;
      */  
}


</script>