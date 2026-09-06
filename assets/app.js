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

// Change the selector if needed
var $table = $('table.scroll'),
    $bodyCells = $table.find('tbody tr:first').children(),
    colWidth;

// Adjust the width of thead cells when window resizes
$(window).resize(function() {
    // Get the tbody columns width array
    colWidth = $bodyCells.map(function() {
        return $(this).width();
    }).get();
    
    // Set the width of thead columns
    $table.find('thead tr').children().each(function(i, v) {
        $(v).width(colWidth[i]);
    });    
}).resize(); // Trigger resize handler


// Flashes the changed stat number and syncs its progress bar (if any).
// Purely presentational — does not touch any game-state calculation.
function pulseStat(selector, isIncrease) {
    var $el = $(selector);
    if ($el.length === 0) {
        return;
    }
    $el.removeClass('stat-flash-up stat-flash-down');
    void $el[0].offsetWidth; // restart the CSS animation
    $el.addClass(isIncrease ? 'stat-flash-up' : 'stat-flash-down');
    var $stat = $el.closest('.stat');
    if ($stat.length && $stat.attr('data-start')) {
        var start = parseFloat($stat.attr('data-start'));
        var val = parseFloat($el.text());
        if (start > 0 && !isNaN(val)) {
            var pct = Math.max(0, Math.min(100, (val / start) * 100));
            $stat.find('.stat__fill').css('width', pct + '%');
        }
    }
}

