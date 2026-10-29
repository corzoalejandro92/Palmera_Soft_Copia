SP = "";
TERM = localStorage.getItem('inventory_term') ?? '';
WAREHOUSE_SELECTED = localStorage.getItem('warehouse_selected') ?? null;
$("#warehouse_"+WAREHOUSE_SELECTED).parent().parent().addClass('warehouse_selected');



var tree = {
    data: null,
    dataTree: null,
    permission: false,
    enabledFoxcon: false,
    create: function(data, term = '') {
        var DataSourceTree = function(options) {
            this._data = options.data;
            this._delay = 400;
        };
        DataSourceTree.prototype = {
            data: function(options, callback) {
                if (typeof options.data != "undefined") {
                    setTimeout(function() {
                        callback({ data: options.data });
                    }, this._delay);
                } else { callback({ data: this._data }); }
            }
        };

        var treeDataSource = new DataSourceTree({ data: data });
        var treeNode = $('#FlatTree4').tree({
            selectable: false,
            dataSource: treeDataSource
        });
        
        
        setTimeout(() => {
            $(".ft-globe").parent().trigger('click');
            setTimeout(() => {
                $(".ft-map-pin").parent().trigger('click');
            },500)
        }, 1);

        
    },
    init: function(term = '', firstTime= false) {

            tree.getServerData(term).done(function(data) {
            tree.permission = data.bossPurchasing;
            tree.enabledFoxcon =  data.isFoxconEnabled;
            tree.data = data.tree;
            tree.dataTree = tree.getTreeData(tree.data);
            tree.create(tree.dataTree, term);
            
            
            if(firstTime){

                $('.warehouse_selected').removeClass('warehouse_selected');

                if(WAREHOUSE_SELECTED == undefined || WAREHOUSE_SELECTED == null || WAREHOUSE_SELECTED == ''){
                    WAREHOUSE_SELECTED = data.default_warehouse.id;
                    localStorage.setItem('warehouse_selected',WAREHOUSE_SELECTED);
                }

                if(WAREHOUSE_SELECTED == 1){
                    $("a[data-click='inyectInventoryForm']").hide();
                   }else{
                    $("a[data-click='inyectInventoryForm']").show();
                   }
            

                    setTimeout(() => {
                        $("#warehouse_"+WAREHOUSE_SELECTED).parent().parent().addClass('warehouse_selected');
                        var dataClick = localStorage.getItem('warehouse_selected_data_click');
                        if(WAREHOUSE_SELECTED != data.default_warehouse.id || (WAREHOUSE_SELECTED == data.default_warehouse.id && dataClick != 'LisInventory')){
                            
                            $(".warehouse_selected .tree-actions [data-click='"+dataClick+"']").trigger('click');
                        }
                    },1010);
                
            }else{
                setTimeout(() => {
                    $('.warehouse_selected').removeClass('warehouse_selected');
                    if(WAREHOUSE_SELECTED == undefined || WAREHOUSE_SELECTED == null || WAREHOUSE_SELECTED == ''){
                        WAREHOUSE_SELECTED = data.default_warehouse.id;
                        localStorage.setItem('warehouse_selected',WAREHOUSE_SELECTED);
                    }
                    $("#warehouse_"+WAREHOUSE_SELECTED).parent().parent().addClass('warehouse_selected');
                    var dataClick = localStorage.getItem('warehouse_selected_data_click');
                    if(WAREHOUSE_SELECTED != data.default_warehouse.id || (WAREHOUSE_SELECTED == data.default_warehouse.id && dataClick != 'LisInventory')){
                       
                        $(".warehouse_selected .tree-actions [data-click='"+dataClick+"']").trigger('click');
                        $(".warehouse_selected .tree-actions [data-click='LisInventory']").trigger('click');
                    }
                },1000);

            }

            

            
        });

    },
    getTreeData: function(data) {
        var name_views_change = $("[name='_viewtypesform']").val();
        return $.map(data, function(x, id) {
            var type = '';
            var obj = {};
            if (!$.isEmptyObject(x.categories)) {
                obj['data'] = tree.getTreeData(x.categories);
                obj['type'] = 'folder';
                type = 'folder';
            } else {
                obj['type'] = 'item';
                type = 'item';
            }
            obj['attr'] = { 'id': x.id };
            var disable_option = typeof x.disable_options == "string" ? x.disable_options : "";

            var view_forms = (x.view_forms !== undefined) ? "data-target_form='" + x.view_forms + "'" : "data-target_form='0'";

            obj['name'] = tree.setIcon(x.icon) +"(" + x.code_foxcon +") "  +x.name;
            var element_actions = "";
            if (x.action == true) {
                element_actions = '<div class="tree-actions">\n' +
                    '<i title="Inventario" class="ft-package" data-click="LisInventory" data-warehouse="' + x.id + '" data-for="' + x.icon + '" data-name="' + btoa(unescape(encodeURIComponent(x.name))) + '"/></i>\n' +
                     ((x.warehousable_type == "App\\Headquarter") && tree.enabledFoxcon ? '<i title="Obsequios" class="icon-present" data-click="LisGiftInventory" data-warehouse="' + x.id + '" data-for="' + x.icon + '" /></i>\n' : '')+
                    (x.buttons.transf ? '<i title="Transferir inventario" class="ft-repeat fa-tmove" data-click="LisTransferInventory" data-warehouse="' + x.id + '" data-warehousable="' + x.warehousable_id + '" data-for="' + x.icon + '" data-name="' + btoa(unescape(encodeURIComponent(x.name))) + '"/></i>\n' : '') +
                    
                    (x.icon == "technical" && !tree.enabledFoxcon ? '<i title="Inyectar inventario" class="las la-people-carry" style="font-size:24px;" data-click="inyectInventoryForm" data-id="' + x.id + '" data-for="' + x.icon + '" data-name="' + btoa(unescape(encodeURIComponent(x.name))) + '"/></i>\n' : '') +

                    (tree.enabledFoxcon && x.warehousable_type == "App\\Headquarter" ? '<i title="Entrada mercancia a bodega" class="las la-dolly" style="font-size:24px;" data-click="inyectInventoryForm" data-id="' + x.id + '" data-for="' + x.icon + '" data-name="' + btoa(unescape(encodeURIComponent(x.name))) + '"/></i>\n' : '') +


                    (x.buttons.conf ? '<i title="Configuracion" class="ft-settings fa-tsettings" data-click="ListSetting" data-warehouse="' + x.id + '" data-for="' + x.icon + '" data-name="' + btoa(unescape(encodeURIComponent(x.name))) + '"/></i>\n' : '') +
                    
                
                    // (x.buttons.buy && tree.permission && !tree.enabledFoxcon ? '<i title="Compras" class="ft-shopping-cart fa-tsale" data-click="ListShoppingInventory" data-warehouse="' + x.id + '" data-for="' + x.icon + '" data-name="' + btoa(unescape(encodeURIComponent(x.name))) + '"/></i>\n' : '') +
                    
                    
                    (x.buttons.add ? '<i title="Agregar bodega" class="ft-plus-circle" data-click="warehouseForm" data-parent="' + x.id + '" data-for="' + x.icon + '"/>\n' : '') +
                    (x.buttons.delete ? '<i title="Eliminar bodega" class="ft-delete"  data-click="openFormDelete" data-id="' + x.id + '" data-for="' + x.icon + '" data-type="' + type + '"/>\n' : '') +
                    (x.buttons.edit ? '<i title="Editar bodega" class="ft-edit-2" data-click="warehouseForm" data-id="' + x.id + '" data-for="' + x.icon + '" data-name="' + btoa(unescape(encodeURIComponent(x.name))) + '"/></i>\n' : '') + ('<i title="Código Foxcon" class="ft-slack" data-click="codeFoxcon" data-id="' + x.id + '" data-for="' + x.icon + '" /></i>\n')+ ((x.id == 1 || x.warehousable_type == "App\\Headquarter") ? '<i title="Configuración Foxcon" class="icon-wrench" data-click="settingFoxconForm" data-warehouse="' + x.id + '" data-for="' + x.icon + '" /></i>\n' : '')
                    '</div>';
            }

            /*Variable que almacena el nombre de cada vista*/
            if (name_views_change == "view_types_form") {
                obj['name'] = "<span class='tree-name-span' id='warehouse_"+x.id+"'>" + obj['name'] + "</span>" + element_actions;
            } else if (obj['type'] == "item") {
                obj['name'] = "<span class='tree-name-span'>" + obj['name'] + "</span>" + element_actions;

            }
            return obj;
        });
    },
    getServerData: function(term) {
        return $.ajax({
            url: ULR + "inventory/tree?term=" + term,
            method: "GET",
            data: {},
            dataType: "json"
        });
    },
    filter: function(filter, value) {
        if (typeof(Storage) !== "undefined") {
            if ($('.Mailbox-list').hasClass("Mailbox-list--slideLeft") && localStorage.getItem("expand") == "true") {
                $("#mailbox-expand > a").trigger("click");
            }
        }
        $("[data-view='reg_list']").html("<div class='system_loading' />");
        var cond = {};
        cond[filter] = [value];
        $.ajax({
            url: SP,
            type: 'post',
            data: { _c: 'product', _v: 'reg_list', cond: cond, _type: 'json' },
            success: function(r) {
                showNotifications(r.notifications);
                $("[data-view='reg_list']").html(r.views['reg_list']);
            }
        });
    },
    reset: function(id, term = '') {
        $('#' + id).unbind().removeData();
        $('#' + id).html('<div id="FlatTree4" class="tree"><div class="tree-folder" style="display:none;"><div class="tree-folder-header"><i class="fa fa-chevron-down tree-icon-adjustement"></i><i class="ft-folder" style="font-size: 14px;color:#d6d6d6;"></i><div class="tree-folder-name"></div></div><div class="tree-folder-content"></div><div class="tree-loader" style="display:none"></div></div><div class="tree-item" style="display:none;"><i class="tree-dot"></i><div class="tree-item-name"></div></div></div>');
        delete($('#' + id).data().tree);
        $('.filebrowser').tree('reload');
        tree.init(term);
    },
    setIcon: function(type) {
        let icon = "";
        switch (type) {

            case 'main':
                icon = "ft-globe";
                break;
            case 'headquarter':
                icon = "ft-map-pin";
                break;
            case 'sharedMechasnims':
                icon = "la la-share-alt";
                break;
            case 'cellar':
                icon = "icon-social-dropbox";
                break;
            case 'technical':
                icon = "ft-user";
                break;
            case 'customer':
                icon = 'la la-building';
                break;
            default:
                icon = "icon-social-dropbox";
                break;

        }
        return "<i class='" + icon + "' style='color: #3c9762;'></i> ";
    },
    setModalTitle: function(type) {
        console.log("type: " + type);
        let title = "";
        switch (type) {

            case 'main':
                title = "nueva sede";
                break;
            case 'office':
                title = "nueva bodega";
                break;
            case 'warehouse':
                title = "vinculación de técnico";
                break;
            case 'technical':
                title = "entrega de inventario";
                break;
            default:
                title = "Palmera Junior";
                break;

        }
        return title;
    }
};

