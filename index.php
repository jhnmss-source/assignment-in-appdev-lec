<?php
// 1. Start the session
session_start();

// --- INITIALIZATION ---
$feedback_message = '';
$username_input = '';

// Check logged-in status
$is_logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;


// --- LOGIC FOR LOGIN FORM SUBMISSION ---
if (isset($_POST['login'])) {
    // Sanitize user inputs
    $username_input = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password_input = $_POST['password']; 

    // 1. Concurrent Login Check (New Logic)
    if ($is_logged_in) {
        // Matching the image/video requirement: Block new login if a session is already active
        $feedback_message = '<p class="message error">A user (' . htmlspecialchars($_SESSION['username']) . ') is already logged in. Please log out first.</p>';
    }
    // 2. Proceed with Login/Hashing if no one is logged in
    elseif (!empty($username_input) && !empty($password_input)) {
        
        // **DYNAMIC HASHING:** Generate a secure hash from the password the user just typed
        $newly_generated_hash = password_hash($password_input, PASSWORD_DEFAULT);
        
        // **Successful "Login":** Set session variables based on the typed input
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username_input; // Store the typed username
        $_SESSION['display_hash'] = $newly_generated_hash; // Store the newly created hash
        
        // Regenerate session ID
        session_regenerate_id(true); 
        
        // Redirect to prevent form resubmission and update display
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;

    } else {
        $feedback_message = '<p class="message error">❌ Please enter both a username and a password.</p>';
    }
}


// --- LOGIC FOR LOGOUT BUTTON ---
if (isset($_POST['logout'])) {
    $_SESSION = array(); // Clear all session data
    // Destroy session cookie if applicable
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    
    // Redirect to the same page to refresh state
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Re-check logged-in status after potential login/logout redirect
$is_logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Hashing Tool v2</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        /* Style adjustments to better match the visual */
        input[type="text"], input[type="password"] {
            padding: 8px; margin-bottom: 15px; width: 300px; border: 1px solid #000;
            box-sizing: border-box; font-size: 1.1em; display: block;
        }
        input[type="password"] { border: 3px solid #000; } 
        button { padding: 10px 20px; font-size: 1em; cursor: pointer; margin-right: 10px; }
        .message { padding: 10px; border: 1px solid; margin-top: 20px; font-size: 1.2em; }
        .error { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
        .field-group { display: flex; align-items: center; margin-bottom: 10px; }
        .field-group label { min-width: 100px; font-size: 1.3em; margin-right: 15px; }
    </style>
</head>
<body>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        
        <div class="field-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username_input); ?>" required>
        </div>

        <div class="field-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required> 
        </div>
        
        <button type="submit" name="login">Login</button>
        
    </form>
    
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" style="margin-top: 20px;">
        <button type="submit" name="logout">Logout</button>
    </form>

    <?php echo $feedback_message; ?>
    
    <hr>
    
    <?php if ($is_logged_in): ?>
        <p style="font-size: 1.5em; font-weight: bold;">User logged in: <?php echo htmlspecialchars($_SESSION['username']); ?></p>

        <p style="font-size: 1.3em; font-weight: bold;">Password:</p>
        <p style="word-wrap: break-word; font-family: monospace; font-size: 1.1em;">
             <?php echo htmlspecialchars($_SESSION['display_hash']); ?>
        </p>
    <?php endif; ?>

</body>
</html>