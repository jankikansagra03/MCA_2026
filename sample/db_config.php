<?php

$con = mysqli_connect("localhost", "root", "");

try {
    if ($con) {
        // echo "Connection Successful";
    } else {
        throw new Exception("Connection Failed");
    }
} catch (Exception $e) {
    echo $e->getMessage();
}

// try {
//     $create_db = "create database MCA_2026";
//     if (mysqli_query($con, $create_db)) {
//         echo "Database Created";
//     } else {
//         throw new Exception("Database Not Created");
//     }
// } catch (Exception $e) {
//     echo $e->getMessage();
// }

try {
    if (mysqli_select_db($con, "MCA_2026")) {
        // echo "Database Selected";
    } else {
        throw new Exception("Database Not Selected");
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
// try {
//     $create_reg = "create table registration(
//         id int Primary Key auto_increment,
//         fullname char(50),
//         email varchar(30),
//         mobile bigint(10),
//         password varchar(25),
//         profile_picture varchar(100)
//     )";
//     if (mysqli_query($con, $create_reg)) {
//         echo "Table Created";
//     } else {
//         throw new Exception("Table Not Created");
//     }
// } catch (Exception $e) {
//     echo $e->getMessage();
// }
// 

// $create_reg = "create table registration(
// id int Primary Key auto_increment,
// fullname char(50),
// email varchar(30),
// mobile bigint(10),
// password varchar(25),
// profile_picture varchar(100)
// )";


// try {
//     $insert_reg =  "INSERT INTO `registration`(`fullname`, `email`, `mobile`, `password`, `profile_picture`) VALUES ('abc','abc@gmail.com',1234123412,'Abc@12345','abc.png')";

//     if (mysqli_query($con, $insert_reg)) {
//         echo "Data Inserted";
//     } else {
//         throw new Exception("Data Not Inserted");
//     }
// } catch (Exception $e) {
//     echo $e->getMessage();
// }   