//funciones para controlar el llamado de las acciones para cada tipo de actividad

//funcion eliminar
function deleteType(id, element) {
    if (typeof loader == 'object') loader.show();
    else {}
    var data = $("#form-type-action").serializeArray();
    data.push({ name: "id", value: id }, { name: "_type", value: "ajax" }, { name: "tsig", value: "api" }, { name: "json", value: "1" });
    $.ajax({ url: SP, type: 'POST', data: data, dataType: 'json' }).done(function(data) {
        toastr.options.timeOut = 7000;
        showNotifications(data.notifications);
        if (data.notifications[0][1] == 2) {
            $(element).closest(".tree-" + element.dataset.type).remove();
        } else {
            console.log("tree.js: No se realizó la query");
        }

        if (typeof loader == 'object') loader.hide();
        else {}
    });
}

$('body').on('keypress', 'input#search', function(event) {
    localStorage.setItem('inventory_term',$(this).val());
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if (keycode == '13') {
        tree.reset('FlatTree4', $(this).val());
    }
});


//cuando Jquery se encuentre completamente cargado
$(document).ready(function() {
    tree.init('', true);

    $(".select2").select2({
        width: '100%',
        tags: true
    });
});

$(document).on("click", "[data-event='change_view']", function() {
    var data_href = $("input[name='_viewtypesform']").val();
    window.location.href = data_href;
});

$(document).on("keyup", "[name='_filter_name_type']", function() {
    var text_filter = $(this).val().toLowerCase();
    if (text_filter != "") {
        var cont = 0;
        $(".tree-item").css("display", "none");
        $(".tree-item-name").each(function() {
            var elemento = $(this);
            var tree = $.trim($(this).text()).toLowerCase();
            if (tree.indexOf(text_filter) >= 0) {
                $(elemento).closest(".tree-item").css("display", "block");
                cont = cont + 1;
            }
        });
        $("#_filter_name_type_cont").text("Registros encontrados : " + cont);
    }
});