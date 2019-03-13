<?php

if(!isset($_POST["btnSearch"])){
	
?>

<!--Create a form to search for patrol cars based on id -->

<form name="form1" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>">
	<tr>
		<td width="25%" class="td_label">ID:</td>
		<td width="25%" class="td_Data"><?php echo $_POST["patrolCar"]?>
		<input type="hidden" name="patrolCarId" id="patolCarId" value="<?php echo $_POST["patrrolCarId"}?>">
		</td>
	</tr>
</form>

<?php if(isset($_POST["btnUpdate"])){
	
	//retrieve patrol car status and patrolcarstatus
	//connect to a database
	$con = mysql_connect("localhost", "kellylwx11_", "Password123_1101");
	
	if(!$con)
	{
		die('Cannot connect to database : '.mysql_error());
	}
	
	//select a table in the database
	mysql_select_db("13_kelly_pessdb",$con);
	
	//update patrol car status
	$sql = "UPDATE patrolCar SET patrolCarStatusId = "".$_POST["patrolCarStatus"]."" WHERE patrolcarId = "" .$_POST["patrolCarId"]."";
	
	if(!mysql_query($sql,$con))
	{
		die('Error4:'.mysql_error());
	}
		
	//if patrol car status is on-site(4) then capture the time of arrival
	if($_POST["patrolCarStatus"]=='4'){
			
	$sql = "UPDATE dispatch SET timeArrived=NOW() WHERE timeArrived is NULL AND patrolcarId = "".$_POST["patrolCarStatus"]."'";
	if(!mysql_query($sql,$con))
	{
		die('Error4:'.mysql_error());
	}
		
	}else if($_POST["patrolCarStatus"]=='3'){ // else if patrol car status is FREE then capture the time of completion 
		
	//First, retrieve the incidentID from dispatch table handeled by that patrol car 
	$sql = "SELECT incidentID FROM dispatch WHERE timeCompleted is NULL AND patrolcarId="".$_POST["patrolCarId"]."";
		
	$result = mysql_query($sql,$con);
	
	$incidentId;
		
	while($row = mysql_fetch_array($result))
	{
		//patrolCarId, patrolCarStatusId
		$incidentId = $row['incidentId'];
	}
		
	//echo $incidentId;
		
	//Now then can update dispatch 
	$sql = "UPDATE dispatch SET timeCompleted=NOW()
	WHERE timeCompleted is NULL AND patrolcarId="".$_POST["patrolCarId"]."";
		
	if(!mysql_query($sql,$con))
	{
		die('Error4:'.mysql_error());
	}
		
	//Last but not least, update incident in incident table to completed (3) all of the patrol cars that attended to it are FREE now 
	$sql="UPDATE incident SET incidentStatusId= '3' WHERE incidentId ='$incidentId' AND incidentId NOT IN (SELECT incidentId FROM dispatch WHERE timeCompleted is NULL)";
		
	if(!mysql_query($sql,$con))
	{
		die('Error5:'.mysql_error());
	}
		
	mysql_close($con);
	?>
		
	<script type="text/javascript">window.location="./logcall.php";</script>
	<?php } ?>
			
	<table width="80%" border="0" align="center" cellpadding="4" cellspacing="4">
	
		<tr>
			<td width="25%" class="td_label">Patrol Car ID:</td>
			<td width="25%" class="td_Data"><input type="text" name="patrolCarId" id="patrolCarId"></td> 
			
			<!-- must validate for no empty entry at the Client side, HOW??? -->
			
			<td class="td_Data"><input type="submit" name="btnSearch" id="btnSearch" value="Search"></td>
		</tr>
	</table>
</form>

<?php 
{else }
	//echo $_POST["patrolCarId"];
	
	//retrieve patrol car status and patrolcarstatus 

	//connect to a database 
	$con = mysql_connect("localhost","kellylwx11_", "Password123_1101");
	if (!$con)
	{
		die('Cannot connect to database : '.mysql_error());
	}
	//select a table in the database
	mysql_select_db("13_kelly_pessdb",$con);
	
	//retrieve patrol zccar status 
	$sql = "SELECT * FROM patrolcar WHERE patrolcarId="".$_POST['patrolCarId']."";
	
	$result = mysql_query($sql,$con);
	
	//patrolcarId
	$patrolCarId;
	
	$patrolCarStatusId;
	
	while($row = mysql_fetch_array($result))
	{
		//patrolcarId, patrolCarStatusId
		$patrolCarId = $row['patrolcarId'];
		$patrolCarStatusId = $row['patrolcarStatusId'];
	}
	
	//retrieve patrolcarstatus master table 
	$sql = "SELECT * FROM patrolcar_status";
	
	$result = mysql_query($sql,$con);
	
	$patrolCarStatusMaster;
	
	while($row = mysql_fetch_array($result))
	{
		//statusId,statusDesc
		$patrolCarStatusId, $patrolCarStatusDesc
		
		//create an associative array of patrol car status master type 
		$patrolCarStatusMaster[$row['statusId']]=$row['statusDesc'];
	}
	
	mysql_close($con);
?>
	<!--display a form to update patrol car status also update incident status when patrol car status is FREE --> 
	<form name="form2" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>">
		<table width="80%" border="0" align="center" cellpadding="4" cellspacing="4">
			<tr>
				<td width="25%" class="td_label">Patrol Car ID:</td>
				<td width="25%" class="td_Data"><?php echo $_POST["patrolCarId"]?></td>
			</tr>
			<tr>
				<td class="td_label">Status:</td>
				<td class="td_Data"><select name="patrolCarStatus" id="$patrolCarStatus"></select></td>
			</tr>
			
			<?php foreach($patrolCarStatusMaster as $key => $value){?>
				<option value="<?php echo $key ?>"</option>
				<?php if($key==$patrolCarStatusId) {?>selected="selected" 
				<?php }?>
				<?php echo $value ?>
			?>
			
		</table>
			
		<br />
		
		<table width="80%"border="0" align="center" cellpadding="4" cellspacing="4">
		
			<tr>
				<td width="46%" class="td_label"><input type="reset" name="btnCancel" id="btnCancel" value="Reset"></td>
				<td width="54%" class="td_Data">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="btnUpdate" id="btnUpdate" value="Update"></td>
			</tr>
		</table>
	</form>
<?php } ?>	
		
	<table width="80%" border="0" align="center" cellpadding="4" cellspacing="4">
	
		<tr>
			<td width="25%" class="td_label">Patrol Car ID:</td>
			
			<td width="25%" class="td_Data"><input type="text" name="patrolCarId" id="patrolCarId"></td>
			
			<!-- must validate for no empty entry at the Client side, HOW??? -->
			
			<td class="td_Data"><input type="submit" name="btnSearch" id="btnSearch" value="Search"></td>
			
		</tr>
		
	</table>
	
</form>

<?php
}else{
	//echo $_POST["patrrolCarId"];
	//retrieve patrol car status and patrolcarstatus 
	//connect to a database
	$con = mysql_connect("localhost","kellylwx11_", "Password123_1101");
	if(!$con)
	{
		die('Cannot connect to database:'.mysql_error()):
	}
	//select a table in the databse 
	mysql_select_db("13_kelly_pessdb",$con);
	//retrieve patrol car sstatus 
	$sql = "SELECT * FROM patrolcar WHERE patrrolCarId = "".$_POST['patrrolCarId']."";
	
	$result = mysql_query($sql,$con);
	
	$patrolCarId;
	
	$patrolCarStatusId;
	
	wwhiel($row = mysql_fetch_array($result))
	{
		//patrolCarId,patrolCarStatusId
		$patrolCarId = $row['patrolCarId'];
		$patrolCarStatusId = $row['patrolCarStatusId'];
	}
	
	//retrieve patrolcarstatus master table 
	$sql = "SELECT * FROM patrolcar_status";
	
	$result = mysql_query($sql,$con);
	
	$patrolCarStatusMaster;
	
	while($row = mysql_fetch_array($result))
	{
		//statusId, statusDesc
		//creat an associative array of patrol car status master tyep 
		$patrolCarStatusMaster[$row['statusId']] = $row['statusDesc'];
	}
	
	mysql_close($con);
?>

<!-- display a form to update patrol car status also update incident status when patrol car status is FREE -->
<form name = "form2" method="post" action="<?php echo htmlentities($_SERVER['PHP SELF'];?>">

	<table width="80%" border="0" align="center" cellpadding="4" cellspaccing="4">
	
		<tr>
			<td width="25%" class="td_label">Patrol Car ID:</td>
			<td width="25%" class="td_Data"><?php echo $_POST["patrolCarId"]?></td>
		</tr>
		
		<tr>
			<td class="td_label">Status:</td>
			<td class="td_Data"><select name="patrolCarStatusId = "$patrolCarStatus"></select></td>
			
			<?php foreach($patrolCarStatusMaster as $key => $value){?>
			
				<option value="<?php echo $key ?>"</option>
				<?php if($key==$patrolCarStatusId) {?>selected="sected"
				<?php }?>
				<?php echo $value ?>
			<?php } ?>
		</tr>
	</table>
	<br/>
	<table width="80%" border="0" align="center" cellpadding="4" cellspacing="4">
	
		<tr>
			<td width="46%" class="td_label"><input type="reset" name="btnCancel" id="btnCancel" id="btnCancel" value="Reset"></td>
			<td width="54%" class="td_Data">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="btnUpdate" id="btnUpdate" value="Update"></td>
		</tr>
	</table>
</form>
<?php } ?>
