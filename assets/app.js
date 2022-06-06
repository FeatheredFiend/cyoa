/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
import './bootstrap';


$(document).ready(function() {

    $("#toggleStats").click(function() {
        if ($("#heroStats").hasClass("mobileHidden")) {
            $("#heroStats").removeClass('mobileHidden');
            $("#toggleStats").text('Close Stats');
        } else {
            $("#heroStats").addClass('mobileHidden');
            $("#toggleStats").text('Open Stats');
        }
    });

    $("#toggleSearch").click(function() {
        if ($("#searchBox").hasClass("hidden")) {
            $("#searchBox").removeClass('hidden');
            $("#tableView").removeClass('col-md-12');
            $("#toggleSearch").text('Close Search');
        } else {
            $("#searchBox").addClass('hidden');
            $("#tableView").addClass('col-md-12');
            $("#toggleSearch").text('Open Search');
        }
    });

    if ($("#paragraph_gamebook").length > 0) {
        var gamebook = $('#gamebook').text();
        var dd = document.getElementById('paragraph_gamebook');
        for (var i = 0; i < dd.options.length; i++) {
            if (dd.options[i].text === gamebook) {
                dd.selectedIndex = i;
                $("#paragraph_gamebook").attr("readonly", true);
                break;
            }
        }
    }

    if ($("#paragraph_action_gamebook").length > 0) {
        var gamebook = $('#gamebook').text();
        var paragraph = $('#paragraph').text();
        $("#paragraph_action_gamebook").val(gamebook);
        $("#paragraph_action_paragraph").val(paragraph);
        $("#paragraph_action_gamebook").attr("readonly", true);
        $("#paragraph_action_paragraph").attr("readonly", true);
    }

    if ($("#paragraph_action_equipment_required_paragraphaction").length > 0) {
        var paragraphaction = $('#paragraphaction').text();
        $("#paragraph_action_equipment_required_paragraphaction").val(paragraphaction);
        $("#paragraph_action_equipment_required_paragraphaction").attr("readonly", true);
    }

    if ($("#paragraph_direction_equipment_required_paragraphdirection").length > 0) {
        var paragraphdirection = $('#paragraphdirection').text();
        $("#paragraph_direction_equipment_required_paragraphdirection").val(paragraphdirection);
        $("#paragraph_direction_equipment_required_paragraphdirection").attr("readonly", true);
    }

    if ($("#playerattackstrength").length > 0) {
        var playerattackstrength = $('#playerattackstrength').text();
        $("#playerattackstrength").val(playerattackstrength + $('#playerskill').text());
    }

    if ($("#paragraph_direction_gamebook").length > 0) {
        var gamebook = $('#gamebook').text();
        var paragraph = $('#paragraph').text();
        $("#paragraph_direction_gamebook").val(gamebook);
        $("#paragraph_direction_gamebook").attr("readonly", true);
        $("#paragraph_direction_paragraph").val(paragraph);
        $("#paragraph_direction_paragraph").attr("readonly", true);
    }

    if ($(".gamebookTable").length > 0) {
        var gamebook = $('#gamebook').text();
        $('.gamebookTable').each(function() {
            $(this).text(gamebook);
        });
    }

    if ($("#merchant_paragraph").length > 0) {
        var paragraph = $('#paragraph').text();
        $("#merchant_paragraph").val(paragraph);
        $("#merchant_paragraph").attr("readonly", true);
    }

    if ($("#merchant_inventory_merchant").length > 0) {
        var merchant = $('#merchant').text();
        var dd = document.getElementById('merchant_inventory_merchant');
        for (var i = 0; i < dd.options.length; i++) {
            if (dd.options[i].text === merchant) {
                dd.selectedIndex = i;
                $("#merchant_inventory_merchant").attr("readonly", true);
                break;
            }
        }
    }

    if ($("#enemy_paragraph").length > 0) {
        var paragraph = $('#paragraph').text();
        $("#enemy_paragraph").val(paragraph);
        $("#enemy_paragraph").attr("readonly", true);
    }

    if ($("#enemy").length > 0) {
        $("#paragraphDirection").addClass('hidden');
    }

    if ($("#battleEnemy").length > 0) {
        $("#battleCreate").addClass('hidden');
        $("#battleCreateLuck").addClass('hidden');
    }

    if ($("#luck").text() == 0) {
        $("#battleNextLuck").addClass('hidden');
    }

    if (($("#stamina").text() < 0) || ($("#stamina").text() == 0)) {
        $("#game").remove();
        $("#gameover").removeClass('hidden');
    }

    if ($("#enemystamina").length > 0) {
        if (($("#enemystamina").text() < 0) || ($("#enemystamina").text() == 0)) {
            $("#paragraphEnemy").remove();
            $("#paragraphBattle").remove();
            $("#paragraphDirection").removeClass('hidden');
        }
    }

});
