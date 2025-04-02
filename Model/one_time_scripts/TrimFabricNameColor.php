<?php

/*** [NOTE]: UPDATE THE DATABASE CONNECTION AS PER REQUIREMENT BEFORE USAGE ***/


// error logging
ERROR_REPORTING(E_ALL);
ini_set('display_errors', 1);


// function to log line space
$logLineSpace = function () {
    if (php_sapi_name() === "cli") echo "\n\n\n";
    else echo "<br><br><br>";
};


// database connection properties object
/* $password = "";
$dbConnectionProperties = [
    "host" => "127.0.0.1",
    "port" => 3306,
    "username" => "root",
    "password" => $password,
    "database" => ""
]; */
$password = "";
$dbConnectionProperties = [
    "host" => "hciordercphp-staging.c2csghnhkj8t.us-east-2.rds.amazonaws.com",
    "port" => 3306,
    "username" => "admin",
    "password" => $password,
    "database" => "hcistage_feb1"
];


// database connection object
$dbConnection = mysqli_connect(
    $dbConnectionProperties['host'],
    $dbConnectionProperties['username'],
    $dbConnectionProperties['password'],
    $dbConnectionProperties['database']
);


echo "Connected to HCI database on host `" . $dbConnectionProperties['host'] . "` successfully";


$logLineSpace();


// fetch records with trailing spaces in fabric_name or color
$trailingSpaceFetchQuery =
    "select * from fabrics where (fabric_name like '% ' or fabric_name like ' %') or (color like '% ' or color like ' %');";


echo "Fetching for Query::\n" . $trailingSpaceFetchQuery;


$logLineSpace();


// fetch records with trailing spaces in fabric_name or color
$trailingSpaceFetchQueryResult = mysqli_query($dbConnection, $trailingSpaceFetchQuery);


// fetch records count
$recordsFetchCount = $trailingSpaceFetchQueryResult->num_rows;


if ($recordsFetchCount == 0) {
    echo "No records found with trailing spaces in fabric_name or color";
    $logLineSpace();
    $dbConnection->close();
    exit(0);
}


echo "Fetched records count:: " . $recordsFetchCount;


$logLineSpace();


echo "Removing trailing spaces with the fabric_name and color for the following records::";


$logLineSpace();


// processing the trailing spaced records for fabric_name or color columns with trim
while ($trailingSpaceFabricRecord = $trailingSpaceFetchQueryResult->fetch_assoc()) {
    $fabricId = $trailingSpaceFabricRecord['id'];
    $fabricName = $trailingSpaceFabricRecord['fabric_name'];
    $fabricColor = $trailingSpaceFabricRecord['color'];

    echo "Fabric ID:: " . $fabricId . "\n";
    echo "Fabric Name:: '" . $fabricName . "'\n";
    echo "Fabric Color:: '" . $fabricColor . "'\n";

    // trimming fabric_name and color
    $fabricName = trim($fabricName);
    $fabricColor = trim($fabricColor);

    // update query with trimmed fabric_name and color
    $updateFabricRecordQuery =
        "update fabrics set fabric_name = '" . $fabricName . "', color = '" . $fabricColor . "' where id = " . $fabricId . ";";

    echo "Update Query::\n" . $updateFabricRecordQuery;

    // update query execution
    $updateFabricRecordQueryResult = mysqli_query($dbConnection, $updateFabricRecordQuery);

    if (isset($updateFabricRecordQueryResult)) {
        echo "\nFabric record updated successfully";
    } else {
        echo "\nFailed to update fabric record";
    }

    $logLineSpace();
}


echo "All records with trailing spaces in fabric_name or color processed successfully and trailing spaces removed";


$dbConnection->close();


exit(0);
