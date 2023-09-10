<?php
// Configuration settings for the CoreNLP service
$corenlp_url = getenv('CORENLP_URL');
$corenlp_user = getenv('CORENLP_USER');
$corenlp_password = getenv('CORENLP_PASSWORD');

/**
 * Custom error handler to provide more descriptive error messages.
 * Specifically handles unauthorized access and other errors.
 */
function customErrorHandler($errno, $errstr, $errfile, $errline) {
    if (strpos($errstr, '401 Unauthorized') !== false) {
        exit('服务授权失败');  // Service authorization failed
    }
    exit("An error occurred: [$errno] $errstr<br>Error on line $errline in $errfile<br>");
}
set_error_handler("customErrorHandler");

// List of supported languages and their configurations
$languages = ['en', 'es', 'fr'];
$jsonData = [];

// Load language-specific configurations from JSON files
foreach ($languages as $lang) {
    $fileContent = @file_get_contents($lang . '.json');
    if ($fileContent === false) {
        exit("Error reading $lang.json file.");
    }
    $jsonData[$lang] = json_decode($fileContent, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        exit("Error decoding $lang.json: " . json_last_error_msg());
    }
}

// Process user input and set default values if not provided
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $userText = $jsonData['en']['defaultSentence'];
} else {
    $userText = $_POST['text'] ?? '';
}

// Truncate the user's input without cutting a word in half and append " ..."
if (strlen($userText) > 500) {
    $userText = substr($userText, 0, 500);
    $lastSpacePosition = strrpos($userText, ' ');
    if ($lastSpacePosition !== false) {
        $userText = substr($userText, 0, $lastSpacePosition);
    }
    $userText .= " ...";
}

function colorize($text, $language) {
    global $jsonData, $corenlp_url, $corenlp_user, $corenlp_password;
    $colorScheme = $jsonData[$language]['colors'];
    $text = $text ?: $jsonData[$language]['defaultSentence'];

    $ch = curl_init();
    $getParams = http_build_query([
        'properties' => json_encode(['annotators' => 'tokenize,ssplit,pos', 'outputFormat' => 'json']),
        'pipelineLanguage' => $language,
    ]);
    curl_setopt($ch, CURLOPT_URL, "$corenlp_url?$getParams");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $text);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Basic ' . base64_encode("$corenlp_user:$corenlp_password"),
        'Content-Type: application/x-www-form-urlencoded',
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if ($httpCode >= 200 && $httpCode < 300) {
        // Successful response
        $data = json_decode($response, true);
    } else {
        // Handle error based on HTTP status code
        exit('HTTP error: ' . $httpCode);
    }
    
    if (curl_errno($ch)) {
        exit('好像出了点问题。待会儿再试试吧。');
    }
    curl_close($ch);

    $colorizedHtml = '';
    foreach ($data['sentences'] as $index => $sentence) {
        $colorizedHtml .= "<p><span class='sentence-id' data-sentence-id='{$index}'>" . ($index + 1) . ".</span> ";
        foreach ($sentence['tokens'] as $token) {
            $pos = $token['pos'];
            $color = $colorScheme[$pos] ?? '#FFFFFF';
            $colorizedHtml .= "<span class='word' data-pos='$pos' style='background-color:$color; border-radius: 5px; padding: 2px 5px; margin: 2px;'>{$token['word']}</span> ";
        }
        $colorizedHtml .= "</p>";
        
        if (strpos(end($sentence['tokens'])['after'], "\n") !== false) {
            $colorizedHtml .= "<hr>";
        }
    }

    return $colorizedHtml;
}

$language = $_POST['language'] ?? 'en';
$colorizedHtml = ($_SERVER['REQUEST_METHOD'] === 'POST') ? colorize($userText, $language) : '';
?>