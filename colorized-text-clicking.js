/**
 * Initializes the colorized text clicking interactions.
 * 
 * @param {Array} buttonsConfig - Configuration of buttons, containing tags related to parts of speech.
 * @param {Object} languageColors - Mapping of parts of speech to their respective colors.
 */
function initializeColorizedTextClicking(buttonsConfig, languageColors) {
    $(document).ready(function() {
        // Handle mouse enter event on words to display their part-of-speech as a tooltip.
        $('.word').on('mouseenter', function() {
            const pos = $(this).data('pos');
            $(this).attr('title', 'POS: ' + pos);
        }).on('click', function(e) {
            const pos = $(this).data('pos').toLowerCase();
            let relatedTags = [];

            // Populate relatedTags based on the clicked word's part-of-speech.
            buttonsConfig.forEach(button => {
                if (button.tags.includes(pos)) {
                    relatedTags = relatedTags.concat(button.tags);
                }
            });

            const sentenceElement = $(this).closest('p');

            // Shift+Ctrl+Click: Hide/Show words not in relatedTags across the entire document.
            if (e.shiftKey && e.ctrlKey) {
                $('.word').each(function() {
                    if (!relatedTags.includes($(this).data('pos').toLowerCase())) {
                        $(this).toggleClass('hidden');
                    }
                });
            } 
            // Ctrl+Click: Hide/Show words not in relatedTags within the same sentence.
            else if (e.ctrlKey) {
                sentenceElement.find('span.word').each(function() {
                    if (!relatedTags.includes($(this).data('pos').toLowerCase())) {
                        $(this).toggleClass('hidden');
                    }
                });
            } 
            // Regular Click: Hide/Show words in relatedTags within the same sentence.
            else {
                sentenceElement.find('span.word').each(function() {
                    if (relatedTags.includes($(this).data('pos').toLowerCase())) {
                        $(this).toggleClass('hidden');
                    }
                });
            }
        });

        // Alt+Click on a sentence: Reset the background color of all words in the sentence.
        $('p').on('click', function(e) {
            if (e.altKey) {
                const sentenceElement = $(this);
                sentenceElement.find('span.word').each(function() {
                    const pos = $(this).data('pos');
                    const defaultColor = languageColors[pos] || '#FFFFFF';
                    $(this).css('background-color', defaultColor).removeClass('hidden');
                });
            }
        });
    });
}
