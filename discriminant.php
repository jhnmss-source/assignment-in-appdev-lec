<?php
// Initialize variables for the input values and the result
$a = $b = $c = '';
$result_output = '';
$discriminant = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $a = filter_input(INPUT_POST, 'a', FILTER_VALIDATE_FLOAT);
    $b = filter_input(INPUT_POST, 'b', FILTER_VALIDATE_FLOAT);
    $c = filter_input(INPUT_POST, 'c', FILTER_VALIDATE_FLOAT);

    // Check if all inputs are valid numbers
    if ($a !== false && $b !== false && $c !== false && $a !== null && $b !== null && $c !== null) {
      
        $discriminant = ($b * $b) - (4 * $a * $c);

      
        $result_output = "<p class='result success'>✅ The Discriminant (D) is: $discriminant</p>";
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discriminant Calculator</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@400&display=swap');
        
        body {
            background-color: #000; /* Dark background */
            color: #fff;
            font-family: 'Roboto', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff; /* White content area */
            color: #000;
            padding: 40px;
            border-radius: 5px;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
            width: 90%;
            max-width: 600px;
        }
        h1 {
            font-family: 'Playfair Display', serif; /* Serif font for the title */
            font-size: 2.5em;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 30px;
            text-align: left;
        }
        form > div {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        label {
            font-size: 1.5em;
            font-weight: bold;
            margin-right: 15px;
            width: 20px; /* To align the A, B, C labels */
        }
        input[type="text"] {
            flex-grow: 1;
            padding: 12px 10px;
            font-size: 1em;
            border: 2px solid #000;
            outline: none;
            box-sizing: border-box;
            max-width: 300px; /* Constraint width as seen in the image */
        }
        input[type="submit"] {
            background-color: #444; /* Dark button background */
            color: #fff;
            border: none;
            padding: 12px 30px;
            font-size: 1.2em;
            cursor: pointer;
            border-radius: 0;
            margin-top: 20px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5); /* Simple shadow for a lifted look */
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #000;
        }
        
        /* Result styling */
        .result {
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 1.1em;
            font-weight: bold;
        }
        .result.success {
            background-color: #e6ffe6;
            color: #008000;
            border: 1px solid #008000;
        }
        .result.error {
            background-color: #ffe6e6;
            color: #cc0000;
            border: 1px solid #cc0000;
        }
        .analysis {
            padding: 10px;
            margin-top: 10px;
            background-color: #f0f0f0;
            border-left: 5px solid #007bff;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Discriminant of a quadratic equation</h1>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div>
                <label for="a">A</label>
                <input type="text" id="a" name="a" placeholder="Value of a here" 
                       value="<?php echo htmlspecialchars($a); ?>" required>
            </div>

            <div>
                <label for="b">B</label>
                <input type="text" id="b" name="b" placeholder="Value of b here" 
                       value="<?php echo htmlspecialchars($b); ?>" required>
            </div>

            <div>
                <label for="c">C</label>
                <input type="text" id="c" name="c" placeholder="Value of c here" 
                       value="<?php echo htmlspecialchars($c); ?>" required>
            </div>

            <input type="submit" value="Submit">
        </form>
        
        <?php
            if ($result_output) {
                echo $result_output;
            }
        ?>

    </div>
</body>
</html>