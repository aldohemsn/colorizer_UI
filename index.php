<?php
include 'functions.php';
$usageInfoData = json_decode(file_get_contents('usageInfo.json'), true);
?>

<title>巧言·点色 - 词性颜色标注与高级隐藏/显示交互</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="styles.css">

<header>
    <h1>巧言·点色</h1>
    <h2>给语言来点颜色</h2>
</header>

<form method="POST" class="noselect" id="inputBox" style="<?= (isset($colorizedHtml) && !empty($colorizedHtml)) ? 'display:none;' : '' ?>">
    <textarea name="text" placeholder="<?= htmlspecialchars($jsonData['en']['defaultSentence']); ?>"><?= htmlspecialchars($userText); ?></textarea>
    <select name="language">
        <?php 
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

<a href="#" id="usageBanner" id="usageBannerContent">用法</a>

<div id="usageInfo" style="display:none;" class="noselect">
    <?php foreach ($usageInfoData['usageInstructions'] as $instruction): ?>
        <p><?= $instruction; ?></p>
    <?php endforeach; ?>
</div>

<div class="noselect" id="colorizedOutput" style="<?= (isset($colorizedHtml) && !empty($colorizedHtml)) ? '' : 'display:none;' ?>">
    <?= $colorizedHtml; ?>
    <div id="buttonGroup"> <!-- Add this div to group the buttons -->
        <button id="restoreAll">重置</button>
        <button id="returnButton">返回</button>
    </div>
</div>

<script src="./jquery.min.js"></script>

<script>
    $(document).ready(function() {
        const defaultSentences = {
            'en': <?= json_encode($jsonData['en']['defaultSentence']); ?>,
            'es': <?= json_encode($jsonData['es']['defaultSentence']); ?>,
            'fr': <?= json_encode($jsonData['fr']['defaultSentence']); ?>
        };

        $('select[name="language"]').change(function() {
            const language = $(this).val();
            $('textarea[name="text"]').val(defaultSentences[language]).attr('placeholder', defaultSentences[language]);
        });

        $('.word').on('mouseenter', function() {
            const pos = $(this).data('pos');
            $(this).attr('title', 'POS: ' + pos);
        }).on('click', function(e) {
            const pos = $(this).data('pos').toLowerCase();
            let relatedTags = [];

            // Loop over the buttons configuration to find related tags
            const buttonsConfig = <?= json_encode($jsonData[$language]['buttons']); ?>;
            buttonsConfig.forEach(button => {
                if (button.tags.includes(pos)) {
                    relatedTags = relatedTags.concat(button.tags);
                }
            });

            const sentenceElement = $(this).closest('p');

            if (e.shiftKey && e.ctrlKey) {
                // Shift+Ctrl+click will hide/show words that are not in the relatedTags group across the entire document.
                $('.word').each(function() {
                    if (!relatedTags.includes($(this).data('pos').toLowerCase())) {
                        $(this).toggleClass('hidden');
                    }
                });
            } else if (e.ctrlKey) {
                // Ctrl+click will hide/show words that are not in the relatedTags group within the same sentence.
                sentenceElement.find('span.word').each(function() {
                    if (!relatedTags.includes($(this).data('pos').toLowerCase())) {
                        $(this).toggleClass('hidden');
                    }
                });
            } else {
                // A regular click will hide/show words that are within the relatedTags group in the same sentence.
                sentenceElement.find('span.word').each(function() {
                    if (relatedTags.includes($(this).data('pos').toLowerCase())) {
                        $(this).toggleClass('hidden');
                    }
                });
            }
        });

        document.addEventListener('dblclick', function(e) {
            e.preventDefault();
        }, false);

        $('.sentence-id').on('click', function() {
            const sentenceElement = $(this).closest('p');
            sentenceElement.find('span.word').each(function() {
                const pos = $(this).data('pos');
                const defaultColor = <?= json_encode($jsonData[$language]['colors']); ?>[pos] || '#FFFFFF';
                $(this).css('background-color', defaultColor).removeClass('hidden');
            });
        });

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
            $('#colorizedOutput').hide();
            $('#inputBox').show();
        });

        $('#restoreAll').on('click', function() {
            $('.word').each(function() {
            const pos = $(this).data('pos');
            const defaultColor = <?= json_encode($jsonData[$language]['colors']); ?>[pos] || '#FFFFFF';
            $(this).css('background-color', defaultColor).removeClass('hidden');
            });
        });
    });
</script>
