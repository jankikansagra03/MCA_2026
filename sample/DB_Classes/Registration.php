<?php
require_once 'db_config_oop.php';

class Registration
{
    public function insertUser(string $fullname, string $email, int $mobile, string $password, string $profile_picture)
    {
        // insert query
        $insert_query = "INSERT INTO registration (fullname, email, mobile, password, profile_picture) VALUES ($fullname, $email, $mobile, $password, $profile_picture)";

        if (mysqli_query($GLOBALS['con'], $insert_query)) {
            return true;
        } else {
            throw new Exception("Error inserting user: " . mysqli_error($GLOBALS['con']));
        }
    }

    public function getUserByColumn(string $column, string $value)
    {
        $select_query = "SELECT * FROM registration WHERE $column = '$value'";
        $result = mysqli_query($GLOBALS['con'], $select_query);

        if ($result) {
            return mysqli_fetch_assoc($result);
        } else {
            throw new Exception("Error fetching user: " . mysqli_error($GLOBALS['con']));
        }
    }


    public function updateUser(int $id, string $fullname, string $email, int $mobile, string $password, string $profile_picture)
    {
        $update_query = "UPDATE registration SET fullname = '$fullname', email = '$email', mobile = '$mobile', password = '$password', profile_picture = '$profile_picture' WHERE id = $id";

        if (mysqli_query($GLOBALS['con'], $update_query)) {
            echo "User updated successfully.";
        } else {
            throw new Exception("Error updating user: " . mysqli_error($GLOBALS['con']));
        }
    }

    public function deleteUser(int $id)
    {
        $delete_query = "DELETE FROM registration WHERE id = $id";

        if (mysqli_query($GLOBALS['con'], $delete_query)) {
            echo "User deleted successfully.";
        } else {
            throw new Exception("Error deleting user: " . mysqli_error($GLOBALS['con']));
        }
    }

    public function getAllUsers()
    {
        $select_query = "SELECT * FROM registration";
        $result = mysqli_query($GLOBALS['con'], $select_query);

        if ($result) {
            return mysqli_fetch_all($result, MYSQLI_ASSOC);
        } else {
            throw new Exception("Error fetching users: " . mysqli_error($GLOBALS['con']));
        }
    }

    public function getUserById(int $id)
    {
        $select_query = "SELECT * FROM registration WHERE id = $id";
        $result = mysqli_query($GLOBALS['con'], $select_query);

        if ($result) {
            return mysqli_fetch_assoc($result);
        } else {
            throw new Exception("Error fetching user: " . mysqli_error($GLOBALS['con']));
        }
    }

    public function getUserByEmail(string $email)
    {
        $select_query = "SELECT * FROM registration WHERE email = '$email'";
        $result = mysqli_query($GLOBALS['con'], $select_query);

        if ($result) {
            return mysqli_fetch_assoc($result);
        } else {
            throw new Exception("Error fetching user: " . mysqli_error($GLOBALS['con']));
        }
    }

    public function otherQuery($query)
    {
        $result = mysqli_query($GLOBALS['con'], $query);

        if ($result) {
            return $result;
        } else {
            throw new Exception("Error executing query: " . mysqli_error($GLOBALS['con']));
        }
    }
}
