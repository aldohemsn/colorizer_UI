<?php
include 'functions.php';

// Load the XML content
$xmlContent = file_get_contents('usageInfo.xml');

// Parse the XML content using SimpleXML
$usageInfoData = simplexml_load_string($xmlContent);
?>

<!-- Setting the title and meta information for the webpage -->
<head>
    <!-- check for the existence of an environment variable -->
    <?php
    if (getenv('UMAMI_SCRIPT')) {
        echo getenv('UMAMI_SCRIPT');
    }
    ?>

    <title>巧言·点色 - 词性颜色标注与高级隐藏/显示交互</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="巧言·点色 - 文本颜色化工具">
    <meta name="description" content="「巧言·点色」是一个基于词性标注的文本颜色化工具。它提供了交互式界面，用户可以输入文本、选择语言，并根据单词的词性标签获取颜色化的文本。此外，该工具还提供了基于词性标签的单词高级隐藏/显示交互功能。">
    <meta name="keywords" content="文本颜色化, 词性标注, 交互式界面, 多语言支持, 词性颜色标注, 高级隐藏/显示交互, 语言学习, 翻译训练, 文本记忆, 语言教育">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
</head>

<!-- Header section of the webpage -->
<header>
    <h1>巧言 · <span style="font-family: 'liu_jian_mao_caoregular', sans-serif;">点色</span></h1>
</header>

<!-- Form for user input -->
<form method="POST" class="noselect" id="inputBox" style="<?= (isset($colorizedHtml) && !empty($colorizedHtml)) ? 'display:none;' : '' ?>">
    <textarea name="text" placeholder="<?= htmlspecialchars($jsonData['en']['defaultSentence']); ?>"><?= htmlspecialchars($userText); ?></textarea>
    <select name="language">
        <?php 
        // Define language names for the dropdown
        $languageNames = [
            'en' => '英文',
            'es' => '西班牙文',
            'fr' => '法文'
        ];
        foreach ($languages as $lang): 
        ?>
            <option value="<?= $lang ?>" <?= ($language == $lang) ? 'selected' : ''; ?>><?= $languageNames[$lang] ?? ucfirst($lang) ?></option>
        <?php endforeach; ?>
    </select>
    <input type="submit" value="点色" id="submitButton">
</form>

<!-- Usage banner and instructions -->
<a href="#" id="usageBanner" id="usageBannerContent">说明</a>
<div id="usageInfo" style="display:none;" class="noselect">
    <?php foreach ($usageInfoData->children() as $instruction): ?>
        <?php if ($instruction->getName() == "subtitle"): ?>
            <h3><?= $instruction; ?></h3>
        <?php else: ?>
            <?php 
            $underlineStyle = ($instruction->attributes()->underline == "true") ? 'text-decoration: underline;' : '';
            ?>
            <p style="<?= $underlineStyle ?>"><?= $instruction; ?></p>
        <?php endif; ?>
    <?php endforeach; ?>
</div>


<!-- Display colorized output -->
<div class="noselect" id="colorizedOutput" style="<?= (isset($colorizedHtml) && !empty($colorizedHtml)) ? '' : 'display:none;' ?>">
    <?= $colorizedHtml; ?>
    <div id="buttonGroup"> <!-- Grouping the buttons -->
        <button id="restoreAll">重置</button>
        <button id="returnButton">返回</button>
    </div>
</div>

<footer>
    <p style="text-align: center; font-size: 0.8rem; margin-top: 20px; color: #888;">体验完整功能请使用电脑</p>
    <p style="text-align: center; font-size: 0.8rem; margin-top: 20px; color: #888;">
        Developed by <a href="mailto:aldohe@msn.com" target="_blank" rel="noopener noreferrer" style="text-decoration: none; color: inherit;">何晓鸿</a>
    <br> 
        Inspired by <a href="https://parts-of-speech.info/" target="_blank" rel="noopener noreferrer" style="text-decoration: none; color: inherit;">Parts-of-speech.Info</a>
    </p>
</footer>


<!-- Include jQuery and custom scripts -->

<!-- Include jQuery and custom scripts -->
<script src="./jquery.min.js"></script>
<script src="./colorized-text-clicking.js"></script>

<script>
    $(document).ready(function() {
        // Default sentences for different languages.
        const defaultSentences = {
            'en': <?= json_encode($jsonData['en']['defaultSentence']); ?>,
            'es': <?= json_encode($jsonData['es']['defaultSentence']); ?>,
            'fr': <?= json_encode($jsonData['fr']['defaultSentence']); ?>
        };

        // Configuration of buttons and mapping of parts of speech to colors.
        const buttonsConfig = <?= json_encode($jsonData[$language]['buttons']); ?>;
        const languageColors = <?= json_encode($jsonData[$language]['colors']); ?>;

        // Initialize the colorized text clicking interactions.
        initializeColorizedTextClicking(buttonsConfig, languageColors);

        // Handle language change event to update the default sentence in the textarea.
        $('select[name="language"]').change(function() {
            const language = $(this).val();
            $('textarea[name="text"]').val(defaultSentences[language]).attr('placeholder', defaultSentences[language]);
        });

        // Prevent default double click behavior.
        document.addEventListener('dblclick', function(e) {
            e.preventDefault();
        }, false);

        // Toggle the display of usage information.
        document.getElementById('usageBanner').addEventListener('click', function(e) {
            e.preventDefault();
            var usageInfo = document.getElementById('usageInfo');
            if (usageInfo.style.display === 'none') {
                usageInfo.style.display = 'block';
            } else {
                usageInfo.style.display = 'none';
            }
        });

        // Hide usage information when clicked.
        document.getElementById('usageInfo').addEventListener('click', function() {
            this.style.display = 'none';
        });

        // Handle return button click to toggle between input and output views.
        $('#returnButton').on('click', function() {
            $('#colorizedOutput').fadeOut(300, function() {
                $('#inputBox').fadeIn(300);
            });
        });

        // Restore the original colors and visibility of all words.
        $('#restoreAll').on('click', function() {
            $('.word').each(function() {
                const pos = $(this).data('pos');
                const defaultColor = languageColors[pos] || '#FFFFFF';
                $(this).css('background-color', defaultColor).removeClass('hidden');
            });
        });
    });
</script>
