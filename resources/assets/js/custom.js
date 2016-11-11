var h1 = $("h1"),
        form = $("form.control"),
        check_box_row = $(".check_box_row"),
        check_inputs = $("[type=radio], [type=checkbox]"),
        control_block = $(".control_block"),
        radio_control_blocks = $("input[type=radio]"),
        btn_control_blocks = $(".btn"),
        location_pathname = location.pathname,
        presentation,
        inputs_control_blocks = $(".control_block input, .control_block button");
location_pathname = location_pathname.replace(/^\/|\/$/g, '');
location_pathname = location_pathname.split('/');
location_pathname = location_pathname.shift();
presentation = $("[role=presentation] [href=" + location_pathname + "]");
presentation ? presentation.parent().addClass('active') : false;
inputs_control_blocks.each(function (index, elem) {
    elem.disabled = true;
});
check_inputs.each(function (index, elem) {
    elem.checked = false;
});
form.on("change", function (e) {
    var cur_input = $(e.target),
            disable_status,
            cur_input_name,
            check,
            tr_class;
    cur_input_name = cur_input.attr("name");
    if (cur_input_name.indexOf('page_id') !== -1) {
        cur_input_name = 'page_id';
    } else if (cur_input_name.indexOf('user_id') !== -1) {
        cur_input_name = 'user_id';
    }
    console.log(cur_input_name);
    switch (cur_input_name) {
        case "page_id":
        case "user_id":
            if (cur_input.prop('checked')) {
                tr_class = "marked";
                disable_status = false;
            } else {
                tr_class = "";
                disable_status = true;
            }
            //console.log();
            cur_input.closest("tr").attr('class', tr_class);
            check_box_row.each(function (index, elem) {
                if (elem.checked === true) {
                    disable_status = false;
                }
            });
            check = !disable_status;
            radio_control_blocks.each(function (index, elem) {
                elem.disabled = disable_status;
            });
            disableSendButtons(radio_control_blocks, btn_control_blocks, check);
            break;
        case "control_block":
            console.log(cur_input.attr('type'));
            btn_control_blocks.each(function (index, elem) {
                elem.disabled = true;
            });
            cur_input.parent().find(".btn").prop('disabled', false);
            break;
    }
});
$('form.filter').on('submit', function (e) {
    var elems = $(this).find('input, select');
    elems.each(function (index, elem) {
        if (!elem.value) {
            elem.name = '';
        }
    });
});
function disableSendButtons(inputs, buttons, check) {
    buttons.each(function (index, elem) {
        elem.disabled = true;
    });
    if (check) {
        inputs.each(function (index, elem) {
            var elem = $(elem);
            if (elem.prop('checked')) {
                elem.parent().find(".btn").prop('disabled', false);
            }
        });
    }
}
