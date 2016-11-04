var h1 = document.querySelector("h1"),
        form = document.querySelector("form.control"),
        input_page_id_elems = document.querySelectorAll("[name^=page_id_]"),
        check_inputs = document.querySelectorAll("[type=radio], [type=checkbox]"),
        control_block = document.querySelector(".control_block"),
        radio_control_blocks = control_block.querySelectorAll("input[type=radio]"),
        btn_control_blocks = control_block.querySelectorAll(".btn"),
        inputs_control_blocks = document.querySelectorAll(".control_block input, .control_block button");
document.body.insertBefore(h1, document.body.firstChild);
[].forEach.call(inputs_control_blocks, function (elem) {
    elem.disabled = true;
});
[].forEach.call(check_inputs, function (elem) {
    elem.checked = false;
});
form.addEventListener("change", function (e) {
    var cur_input = e.target,
            disable_status,
            cur_input_name,
            check,
            tr_class;
    cur_input_name = cur_input.getAttribute("name");
    if (cur_input_name.indexOf('page_id') !== -1) {
        cur_input_name = 'page_id';
    }
    switch (cur_input_name) {
        case "page_id":
            if (cur_input.checked) {
                tr_class = "marked";
                disable_status = false;
            } else {
                tr_class = "";
                disable_status = true;
            }
            cur_input.closest("tr").className = tr_class;
            [].forEach.call(input_page_id_elems, function (elem) {
                if (elem.checked === true) {
                    disable_status = false;
                }
            });
            check = !disable_status;
            [].forEach.call(radio_control_blocks, function (elem) {
                elem.disabled = disable_status;
            });

            disableSendButtons(radio_control_blocks, btn_control_blocks, check);
            break;
        case "control_block":
            [].forEach.call(btn_control_blocks, function (elem) {
                elem.disabled = true;
            });
            cur_input.parentElement.querySelector(".btn").disabled = false;
            break;
    }
});
function disableSendButtons(inputs, buttons, check) {
    [].forEach.call(buttons, function (button) {
        button.disabled = true;
    });
    if (check) {
        [].forEach.call(inputs, function (elem) {
            if (elem.checked === true) {
                elem.parentElement.querySelector(".btn").disabled = false;
            }
        });
    }
}
(function () {
    if (!Element.prototype.closest) {
        Element.prototype.closest = function (css) {
            var node = this;
            while (node) {
                if (node.matches(css))
                    return node;
                else
                    node = node.parentElement;
            }
            return null;
        };
    }
})();
(function () {
    if (!Element.prototype.matches) {
        Element.prototype.matches = Element.prototype.matchesSelector ||
                Element.prototype.webkitMatchesSelector ||
                Element.prototype.mozMatchesSelector ||
                Element.prototype.msMatchesSelector;
    }
})();
