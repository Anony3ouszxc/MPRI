$(document).ready(function(){
    if( typeof(cookie)=='object' && typeof(cookie.init) == 'function' ){
        cookie.init();
    }
    userModule.init();
    backend.init();

});

var userModule = (function(){
    var profile = {
        'name' : 'auiz'
    };

    init = function(){
        $.get('/admin/getProfile',{}, function(rs){
            setProfile(rs);
        },'json');
    }

    setProfile = function(user){
        profile = user;
        // set flag current page
        modal = $('#modal-confirm-delete');
        table = modal.find('.modal-header').data('table');
        profile.currentPage = table;
        return profile;
    }

    getProfile = function(){
        return profile;
    }


    return {
        getProfile : getProfile,
        init: init
    };
})(jQuery);

var backend = {
    init: function(){
        var self = this;
        self.initParam();
        self.initTree();
        self.initLayout();
        self.initForm();
        self.initSave();
        self.initDelete();
        self.initEdit();
        self.initActive();
    },
    initParam: function(){
        var self = this;
        self.baseUrl = $("body").data("base-url");
    },
    initLayout: function(){
        $("#formLayout").on("click", "#btn-add-row", function(e){
            e.preventDefault();

            var layout     = $("[name=select-layout]:checked").val(),
                setLayout  = layout.split("-"),
                html       = '',
                config     = '',
                rowIndex   = $(".row-content").size() ? ($(".row-content:last" ).data('index') + 1) : 0,
                rowName    = 'data[content]['+rowIndex+']',
                // option     = "{height: 200,toolbar: [['fontsize', ['fontsize']],['style', ['bold', 'italic', 'underline', 'clear']],['insert', ['link', 'picture', 'hr']],['color', ['color']],['para', ['ul', 'ol', 'paragraph']]]}",
                option     = "{height: 200}";
                textEditor = '<textarea ui-jp="summernote" ui-options="'+option+'" class="form-control" rows="4" name="'+rowName+'[content][]" required></textarea>';
                actionIcon = '<i class="material-icons md-24 text-danger delete-layout"></i>'
                             +'<i class="material-icons md-24 move-up" style="position: absolute;margin-left: -30px;top: 24px;cursor:pointer;"></i>'
                             +'<i class="material-icons md-24 move-down" style="position: absolute;margin-left: -30px;top: 48px;cursor:pointer;"></i>';
            $.each(setLayout, function(i,v){
                config += '<input type="hidden" name="'+rowName+'[config][]" value="'+v+'">';
                html   += '<div class="col-md-'+v+'">';
                if( i==0 ){
                    html += actionIcon;
                }
                html   += '<div class="box p-a">'+textEditor+'</div></div>';
            });
            html      = '<div class="row form-group row-content" data-index="'+rowIndex+'">'+html+config+'</div>';
            $(".layout-builder").append(html);

            $(".delete-layout")
            .css('position','absolute')
            .css('margin-left','-30px')
            .css('margin-top', '0')
            .css('cursor', 'pointer');
            $(".delete-layout").closest("div").css("padding-left",'30px');
            if( $(".row-content" ).size() >= 3 ){
                $("#add-layout").addClass('hide');
            }
            $('[ui-jp]').uiJp();
        });
        $("#form-layout").on('click', '.delete-layout', function(e){
            e.preventDefault();
            $(this).closest('div.row.row-content').remove();
            $("#add-layout").removeClass('hide');
        }).on("click",".move-up", function(e){
            e.preventDefault();
            var currentRow = $(this).closest('.row-content'),
                rowIndex   = currentRow.data("index");
            alert("up " + rowIndex+ ", " + getMinIndex());
            if( rowIndex > getMinIndex() ){
                var prevRow   = currentRow.prev(".row-content"),
                    prevIndex = prevRow.data("index");
                    updateIndex(currentRow, prevIndex);
                    updateIndex(prevRow, rowIndex);
                    currentRow.detach().insertBefore(prevRow);
                    // currentRow.css("border", "1px dotted #eee");
            }
        }).on("click",".move-down", function(e){
            e.preventDefault();
            var currentRow = $(this).closest('.row-content'),
                rowIndex   = currentRow.data("index");
            alert("down " + rowIndex + ", " + getMaxIndex());
            if( rowIndex < getMaxIndex() ){
                var nextRow  = currentRow.next(".row-content"),
                    nextIndex = nextRow.data("index");
                    updateIndex(currentRow, nextIndex);
                    updateIndex(nextRow, rowIndex);
                    currentRow.detach().insertAfter(nextRow);
                    // currentRow.css("border", "1px dotted #eee");
            }

        });

        var updateIndex = function(element, index){
            element.find("textarea.form-control").each(function(){
                $(this).attr("name", "data[content]["+index+"][content][]");
            });
            element.find("input[type='hidden']").each(function(){
                $(this).attr("name", "data[content]["+index+"][config][]");
            });
            element.data("index", index);
        };
        var getMaxIndex = function(){
            var max = 0;
            $(".layout-builder .row-content").each(function(){
                max = max < $(this).data("index")
                      ? $(this).data("index")
                      : max;
            });
            return max;
        };
        var getMinIndex = function(){
            var min = 0;
            $(".layout-builder .row-content").each(function(){
                if( !min ){
                    min = $(this).data("index");
                }
                min = min > $(this).data("index")
                      ? $(this).data("index")
                      : min;
            });
            return min;
        }
    },
    initEdit: function(){
        $("[id=btn-edit]").on('click', function(e){
            e.preventDefault();
            url = $(this).data('target');
            window.location.href = url;
        });
    },
    initDelete: function(){
        var self = this;
        $("[id=btn-delete]").on('click', function(e){
            e.preventDefault();
            rowId = $(this).closest('tr').data('id');
            modal = $('#modal-confirm-delete');
            if( modal.size() ){
                msg = modal.find(".modal-body").data("confirm") + rowId +'?';
                modal.find(".modal-body").empty().append(msg);
                modal.find('.modal-header').data('id', rowId );
                modal.modal('show');
            }
        });
        $("#btn-confirm-delete").on("click", function(e){
            e.preventDefault();

            modal = $('#modal-confirm-delete');
            rowId = modal.find('.modal-header').data('id');
            table = modal.find('.modal-header').data('table');
            if( table && rowId ){
                $.post(self.baseUrl+'api/delete', { table: table, id: rowId }, function(rs){
                    if( rs.success ){
                        $.notify(rs.message,{
                            type: 'success',
                            allow_dismiss: false,
                            animate: {
                                enter: 'animated bounceInDown',
                                exit: 'animated bounceOutUp'
                            }
                        });
                    } else {
                        $.notify(rs.message,{
                            type: 'danger',
                            allow_dismiss: false,
                            animate: {
                                enter: 'animated bounceInDown',
                                exit: 'animated bounceOutUp'
                            }
                        });
                    }
                    setTimeout(function(){window.location.reload(1);}, 1000);
                },'json');
            } else {
                $.notify(modal.find('.modal-footer').data('error'),{
                    type: 'danger',
                    allow_dismiss: false
                });
                modal.modal('hide');
            }
        });
    },
    initSave: function(){
        $("#btn-save-form").on('click', function(e){
            e.preventDefault();

            if($("#page-map-manu").size()){
                backend.saveMapMenu();
            }
            if($("#form-update-profile").size()){
                $("#form-update-profile").trigger("submit");
            }
        });
    },
    initForm: function(){
        var self = this;
        $("#embed-form").on('submit', function(e, options){
            if( options == undefined ){
                e.preventDefault();
                text = $("textarea").val();
                $("textarea").val( b64EncodeUnicode(text) );
                $(e.currentTarget).trigger(e.type, { 'encoded': true });
            }
        });
        // $("#form-update-profile").on('submit', function(e){
        //     e.preventDefault();
        //     self.updateProfile();
        // });
    },
    initTree: function(){
        var self = this;
        $('#gtreetable').gtreetable({
            'draggable' : false,
            'types': { 'default': 'glyphicon glyphicon-folder-open'},
            "manyroots" : true,
            "cache" : 2,
            "showExpandIconOnEmpty" : true,
            'source': function (id) {
                return {
                    type: 'POST',
                    url: self.baseUrl+'api/nodeMenu',
                    data: { 'id': id, 'level' : $(".node[data-id="+id+"]:first").data('level') },
                    dataType: 'json',
                    error: function(XMLHttpRequest) {
                        alert(XMLHttpRequest.status+": "+XMLHttpRequest.responseText);
                    }
                }
            },
            'onSave':function (oNode) {
                return {
                    type: 'POST',
                    url: !oNode.isSaved() ? self.baseUrl+'api/nodeCreate' : self.baseUrl+'api/nodeUpdate?id=' + oNode.getId(),
                    data: {
                        parent: oNode.getParent(),
                        name: oNode.getName(),
                        position: oNode.getInsertPosition(),
                        related: oNode.getRelatedNodeId()
                    },
                    dataType: 'json',
                    error: function(XMLHttpRequest) {
                        alert(XMLHttpRequest.status+": "+XMLHttpRequest.responseText);
                    }
                };
            },
            'onDelete':function (oNode) {
                if( oNode.getId() == 0 ){ return false; }
                return {
                    type: 'POST',
                    url: self.baseUrl+'api/nodeDelete',
                    dataType: 'json',
                    data: {
                        id : oNode.getId()
                    },
                    error: function(XMLHttpRequest) {
                        alert(XMLHttpRequest.status+": "+XMLHttpRequest.responseText);
                    }
                };
            },
            'onMove': function (oSource, oDestination, position) {
                // return true;
                return {
                    type: 'POST',
                    url: self.baseUrl+'api/nodeMove',
                    data: {
                        id : oSource.getId(),
                        related: oDestination.getId(),
                        position: position
                    },
                    dataType: 'json',
                    error: function(XMLHttpRequest) {
                        alert(XMLHttpRequest.status+": "+XMLHttpRequest.responseText);
                    }
                };
            }
        });

        $('.tree').treegrid({
            expanderExpandedClass: 'glyphicon glyphicon-minus',
            expanderCollapsedClass: 'glyphicon glyphicon-plus',
            indentTemplate : '<span class="treegrid-indent"></span>'
        });
    },
    saveMapMenu: function(){
        var self = this;
        var different = $('select,input:text').filter(function(){
            return $(this).attr('data-default') != $(this).val();
        });
        if( different.length ){
            params = different.serializeArray();
            $.post(self.baseUrl+'main/update-menu', params,function(rs){
                if( rs.success ){
                    var notify = $.notify(rs.message, {
                        type: 'success',
                        allow_dismiss: false
                    });
                } else {
                    var notify = $.notify(rs.message, {
                        type: 'danger',
                        allow_dismiss: false
                    });
                }
            }, 'json');
        }
    },

    updateProfile: function(){
        var self = this;
        var params = $("#form-update-profile").serializeArray();
        $.post(self.baseUrl+"user/update-profile", params, function(rs){
            if( rs.success ){
                var notify = $.notify(rs.message, {
                    type: 'success',
                    allow_dismiss: false
                });
                // clear input password form prevent use same state
                $("[name*=password").val('');
            } else {
                var notify = $.notify(rs.message, {
                    type: 'danger',
                    allow_dismiss: false
                });
                // clear old password
                $("[name*=old_password").val('');
            }
        },'json');
    },

    initActive: function(){
        $("a.active-status").on("click", function(e){
            e.preventDefault();
            icon    = $(this);
            rowTr   = $(this).closest('tr');
            id      = rowTr.data('id');
            profile = userModule.getProfile();
            if( profile.currentPage && profile.role[profile.currentPage +'_publish'] != 'on' && !profile.isAdmin ){
                return false;
            } else {
                $.post('/admin/publish/', { table : profile.currentPage, id : id }, function(rs){
                    icon.toggleClass('active');
                }, 'json');
                // $.notify({
                //     // options
                //     title: 'Bootstrap notify',
                //     message: 'Turning standard Bootstrap alerts into "notify" like notifications',
                // },{
                //     element: 'body',
                //     allow_dismiss: true,
                //     // position: 'absolute',
                //     placement: {
                //         from: "top",
                //         align: "center"
                //     },
                //     // offset: {y:10, x:100},
                //     delay: 0,
                //     icon_type: 'class',
                //     animate: {
                //         enter: 'animated fadeInDown',
                //         exit: 'animated fadeOutUp'
                //     },
                //     template:
                //         '<div class="modal-content box-shadow-md m-b">'+
                //             '<div class="modal-header">'+
                //                 '<h5 class="modal-title">Modal!</h5>'+
                //             '</div>'+
                //             '<div class="modal-body">'+
                //                 '<p>Modal content</p>'+
                //             '</div>'+
                //             '<div class="modal-footer">'+
                //                 '<button class="btn dark-white" id="close-modal-active" data-notify="dismiss">x</button>'+
                //                 '<button class="btn primary" id="toggle-active">OK</button>'+
                //             '</div>'+
                //         '</div>'
                // });
            }
            return false;
        });
    },

}

function b64EncodeUnicode(str) {
    return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g, function(match, p1) {
        return String.fromCharCode('0x' + p1);
    }));
}

function b64DecodeUnicode(str) {
    return decodeURIComponent(Array.prototype.map.call(atob(str), function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));
}