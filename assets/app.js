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
require('bootstrap');

$(document).ready(function() {
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

    if ($("#equipment_required_paragraph").length > 0) {
        var paragraph = $('#paragraph').text();
        $("#equipment_required_paragraph").val(paragraph);
        $("#equipment_required_paragraph").attr("readonly", true);
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
});