$(document).ready(function() {

    $("#navToggle").on('click', function() {
        var expanded = $(this).attr('aria-expanded') === 'true';
        $(this).attr('aria-expanded', String(!expanded));
        $("#siteNav").toggleClass('is-open');
    });

    $(document).on('click', '.flash-toast__close', function() {
        var $toast = $(this).closest('.flash-toast');
        $toast.addClass('flash-toast--leaving');
        setTimeout(function() { $toast.remove(); }, 220);
    });

    $('.flash-toast').each(function() {
        var $toast = $(this);
        setTimeout(function() {
            $toast.addClass('flash-toast--leaving');
            setTimeout(function() { $toast.remove(); }, 220);
        }, 5000);
    });

    $("#toggleStats").click(function() {
        $("#heroStats").removeClass('mobileHidden');
    });

    $("#heroStatsClose").click(function() {
        $("#heroStats").addClass('mobileHidden');
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

    $(document).on('click', '.smallOptionsLink', function() {
        var $body = $('#optionsModalBody').empty();
        $(this).closest('tr').find('.smallOptionsAction').each(function() {
            var $item = $('<div class="options-modal__item"></div>');
            $item.append($(this).contents().clone(true, true));
            $body.append($item);
        });
        $('#optionsModal').addClass('is-open').attr('aria-hidden', 'false');
    });

    $(document).on('click', '[data-modal-close]', function() {
        $('#optionsModal').removeClass('is-open').attr('aria-hidden', 'true');
    });

    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            $('#optionsModal').removeClass('is-open').attr('aria-hidden', 'true');
        }
    });

    if ($("#elevate_user_name").length > 0) {
        $("#elevate_user_name").attr("readonly", true);
    }

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

    if ($("#paragraph_action_enemy_paragraphaction").length > 0) {
        var paragraphaction = $('#paragraphaction').text();
        $("#paragraph_action_enemy_paragraphaction").val(paragraphaction);
        $("#paragraph_action_enemy_paragraphaction").attr("readonly", true);
    }    

    if ($("#paragraph_direction_equipment_required_paragraphdirection").length > 0) {
        var paragraphdirection = $('#paragraphdirection').text();
        $("#paragraph_direction_equipment_required_paragraphdirection").val(paragraphdirection);
        $("#paragraph_direction_equipment_required_paragraphdirection").attr("readonly", true);
    }

    if ($("#paragraph_equipment_paragraph").length > 0) {
        var paragraph = $('#paragraph').text();
        $("#paragraph_equipment_paragraph").val(paragraph);
        $("#paragraph_equipment_paragraph").attr("readonly", true);
    }    

    if ($("#playerattackstrength").length > 0) {
        var playerattackstrength = $('#playerattackstrength').text();
        var enemyattackstrength = $('#enemyattackstrength').text();
        var playerskill = $('#playerskill').text();
        var playerstrength = parseInt(playerattackstrength) + parseInt(playerskill);
        $("#playerattackstrength").text(playerstrength);
        if (parseInt(playerstrength) < parseInt(enemyattackstrength)) {
            $(".paragraphDirection:first").addClass('hidden');
        } else if (parseInt(playerstrength) > parseInt(enemyattackstrength)) {
            $(".paragraphDirection:nth-child(2)").addClass('hidden');
        } else {

        }     
    }

    if ($("#paragraph_direction_gamebook").length > 0) {
        var gamebook = $('#gamebook').text();
        var paragraph = $('#paragraph').text();
        $("#paragraph_direction_gamebook").val(gamebook);
        $("#paragraph_direction_gamebook").attr("readonly", true);
        $("#paragraph_direction_paragraph").val(paragraph);
        $("#paragraph_direction_paragraph").attr("readonly", true);
    }

    if ($("#paragraphDirections").length > 0) {
        $('.paragraphDirectionsEquipment').each(function() {
            var paragraphDirection = $(this);
            $(this).children('table').children('tbody').children('tr').children('.equipmentRequiredId').each(function() {
                var equipmentId = $(this).text();
                $('.heroEquipmentList').each(function() {
                    var heroEquipmentId = $(this).text();
                    var parent = $(this).parent();
                    var qty = parent.children('.heroEquipmentQuantity').text();
                    if (qty > 0) {
                        if (equipmentId == heroEquipmentId) {
                            paragraphDirection.children('div').removeClass('hiddenDirection');
                            paragraphDirection.removeClass("hidden");
                        } else {
                            paragraphDirection.children('div').addClass('hiddenDirection');
                            paragraphDirection.addClass("hidden");
                        }
                    }

                });
            });
        });
        var length = $('.directionButton:visible').length;
        if (length >= 2) {
        } else {
            $('.paragraphDirectionsEquipment:hidden').remove();


        }
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
        $("#paragraphDirections").addClass('hidden');
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
            $("#paragraphDirections").removeClass('hidden');
        }
    }

    if ($("#specialBattle").length > 0) {
        $("#battleCreate").addClass('hidden');
        $("#battleCreateLuck").addClass('hidden');
    }

    if ($(".paragraphactionlist").length > 0) {
        var  actions = $(".paragraphactionlist").length
        for (let i = 0; i <= actions - 1; i++) { 
            var x = 1;
            var actionText = $(".paragraphactionlisttext:first").text();
            var category = $(".paragraphactionlistcategory:first").text();
            var operator = $(".paragraphactionlistoperator:first").text();
            var attribute = $(".paragraphactionlistattribute:first").text();
            var actionvalue = $(".paragraphactionlistactionvalue:first").text();
            var target = $(".paragraphactionlisttarget:first").text();
            var diceroll = $(".paragraphactionlistdiceroll:first").text();
            var paragraph = $("#paragraph").text();
            var adventure = $("#adventure").text();
            var hero = $("#heroId").text();

            $.ajax({
                url: "/run-paragraph-action",
                //url: "/public/run-paragraph-action",
                type: "GET",
                dataType: "JSON",
                data: {
                    category: category,
                    operator: operator,
                    attribute: attribute,
                    actionvalue: actionvalue,
                    target: target,
                    diceroll: diceroll,
                    paragraph: paragraph,   
                    adventure: adventure,   
                    hero: hero                                     
                },
                success: function(score) {
                    if (score.target == "Player") {
                        if (score.category == "Attribute Change") {
                            if (score.operator == "Add") {
                                if (score.attribute == "Stamina") {
                                    var stamina = $("#gamestamina").text();
                                    var adj = parseInt(stamina) + parseInt(score.actionvalue);
                                    $("#gamestamina").text(adj);
                                    pulseStat("#gamestamina", true);
                                } else if (score.attribute == "Skill") {
                                    var skill = $("#gamestamina").text();
                                    var adj = parseInt(skill) + parseInt(score.actionvalue);
                                    $("#gameskill").text(adj);
                                    pulseStat("#gameskill", true);
                                } else if (score.attribute == "Luck") {
                                    var luck = $("#gameluck").text();
                                    var adj = parseInt(luck) + parseInt(score.actionvalue);
                                    $("#gameluck").text(adj);
                                    pulseStat("#gameluck", true);
                                }
                            } else {
                                if (score.attribute == "Stamina") {
                                    var stamina = $("#gamestamina").text();
                                    var adj = parseInt(stamina) - parseInt(score.actionvalue);
                                    $("#gamestamina").text(adj);
                                    pulseStat("#gamestamina", false);
                                } else if (score.attribute == "Skill") {
                                    var skill = $("#gamestamina").text();
                                    var adj = parseInt(skill) - parseInt(score.actionvalue);
                                    $("#gameskill").text(adj);
                                    pulseStat("#gameskill", false);
                                } else if (score.attribute == "Luck") {
                                    var luck = $("#gameluck").text();
                                    var adj = parseInt(luck) - parseInt(score.actionvalue);
                                    $("#gameluck").text(adj);
                                    pulseStat("#gameluck", false);
                                }
                            }
                        } else if (score.category == "Battle") {
                            if (score.operator == "Add") {
                                if (score.attribute == "Stamina") {
                                    var stamina = $("#battleplayerstamina").text();
                                    var adj = parseInt(stamina) + parseInt(score.actionvalue);
                                    $("#battleplayerstamina").text(adj);
                                    pulseStat("#battleplayerstamina", true);
                                } else if (score.attribute == "Skill") {
                                    var skill = $("#battleplayerstamina").text();
                                    var adj = parseInt(skill) + parseInt(score.actionvalue);
                                    $("#battleplayerskill").text(adj);
                                    pulseStat("#battleplayerskill", true);
                                } else if (score.attribute == "Luck") {
                                    var luck = $("#gameluck").text();
                                    var adj = parseInt(luck) + parseInt(score.actionvalue);
                                    $("#gameluck").text(adj);
                                    pulseStat("#gameluck", true);
                                }
                            } else {
                                if (score.attribute == "Stamina") {
                                    var stamina = $("#battleplayerstamina").text();
                                    var adj = parseInt(stamina) - parseInt(score.actionvalue);
                                    $("#battleplayerstamina").text(adj);
                                    pulseStat("#battleplayerstamina", false);
                                } else if (score.attribute == "Skill") {
                                    var skill = $("#battleplayerskill").text();
                                    var adj = parseInt(skill) - parseInt(score.actionvalue);
                                    $("#battleplayerskill").text(adj);
                                    pulseStat("#battleplayerskill", false);
                                } else if (score.attribute == "Luck") {
                                    var luck = $("#gameluck").text();
                                    var adj = parseInt(luck) - parseInt(score.actionvalue);
                                    $("#gameluck").text(adj);
                                    pulseStat("#gameluck", false);
                                }
                            }
                        }
                    } else {

                    }
                },
                error: function(err) {

                }
            });

            $(".paragraphactionlist:nth-child(" + x + ")").remove();
            $('#actionText').append('<tr><td class="text-center py-5">' + actionText + '</td></tr>');
        }
        var healthCheck = $("#gamestamina").text();
        if (parseInt(healthCheck) < 1 ) {
            $("#game").remove();
            $("#gameover").removeClass('hidden');
        }
    }

    if ($(".paragraphEquipmentlist").length > 0) {
        var  equipments = $(".paragraphEquipmentlist").length
        for (var i = 0; i < equipments; i++) { 
            var x = 1;
            var equipment = $(".paragraphequipmentequipment:first").text();
            var adventure = $("#adventure").text();
            var quantity = $(".paragraphequipmentquantity:first").text();
            var category = $(".paragraphequipmentcategory:first").text();
            var equipmentText = category + " " + equipment;

            $.ajax({
                url: "/run-paragraph-equipment",
                //url: "/public/run-paragraph-equipment",
                type: "GET",
                dataType: "JSON",
                data: {
                    adventure: adventure,
                    equipment: equipment,                    
                    quantity: quantity,
                    category: category                               
                },
                success: function(use) {

                },
                error: function(err) {

                }
            });
            $(".paragraphEquipmentlist:nth-child(" + x + ")").remove();
            $('#equipmentText').append('<tr><td class="text-center py-5">' + equipmentText + '</td></tr>');
            x++;
            
        }
        rebuildHeroEquipment();

    }

    function rebuildHeroEquipment() {
        var adventure = $("#adventure").text();
          $.ajax({
            url: "/get-hero-equipment",
            //url: "/public/get-hero-equipment",
              type: "GET",
              dataType: "JSON",
              data: {
                  adventure: adventure
              },
              success: function (equipments) {
                var heroEquipment = $("#heroequipmentTable");
                heroEquipment.html('');
                $.each(equipments, function (key, equipment) {
                    heroEquipment.append('<tr><td class="hidden heroEquipmentList">' + equipment.heroequipment + '</td><td>' + equipment.id + '</td><td>' + equipment.name + '</td><td class="heroEquipmentQuantity">' + equipment.quantity + '</td></tr>');
                });
              },
              error: function (err) {

              }
          });

      }

});
