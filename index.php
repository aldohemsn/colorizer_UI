<?php
include 'functions.php';

// Load the XML content
$xmlContent = file_get_contents('usageInfo.xml');

// Parse the XML content using SimpleXML
$usageInfoData = simplexml_load_string($xmlContent);
?>

<!-- Setting the title and meta information for the webpage -->
<title>译笺·点色 - 词性颜色标注与高级隐藏/显示交互</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="styles.css">

<!-- Header section of the webpage -->
<header>
    <h1>译笺 · <span style="font-family: 'liu_jian_mao_caoregular', sans-serif;">点色</span></h1>
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
            <p><?= $instruction; ?></p>
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
        <span style="font-family: 'liu_jian_mao_caoregular', sans-serif;">点色</span> is developed by 
        <a href="https://www.aldo.fun" target="_blank" rel="noopener noreferrer" style="text-decoration: none; color: inherit;">「译笺」</a>, 
        and inspired by 
        <a href="https://parts-of-speech.info/" target="_blank" rel="noopener noreferrer" style="text-decoration: none; color: inherit;">Parts-of-speech.Info</a>
    </p>
</footer>


<!-- Include jQuery and custom scripts -->

<script src="./jquery.min.js"></script>
<script src="./colorized-text-clicking.js"></script>

<script>
    $(document).ready(function() {
        const defaultSentences = {
            'en': <?= json_encode($jsonData['en']['defaultSentence']); ?>,
            'es': <?= json_encode($jsonData['es']['defaultSentence']); ?>,
            'fr': <?= json_encode($jsonData['fr']['defaultSentence']); ?>
        };

        const buttonsConfig = <?= json_encode($jsonData[$language]['buttons']); ?>;
        const languageColors = <?= json_encode($jsonData[$language]['colors']); ?>;
        initializeColorizedTextClicking(buttonsConfig, languageColors);

        $('select[name="language"]').change(function() {
            const language = $(this).val();
            $('textarea[name="text"]').val(defaultSentences[language]).attr('placeholder', defaultSentences[language]);
        });

        document.addEventListener('dblclick', function(e) {
            e.preventDefault();
        }, false);

        document.getElementById('usageBanner').addEventListener('click', function(e) {
            e.preventDefault();
            var usageInfo = document.getElementById('usageInfo');
            if (usageInfo.style.display === 'none') {
                usageInfo.style.display = 'block';
            } else {
                usageInfo.style.display = 'none';
            }
        });

        document.getElementById('usageInfo').addEventListener('click', function() {
            this.style.display = 'none';
        });

        $('#returnButton').on('click', function() {
            $('#colorizedOutput').fadeOut(300, function() {
                $('#inputBox').fadeIn(300);
            });
        });

        $('#restoreAll').on('click', function() {
            $('.word').each(function() {
                const pos = $(this).data('pos');
                const defaultColor = languageColors[pos] || '#FFFFFF';
                $(this).css('background-color', defaultColor).removeClass('hidden');
            });
        });
    });
</script>
