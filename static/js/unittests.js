$(document).ready(function() {
    // Set some classes before processing
    $('#unitTestContainer .unitTestRepeater').addClass('collapsibleTestSection');

    // Add document event handlers
    $('#btnShowSuccessfulTests').on('click', function(event) {
        toggleSuccessfulTests();
    });

    // Add accordion functionality to the test cases
    $('.collapsibleTestSection').accordion({
        active: false,
        collapsible: true,
        heightStyle: 'content'
    });

    $('#btnCollapseAllSections').on('click', function (event) {
        collapseAllSections();
    });

    $('#btnExpandAllSections').on('click', function (event) {
        expandAllSections();
    });

    // Add classes to successful and failed tests
    $('.unitTestRepeater').each(function() {
        var repeater = this;
        $(repeater).each(function(foundItem) {
            if ($(this).find('.unitTestResult > span').hasClass('success-message')) {
                $(repeater).addClass('successful-test');
            } else if ($(this).find('.unitTestResult > span').hasClass('error-message')) {
                $(repeater).addClass('failed-test');
            } else {
                $(repeater).addClass('incompleted-test');
            }
        });
    });
    expandAllSections();
    hideSuccessfulTests();

    // Set Counts
    $('#spSuccessfulCount').text($('.unitTestRepeater.successful-test').length);
    $('#spFailedCount').text($('.unitTestRepeater.failed-test').length);
    $('#spIncompleteCount').text($('.unitTestRepeater.incompleted-test').length);
});

var hideSuccessfulTests = function () {
    $('.unitTestRepeater.successful-test').hide();
}

var showSuccessfulTests = function () {
    $('.unitTestRepeater.successful-test').show();
}

var toggleSuccessfulTests  = function () {
    $('.unitTestRepeater.successful-test').toggle(500);
}

var expandAllSections = function () {
    $('.collapsibleTestSection').accordion('option', 'active', 0);
}

var collapseAllSections = function () {
    $('.collapsibleTestSection').accordion('option', 'active', false);
}