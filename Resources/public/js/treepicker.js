$(function() {
    $(".treepicker").each(function() {
        var treepicker = $(this);
        var options = {
            plugins: [ 'checkbox' ],
            checkbox: {
                three_state: false,
                cascade: 'undetermined'
            },
            core: {
                'data': {
                    'url' : treepicker.data("treedata"),
                    'data' : function (node) {
                        return { 'id' : node.id };
                    }

                }
            }
        };

        var wrapper = treepicker.closest(".treepicker-wrapper");
        var optionCount = wrapper.find("input.treepicker-item-id").length;

        treepicker.jstree(options);
        treepicker.on("select_node.jstree",function(e,treeNode) {
            if (!isSelected(wrapper, treeNode.node.id)) {
                optionCount++;
                var options = wrapper.find(".treepicker-options");
                var prototype = $(options.data("treeitem").replace(/__prototype__/g,optionCount));
                prototype.find("input.treepicker-item-id").val(treeNode.node.id);
                var anchor = prototype.find(".treepicker-item-title").text(treeNode.node.text);

                var node = treeNode.node;

                while (node.parent && node.parent != "#") {
                    node = treepicker.jstree("get_node", node.parent);
                    var parent = $("<span/>").addClass("treepicker-item-parent").append(
                        $("<span/>").addClass("treepicker-item-parent-title").text(node.text)
                    ).append(
                        $("<span/>").addClass("treepicker-item-parent-sep").text(" / ")
                    );

                    parent.insertBefore(anchor);
                    anchor = parent;
                }

                options.append(prototype);
            }
        });
        treepicker.on("deselect_node.jstree",function(e,treeNode) {
            wrapper.find("input.treepicker-item-id").each(function() {
                var option = $(this);
                if (option.val() == treeNode.node.id) {
                    option.closest(".treepicker-item").remove();
                }
            });
        });
        treepicker.on("model.jstree", function(e,treeNode){
            for (var i in treeNode.nodes) {
                var nodeId = treeNode.nodes[i];
                if (isSelected(wrapper, nodeId)) {
                    treepicker.jstree("select_node", nodeId);
                }
            }
        });
    });
    function isSelected(wrapper, nodeId) {
        var selected = false;
        wrapper.find("input.treepicker-item-id").each(function() {
            if ($(this).val() == nodeId) {
                selected = true;
            }
        });
        return selected;
    }
});
