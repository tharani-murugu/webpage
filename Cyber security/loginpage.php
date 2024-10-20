<?php
$uname = $_POST['uname'];
$pword = $_POST['pword'];

if (!empty($uname) && !empty($pword)) {
    $host = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "project";

    $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

    if ($conn->connect_error) {
        die('Connection Error: ' . $conn->connect_error);
    }

    // Select the username and password from the register table to verify login
    $SELECT = "SELECT username, password1 FROM register WHERE username = ? AND password1 = ? LIMIT 1";

    // Prepare and bind the query
    $stmt = $conn->prepare($SELECT);
    $stmt->bind_param("ss", $uname, $pword);
    $stmt->execute();
    $stmt->bind_result($db_uname, $db_pword);
    $stmt->store_result();
    $rnum = $stmt->num_rows;

    // If the user exists in the register table
    if ($rnum > 0) {
        // Fetch the username and password
        $stmt->fetch();

        // Insert into newlogin table if the user is successfully verified
        $INSERT = "INSERT INTO newlogin (uname, pword) VALUES (?, ?)";
        $stmt->close(); // Close the previous statement

        $stmt = $conn->prepare($INSERT);
        $stmt->bind_param("ss", $uname, $pword);
        $stmt->execute();

        header('Location: home.html'); // Redirect to the home page after successful login
    } else {
        // If the credentials do not match
        echo "<script>
                alert('Invalid username or password');
                window.location.href = 'loginpage.html';
              </script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "All fields are required.";
}
?>
