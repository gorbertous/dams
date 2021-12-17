(function ($) {
    "use strict";
    var eagerFilter = false;
    // Toggle the side navigation
    $("#sidebarToggle").on("click", function (e) {
        e.preventDefault();
        $("body").toggleClass("sb-sidenav-toggled");
    });

    // code for popups
    $('[data-toggle="popover"]').popover({
        html: true
    });

    // Handle left menu active/collapse state
    var current = location.pathname;

    // expand the menu
    function collapseMenu(item) {
        var chapterNavid = item;
        var targetDiv = document.getElementById(chapterNavid).getElementsByClassName("collapse");
        var targetLink = document.getElementById(chapterNavid).getElementsByClassName("nav-link");
        for (var i = 0; i < targetDiv.length; i++) {
            targetDiv.item(i).classList.add("show");
        }
        for (var i = 0; i < targetLink.length; i++) {
            targetLink.item(i).classList.remove("collapsed");
        }
    }
    //is action link in the array
    function isInArrayList(arraylist) {
        // current action link
        const action_link = current.split("/").slice(-1).join("/");
        const analytics_links = arraylist.find(element => element === action_link);
        return analytics_links;
    }
    function setMenuItemActive() {
        switch (true) {
            case current.indexOf("invoice") !== - 1:
                $("#invoices").addClass("active");
                break;
            case current.indexOf("generate-period") !== - 1:
                $("#generate_period").addClass("active");
                break;
            case current.indexOf("rules") !== - 1:
                $("#rules").addClass("active");
                break;
            case current.indexOf("sme-portfolio") !== - 1:
                $("#view_sme").addClass("active");
                collapseMenu("view-items")
                break;
            case current.indexOf("eur-curr") !== - 1:
                $("#port_recal_currency").addClass("active");
                collapseMenu("portfolio-items")
                break;
            case current.indexOf("inclusion-notice-followup") !== - 1:
                $("#inc_follow_up").addClass("active");
                collapseMenu("portfolio-items")
                break;
            case current.indexOf("monitoringvisit") !== - 1:
                $("#port_mv_follow_up").addClass("active");
                collapseMenu("portfolio-items");
                break;
            case current.indexOf("dictionary") !== - 1:
                $("#manual_dictionaries").addClass("active");
                collapseMenu("usermanual-items");
                break;
            case current.indexOf("outsourcing") !== - 1:
                $("#outsourcing").addClass("active");
                collapseMenu("usermanual-items");
                break;
            case current.indexOf("dashboard") !== - 1:
                $("#manual_templates").addClass("active");
                collapseMenu("usermanual-items");
                break;
            case current.indexOf("mapping-view") !== - 1:
                $("#manual_template_summary").addClass("active");
                collapseMenu("usermanual-items");
                break;
            case current.indexOf("toolbox") !== - 1:
                $("#toolbox").addClass("active");
                break;
            case current.indexOf("external") !== - 1:
                $("#ext_data_upload").addClass("active");
                break;
            case current.indexOf("pdlr-list") !== - 1  || current.indexOf("delete-pdlr-report") !== - 1:
                $("#delete_pdlr_report").addClass("active");
                break;
            case current.indexOf("control") !== - 1 :
                $("#delete_inclusion_report").addClass("active");
                break;
            case current.indexOf("pdlr") !== - 1 || current.indexOf("pdlr-validation") !== - 1:
                $("#pd_recoveries").addClass("active");
                break;
            case current.indexOf("inclusion") !== - 1 || current.indexOf("validation") !== - 1 || current.indexOf("split-upload") !== - 1:
                $("#inclusion_dashboard").addClass("active");
                break;
            case current.indexOf("analytics") !== - 1:
                //action links
                const actionlinks = ["analytics-reports", "loan-collateral-report", "active-portfolio-management", 
                    "main-agri-statistics", "active-portfolio-management", "mandate-performance-country", "cumulative-key-portfolio", 
                    "mandate-performance", "seasonality-report"];
                const analytics_links = isInArrayList(actionlinks);
                if (analytics_links) {
                    $("#rep_analytics").addClass("active");
                } else if (current.indexOf("data-extracts-reports") !== -1) {
                    $("#rep_data_extracts").addClass("active");
                } else if (current.indexOf("forecast-reports") !== -1) {
                    $("#rep_forecasts").addClass("active");
                } else if (current.indexOf("operations-reports") !== -1) {
                    $("#rep_operations").addClass("active");
                } else if (current.indexOf("faq") !== -1) {
                    $("#rep_faq").addClass("active");
                }
                collapseMenu("report-items");
                break;
            case current.indexOf("sampling-evaluation") !== - 1:
                if (current.indexOf("drawing") !== -1) {
                    $("#samp_cip").addClass("active");
                } else if (current.indexOf("manual-sampling") !== -1) {
                    $("#samp_manual_pds").addClass("active");
                } else if (current.indexOf("non-cip-sampling") !== -1) {
                    $("#non_cip").addClass("active");
                } else if (current.indexOf("list-samples") !== -1) {
                    $("#samp_randomly").addClass("active");
                } else if (current.indexOf("manual-pd-sampling") !== -1) {
                    $("#samp_manually_sampled").addClass("active");
                } else if (current.indexOf("sample-upload") !== -1) {
                    $("#samp_info_update").addClass("active");
                } else if (current.indexOf("yearly-evaluation") !== -1) {
                    $("#samp_yearly_cip").addClass("active");
                } else if (current.indexOf("transactions-update") !== -1) {
                    $("#samp_tranactions").addClass("active");
                } else {
                    $("#samp_annual").addClass("active");
                }
                collapseMenu("sampling-items");
                break;
            case current.indexOf("transactions") !== - 1:
                $("#view_trn").addClass("active");
                collapseMenu("view-items");
                break;
            case current.indexOf("portfolio") !== - 1:
                $("#port_list").addClass("active");
                collapseMenu("portfolio-items")
                break;
            case current.indexOf("upload-rating") !== - 1:
                $("#sme_upload_rating").addClass("active");
                collapseMenu("sme-items");
                break;
            case current.indexOf("download-rating") !== - 1:
                $("#sme_download_rating").addClass("active");
                collapseMenu("sme-items");
                break;
            case current.indexOf("import") !== - 1:
                $("#edit").addClass("active");
                break;
            default:
                $("#home").addClass("active");
        }
    }
    //append active class to menu item
    setMenuItemActive();

    //filters submit on change
    $(".filters").change(function () {
        if (!$(this).hasClass("filtersLive")) {
            $(this).closest("form").submit();
        }
    });

    $(".filtersLive").on('input', function () {
        eagerFilter = true;
        $(this).closest("form").submit();
    });
   
    //JQuery date pickeer
    $(".datepicker").datepicker({'dateFormat': 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });

    $( ".formAjax" ).submit(function( event ) {
        if (eagerFilter) {
            eagerFilter = false;
            var actionURL = $( this ).attr('action').split('?')[0] + "?" + $( this ).serialize();
            var divID = $( this ).attr('id') + "_data";
            $.ajax({
                url: actionURL,
                dataType: "html",
                success: function(data) {
                    $('#'+divID).html(data);
                },
                error: function(e) {
                    console.log(e);
                }
            });
            event.preventDefault();
            return false;
        }
        return true;
    });
      
	$('form').submit(function(e)
	{
		//prevent double validations
		var form = $(e.currentTarget);
		function noSubmit(e)
		{
			e.preventDefault();
		}
		form.submit(noSubmit);
		function remove_handler(e){
			$(e.currentTarget).unbind('submit', noSubmit);
		}
		setTimeout(function(){remove_handler(e)}, 4000);
	});

})(jQuery);

