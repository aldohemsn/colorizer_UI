$(document).ready(function() {
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
            $('.word').each(function() {
                if (!relatedTags.includes($(this).data('pos').toLowerCase())) {
                    $(this).toggleClass('hidden');
                }
            });
        } else if (e.ctrlKey) {
            sentenceElement.find('span.word').each(function() {
                if (!relatedTags.includes($(this).data('pos').toLowerCase())) {
                    $(this).toggleClass('hidden');
                }
            });
        } else {
            sentenceElement.find('span.word').each(function() {
                if (relatedTags.includes($(this).data('pos').toLowerCase())) {
                    $(this).toggleClass('hidden');
                }
            });
        }
    });
});

