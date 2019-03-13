<?php
	if(isset($_POST["btnProcessCall"])) && !isset($_POST['btnDispatch'])
		header("Location: head.php");
?>
	
<?php
	if(isset($_POST["btnSubmit"]))
	{
		//connect to database
		$con = mysql_connect("localhost", "kellylwx11_", "Password123_1101");
	}
	
	if(!$con);
	{
		die('Cannot connect to database:'.mysql_error());
	}
	
	mysql_select_db("13_kelly_pessdb", $con);
	
	// update patrolcar status table and dispatch table
	$patrolcarDispatched = $_POST["chkPatrolcar"];

	$c = count($patrolcarDispatched);

	// insert new incident
	$status;
	if($c > 0)
		$status = "2";
	else
		$status = "1";

	$sql = "INSERT INTO incident(callerName, phoneNumber, incindentTypeId, incidentLocation, incidentDesc, incidentStatusid) VALUES('$_POST[callerName]','$_POST[contactNumber]','$_POST[incidentType]','$_POST[location]','$_POST[incidentTypeDesc]')";
?>
	
<?php
if(!isset($_POST['btnProcessCall']) && !isset($_POST['btnSubmit']))
	header("Location: head.php");
?>

<!DOCTYPE HTML>
<html>
<head>
<title>Dispatch</title>
</head>

<body>
<?php
/* Search and retrieve similar pending incidents and populate a table */

//connect to a database
$con = mysql_connect("localhost", "kellylwx11_", "Password123_1101");
if(!$con);
{
	die('Cannot connect to database:'.mysql_error());
}

//select a table in the database
mysql_select_db("13_kelly_pessdb", $con);

$sql = "SELECT patrolCarID, statusDesc FROM patrolcar JOIN patrolcar_status On patrolcar.patrolcarStatusID = patrolcar_status.statusID WHERE patrolcar.patrolcarStatusID = '2' OR patrolcar.patrolcarStatusID = '3'";
$result = mysql_query($sql, $con);

$incidentArray; 
$count = 0;

while($row = mysql_fetch_array($result))
{
	$patrolcarArray[$count] = $row;
	$c = $count++;
}

if(!mysql_query($sql, $con))
{
	die('Error1: '.mysql_error());
}

mysql_close($con);
?>

<?php
//insert new incident
$status;
//if($c > 0)
	{
	$status = '2';
	}
//else{
	$status = '1';
		
$sql = "INSERT INTO incident(callerName, phoneNumber, incidentTypeid, incidentLocation, incidentDesc, incidentStatusid) VALUES(".$_POST['callerName']."','".$_POST['phoneNumber']."','".$_POST[incidentTypeid]."','".$_POST['incidentLocation']."','".$_POST['incidentDesc']."','$status')";

//if(!mysql_query($sql, $con))
{
	die('Error1:' .mysql_error());
}
//retrieve new incremental key for incidentId
$incidentId = mysql_insert_id($con);;

for($i=0; $i<$c; $i++)
{
	$sql = "UPDATE patrolcar SET patrolcarStatusID='1' WHERE patrolcarId='$patrolcarDispatched[$i]'";
	
	if(!sql_query($sql, $con))
	{
		die('Error2:' .mysql_error());
	}
	
	$sql = "INSERT INTO dispatch(incidentID, patrolCarID, timeDispatched) VALUES('$incidentId', '$patrolcarDispatched[$i]',NOW())";
	
		if(!mysql_query($sql, $con))
			die('Error3:' .mysql_error());
	
		mysql_close($con);
	?>

	<form name="frmdispatch" method="POST" action="dispatch.php"/>
	<table>
		<tr>
			<td>Caller Name:</td>
			<td>
				<?php echo $_POST['callerName']; ?>
				<input type="hidden" name="callerName" value="<?php $_POST('callerName'); ?>"
			</td>
		</tr>
		<tr>
			<td>Phone Number:</td>
			<td>
				<?php echo $_POST['phoneNumber']; ?>
				<input type="hidden" name="phoneNumber" value="<?php $_POST('phoneNumber'); ?>" 
			</td>
		</tr>
		<tr>
			<td>Incident Location:</td>
			<td>
				<?php echo $_POST['incidentLocation']; ?>
				<input type="hidden" name="incidentLocation" value="<?php $_POST('incidentLocation'); ?>"
			</td>
		</tr>
		<tr>
			<td>Incident Type:</td>
			<td>
				<?php echo $_POST['incidentType']; ?>
				<input type="hidden" name="incidentType" value="<?php $_POST('incidentType'); ?>
			</td>
		</tr>
		<tr>
			<td>Description:</td>
			<td>
				<?php echo $_POST['incidentDesc']; ?>
				<input type="hidden" name="incidentDesc" value="<?php $_POST['incidentDesc'];
			</td>
		</tr>
	</table>
	</form>
	
	<table width="40%" border="1" align="center" cellpadding="4" cellspacing="8">
		<tr>
			<td width="20%">&nbsp;</td>
			<td width="51%">Patrol Car ID</td>
			<td width="29%">Status</td>
		</tr>
		
		<?php 
			$i = 0;
			while($i < $count) {
		?>
		<tr>
			<td class="td_label"><input type="checkbox" name="chKPatrolcar[]" value="<?php 
			<td><?php echo $patrolcarArray[$i]['patrolcarId']?></td>
			<td><?php echo $patrolcarArray[$i]['statusDesc']?></td>
		</tr>
			<?php $i++;	) ?>
	</table>
	
	<table width
		<tr>
			<td width="46%" class="td_label" align="right"><input type="reset" name="btnReset);
			<td width="54%" class="td_Data">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input
		</tr>
	</table>
	</form>
</body>
</html>