const _MS_PER_DAY = 1000 * 60 * 60 * 24;
Date.prototype.getDaysForSAS = function() {
    const utc1 = Date.UTC(1960, 0, 1);
    const utc2 = Date.UTC(this.getFullYear(), this.getMonth(), this.getDate());  
    return Math.floor((utc2 - utc1) / _MS_PER_DAY);
}
Date.prototype.getDateFromDays = function(days) {
    var date = new Date(1960, 0, 1);
    date.setDate(date.getDate() + days);
    return date;
}

function toggleVisible(control, emptyFieldCheck) {
    document.getElementById(control).style = (emptyFieldCheck=='')?'display:none;':'';
}

function setValueAndSubmit(control, value) {
    var elem = document.getElementById(control);
    elem.value = value;
    window.onbeforeunload = null;
    $(elem).closest("form").submit();
    return true;
}

function fireSelectsOnChange() {
    $(document).ready( function () {
        control = $("select#template_type")[0];
        if (control.options[control.selectedIndex].hidden) {
            control.selectedIndex++;
        }
        filterTables(document.getElementById("template_type").value);
        //$("[id*='rule_level']").trigger("change");
        $("[id*='checked_field']").trigger("change");
        $("[id*='operator']").trigger("change");
    });
}

function updateHiddenOptions(control, value) {
    var elem = document.getElementById(control);
    var selected = elem.selectedIndex;
    for (i = 0; i < elem.options.length; i++) {
        if (elem.options[i].text=='') continue;
        setHiddenOption(elem.options[i], value);
        if (elem.options[i].value == elem.options[selected].value && !(elem.options[i].hidden)) {
            elem.selectedIndex = i; 
        }
    }
    if (elem.options[elem.selectedIndex].hidden) {
        elem.selectedIndex = 0;
        $(elem).trigger("change");
    }
}

