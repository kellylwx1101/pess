<!DOCTYPE HTML>
<html>
<head>
<title>Log Call</title>
<script>
	function validateForm()  
	{
		var x = document.forms["frmLogCall"]["callerName"].value;
		if (x == "")
		{ 
			alert("Name must be filled out");
			return false;
		}
	}
</script>
<?php 
	include "head.php";

	if(isset($_POST['btnProcessCall']))
	{
		$con = mysql_connect("localhost", "kellylwx11_", "Password123_1101");
		if(!$con)
			{
			die("Cannot connect to database:".mysql_error());
			}
			
		mysql_select_db("13_kelly_pessdb", $con);
		
		$sql = "INSERT INTO incident(callerName, phoneNumber, incidentTypeId, incidentLocation, incidentDesc, incidentStatusid) VALUES('$_POST[callerName]','$_POST[contactNumber]','$_POST[incidentType]','$_POST[location]','$_POST[incidentTypeDesc]')";
		//echo $sql; 
		if(!mysql_query($sql, $con))
			die("Error: " . mysql_error());
		mysql_close($con);
	}
?>
</head>
<body>
	<?php
		$con = mysql_connect("localhost", "kellylwx11_", "Password123_1101");
		if(!$con)
			die("Cannot connect to database:".mysql_error());
		
		mysql_select_db("13_kelly_pessdb", $con);
		
		$result = mysql_query("SELECT * FROM incidenttype");
		$incidentType;
		
		while($row = mysql_fetch_array($result))
			//$incidentTypeid, $incidentDesc 
			$incidentType[$row['incidentTypeid']] = $row['incidentTypeDesc'];
			
mysql_close($con);
?>

</br>
	<form name="frmlogcall" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
		<fieldset>
			<legend>Log Call</legend>
			<table>
				<tr>
					<td align="right">Caller's Name:</td>
					<td><p><input type="text" name="callerName"/></p></td>
				</tr>
			
				<tr>
					<td align="right">Contact Number:</td>
					<td><p><input type="text" name="contactNumber"/></p></td>
				</tr>
			
				<tr>
					<td align="right">Location:</td>
					<td><p><input type="text" name="location"></p></td>
				</tr>
				
				<tr>
					<td align="right" class="td_label">Incident Type:</td>
					<td class="td_Date">
						<p>
						<select name="incidentType" id="incidentType">
							<?php foreach ($incidentType as $key => $value){?>
								<option value="<?php echo $key ?>"><?php echo $value ?></option>
							<?php }?>
						</select>
						</p>
					</td>
				</tr>
				<tr><tr>
				<tr>
					<td align="right">Description:</td>
					<td><p><textarea name="incidentTypeDesc" rows="4" cols="50" name="incidentDesc"></textarea></p>
				</tr>
				<tr>
					<td align="right"><input type="reset" /></td>
					<td><input type="submit" name="btnProcessCall" value="Process Call" /></td>
				</tr>
			</table>
		</fieldset>
	</form>
</html>