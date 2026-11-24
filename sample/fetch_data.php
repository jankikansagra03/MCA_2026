<?php

include_once('db_config.php');
$q = "select *  from `registration`";
$users = mysqli_query($con, $q);
?>
<table border="1" cellspacing="0" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Password</th>
        <th>Mobile</th>
        <th>Profile Picture</th>
        <th>Actions</th>
    </tr>

    <?php
    while ($result = mysqli_fetch_assoc($users)) {
    ?>
        <tr>
            <td><?php echo $result['id']; ?></td>
            <td><?php echo $result['fullname']; ?></td>
            <td><?php echo $result['email']; ?></td>
            <td><?php echo $result['password']; ?></td>
            <td><?php echo $result['mobile']; ?></td>
            <td><img src="uploads/profile_pictures/<?php echo $result['profile_picture']; ?>" alt="Profile Picture" width="100"></td>
            <td>
                <a href="view.php?id=<?php echo $result['id']; ?>">View</a>
                <a href="edit.php?uid=<?php echo $result['id']; ?>">Edit</a>
                <a href="delete.php?id=<?php echo $result['id']; ?>"> Delete</a>
            </td>
        </tr>
    <?php
    }
    ?>
</table>