function selectOptions(val) {
    if (val != "") {
        var temp = val.value.replaceAll("\",\"",",");
        temp = temp.substring(1,temp.length-1);
        selected = temp.split(',').reduce(function(map, obj) {
            map[obj] = obj;
            return map;
        }, {});
        options = val.parentElement.lastElementChild.lastElementChild.options;
        for (i=0; i<options.length; i++) {
            options[i].selected = (selected[options[i].value]==options[i].value);
        }
    }
}

function updateFieldTypes(prefix, type) {
    var field = document.getElementById(prefix.replaceAll('-','.') + 'checked_field');
    var datatype = document.getElementById(prefix.replaceAll('-','.') + 'datatype');
    var val1 = document.getElementById(prefix + 'param-1-value'); 
    var val2 = document.getElementById(prefix + 'param-2-value');
    val1.parentElement.lastElementChild.innerHTML="";
    val2.parentElement.lastElementChild.innerHTML="";
    val1.ondblclick = null;
    val2.ondblclick = null;
    // delete val1.pattern;
    // delete val2.pattern;
    datatype.value = type;
    val1.value = changeValueByDatatype(val1.value, val1.type, type);
    val2.value = changeValueByDatatype(val2.value, val2.type, type);
    //var inputType = (type == 'string')?'text':type;
    switch(type) {
        case "string":
            val1.type = "text";
            val1.placeholder = "value or //entity.field";
            val2.type = "text";
            // delete val1.pattern;
            // delete val2.pattern;
            if (field.selectedOptions[0].getAttribute('dictionary') != null) {
                getDictionaryControl(field.selectedOptions[0].getAttribute('dictionary'), function (template) {
                    var parser = new DOMParser();                    
                    val1.parentElement.lastElementChild.innerHTML = (parser.parseFromString(template(val1),'text/html')).body.innerHTML;
                    selectOptions(val1);
                    val2.parentElement.lastElementChild.innerHTML = (parser.parseFromString(template(val2),'text/html')).body.innerHTML;
                    selectOptions(val2);
                });
                val1.onclick = function(e) {
                    if ($(val1.parentElement.lastElementChild).is(':hidden')) {
                        val1.parentElement.lastElementChild.style = "display: block; position: absolute;";
                        //val1.parentElement.lastElementChild.lastElementChild.focus();
                    } else {
                        val1.parentElement.lastElementChild.style = "display: none;";
                    }
                }
                val2.onclick = function(e) {
                    if ($(val2.parentElement.lastElementChild).is(':hidden')) {
                        val2.parentElement.lastElementChild.style = "display: block; position: absolute;";
                        //val2.parentElement.lastElementChild.lastElementChild.focus();
                    } else {
                        val2.parentElement.lastElementChild.style = "display: none;";
                    }
                }
            }
            break;
        case "date":
            val1.type = "text";
            val1.placeholder = "dd/mm/yyyy or //entity.field";
            // val1.pattern = "(\/\/[A-Za-z0-9_-]+\..+)|(([0-2]?[1-9]|10|30|31)\/(0?[1-9]|10|11|12)\/(19|20)[0-9]{2})"
            val1.ondblclick = function(e) {
                this.placeholder = this.type=="text"?"//entity.field":"dd/mm/yyyy";
                if (this.type != "text") {
                    this.type = "text";
                    this.value = changeValueByDatatype(this.value, "date", "text");
                    this.value = changeValueByDatatype(this.value, "text", "date");
                } else {
                    this.value = changeValueByDatatype(this.value, "date", "text");
                    dateValue = (new Date()).getDateFromDays(parseInt(this.value));
                    this.value = dateValue.getFullYear()+
                    "-"+("0"+(dateValue.getMonth()+1)).substr(-2)+
                    "-"+("0"+dateValue.getDate()).substr(-2);
                    this.type = "date";
                }
            }
            val2.type = "text";
            val2.placeholder = val1.placeholder;
            // val2.pattern = val1.pattern;
            val2.ondblclick = val1.ondblclick;
            break;
        case "number":
            val1.type = "text";
            val1.placeholder = "value or //entity.field";
            // val1.pattern = "(\/\/[A-Za-z0-9_-]+\..+)|([0-9]+(\.[0-9]+)?)"
            val1.ondblclick = function(e) {
                this.type = this.type=="text"?"number":"text";
                this.placeholder = this.type=="text"?"//entity.field":"value";
            }
            val2.type = "text";
            // val2.pattern = val1.pattern;
            val2.ondblclick = val1.ondblclick;
            break;
        default:
    }
}

