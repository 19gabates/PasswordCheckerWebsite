<?php

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the submitted password
    $submittedPassword = $_POST["password"];

    // Check if the password is in the rockyou.txt file
    $passwordFile = fopen("rockyou.txt", "r");
    $passwordFound = false;

    while (!feof($passwordFile)) {
        $line = trim(fgets($passwordFile));
        if ($line === $submittedPassword) {
            $passwordFound = true;
            break;
        }
    }

    fclose($passwordFile);

    // Display the result
    if ($passwordFound) {
        echo '<p class="text-danger">Password is in the rockyou.txt file. It is deemed instantly crackable. See below for more details.</p>';
    } else {
        // If the password is not in rockyou.txt, perform time estimation
        $password = $submittedPassword;

        // Adjust these values based on your own estimations and assumptions
        $hashing_iterations = 10000; // Number of hashing iterations (e.g., for bcrypt)
        $hashing_time = 0.0001; // Time to hash a password in seconds (e.g., for bcrypt)

        // Assuming a simple brute-force attack with 1,000,000 guesses per second
        $guesses_per_second = 1000000;

        // Calculate the total time to crack the password
        $total_time = $hashing_iterations * $hashing_time * pow(10, strlen($password)) / (2 * $guesses_per_second);

        // Convert the total time to a human-readable format
        $time_estimate = formatTimeEstimate($total_time);

        echo "<p class='text-success'>Password is not in the rockyou.txt file.</p>";
        echo "<p>Estimated time to crack the password: $time_estimate</p>";
    }
}

function formatTimeEstimate($seconds) {
    $units = array('year' => 31536000, 'month' => 2592000, 'day' => 86400, 'hour' => 3600, 'minute' => 60, 'second' => 1);

    foreach ($units as $name => $divisor) {
        if ($quotient = intval($seconds / $divisor)) {
            $plural = $quotient > 1 ? 's' : '';
            return "$quotient $name$plural";
        }
    }

    return 'less than a second';
}

?>