function changeValueByDatatype(val, from, to) {
    ret = val;
    if (from != to && from == "date") {
        parts = val.split('/'); 
        if (parts.length<3) {
            parts = val.split('-');
            ret = parts.length==3?(new Date(parts[0]+'-'+parts[1]+'-'+parts[2])).getDaysForSAS():val;
        } else {
            ret = (new Date(parts[2]+'-'+parts[1]+'-'+parts[0])).getDaysForSAS();
        } 
    }
    if (from != to && to == "date") {
        num = parseInt(val);
        if (isNaN(num)) {
            ret = val;
        } else {
            dateValue = (new Date()).getDateFromDays(num);
            ret = ("0"+dateValue.getDate()).substr(-2)+
            "/"+("0"+(dateValue.getMonth()+1)).substr(-2)+
            "/"+dateValue.getFullYear();
        }
    }
    return ret;
}

function getDictionaryControl(id, callback) {
    var req = '/damsv2/rules/getDictionaryValues/'+id;
    $.ajax({
        url: req,
        dataType: "json",
        success: function(data) {
            callback(getTemplate(data));
        },
        error: function(e) {
            console.log(e);
        }
    });

}

function getTemplate(data) {
    var options = "";
    for(i = 0; i < data.length; i++) {
        options += "<option value=\"" + data[i].code + "\">" + (data[i].translation?data[i].translation:(data[i].code + " - " + data[i].label)) + "</option>";
    }
    return function(control) {
        return "<select onBlur=\"updateField('"+control.getAttribute('id')+"', this);\" multiple>" + options + "</select>";
    }
}

function updateField(id, control) {
    if ($(control).val() == "") {
        $("#"+id).val("");
    } else {
        var temp = $(control).val().join("\",\"");
        $("#"+id).val("\"" + temp+ "\"");
    }
    $("#"+id).parent().blur();
    $(control).parent().css('display', 'none');
}

function filterOperators(control, type) {
    var elem = document.getElementById(control);
    var typeValue = (type == 'string')?1:(type == 'number')?2:(type == 'date')?4:0;
    for (i = 0; i < elem.options.length; i++) {
        if (elem.options[i].text=='') continue;
        elem.options[i].hidden = (elem.options[i].getAttribute('types') & typeValue)==0;
    }
    if (elem.options[elem.selectedIndex].hidden) {
        elem.selectedIndex = 0;
        $(elem).trigger("change");
    }
}

function filterTables($value) {
    $("[id*='checked_entity'] > option[type!="+$value+"]").prop("hidden", true);
    $("[id*='checked_entity'] > option[type="+$value+"]").prop("hidden", false);
    $("[id*='checked_entity']").trigger("change");
}

function updateFieldVisibility(prefix, params) {
    var val1_ctn = document.getElementById(prefix + 'param-1-value');
    var val1 = val1_ctn.parentElement;
    var val2_ctn = document.getElementById(prefix + 'param-2-value');
    var val2 = val2_ctn.parentElement;
    if (params < 2) {
        val2.style = 'display: none;';
        val2_ctn.value = '';
        if (params < 1) {
            val1.style = 'display: none;';
            val1_ctn.value = '';
        } else {
            val1.style = 'display: block;';
        }
    } else {
        val1.style = 'display: block;';
        val2.style = 'display: block;';
    }
}

function setHiddenOption(control, tableSelected) {
    control.hidden = control.getAttribute('table') != tableSelected;
}

