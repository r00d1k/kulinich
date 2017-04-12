
var Assetmanager = {

    scObject:           new String(),
    scField:            new String(),
	docIcon:			new String('/www/assets/global/js/editor/assetmanager/ico-doc.jpg'),
    requestUrl:         new String('/util/asset/manage/'),
    uploadUrl:          new String('/util/asset/upload/'),
    deleteUrl:          new String('/util/asset/delete/'),
    deleteFolderUrl:    new String('/util/asset/delete-category/'),
    createUrl:          new String('/util/asset/create-category/'),
    synchUrl:           new String('/util/asset/synch/'),
    requestType:        new String('POST'),

    folders:            new Array(),
    files:              new Array(),

    parent:             new String('0'),
    parentAsString:     false,
    pages:              0,
    page:               1,
    top:               -1,

    itemWidth:          100,
    itemHeight:         100,

    selectedItemHash:   new String(),
    selectedFolderID:   -1,

    selectedItemURL:    new String(),
    hightlightBg:       new String('#FFFFCC'),
    defaultBg:          new String('#E9E8F2'),

    idList:             new String('am_list'),
    idPreview:          new String('am_preview'),
    idPager:            new String('am_pager'),
    idFolderInput:      new String('am_folder_name'),
    idCreateFolder:     new String('am_create_btn'),
    idSynchBtn:         new String('am_synch_btn'),

    defaultFolder:      new String('Enter folder name here'),
    folderCreated:      false,

    messageNothingSelected: new String('Nothing was selected!'),
    messageConfirmDelete:   new String('Are you sure you want delete selected '),
    messageEnterFolderName: new String('Please enter new folder name'),
    messageCreateError:     new String('Can`t create folder '),

    init: function()
    {
        //this.navigationFocus();
        if (this.parentAsString)
        {
            this.load('',1);
        }
        else
        {
            this.load(0,1);
        }

        if (this.scObject != '' && this.scField != '' && this.uploadUrl.indexOf('scaffoldname') == -1)
        {
            this.uploadUrl += 'scaffoldname/' + this.scObject + '/scaffoldfield/' + this.scField + '/';
            this.synchUrl += 'scaffoldname/' + this.scObject + '/scaffoldfield/' + this.scField + '/';
        }

        new AjaxUpload(
                            'am_upload_btn',
                            {
                                action: Assetmanager.uploadUrl,
                                name: 'asset',
                                onSubmit: function()
                                {
                                    this.setData({'am_category': Assetmanager.parent});
                                },
                                onComplete: function()
                                {
                                    Assetmanager.load(Assetmanager.parent, 1);
                                }
                            }
        );


        var select = this.navigationGetSelect();
        select.onkeyup = function( evt ){
            evt = window.event || evt;
            keyCode = evt.charCode || evt.keyCode;
            switch (keyCode)
            {
                case 37://arrow left
                case 39://arrow right
                case 40://arrow down
                case 38://arrow up
                    Assetmanager.onArrowPressed();
                    break;
                case 13://Enter
                    Assetmanager.onEnterPressed();
                    break;
                case 46://del
                case 119://F8
                    Assetmanager.onDeletePressed();
                    break;
            }
        }

        $("#"+this.idFolderInput).attr('value', this.defaultFolder);
        $("#"+this.idFolderInput).click( function() { Assetmanager.onFolderInputClick() } );
        $("#"+this.idFolderInput).blur( function() { Assetmanager.onFolderInputBlur() } );

        $("#"+this.idCreateFolder).click( function() { Assetmanager.onCreateFolderClick() } );
        $("#"+this.idSynchBtn).click( function() { Assetmanager.onSynchBtnClick() } );
    },

    onFolderInputClick: function()
    {
        var value = $("#"+this.idFolderInput).attr('value');
            value = value == this.defaultFolder ? '' : value;
            $("#"+this.idFolderInput).attr('value', value);
    },

    onFolderInputBlur: function()
    {

        var value = $("#"+this.idFolderInput).attr('value');
            value = value == '' ? this.defaultFolder : value;
            $("#"+this.idFolderInput).attr('value', value);

    },

    onCreateFolderClick: function()
    {
        var value = $("#"+this.idFolderInput).attr('value');
            if (value != '' && value != this.defaultFolder)
            {
                this.createFolder(value);
            }
            else
            {
                alert(this.messageEnterFolderName);
            }
    },

    onSynchBtnClick: function()
    {
        this.synchFolder();
    },

    createFolder: function(name)
    {
        this.folderCreated = false;
        var _data = 'parent=' + encodeURIComponent(this.parent) + "&name=" + name;

        if (this.scObject != '' && this.scField != '' && this.createUrl.indexOf('scaffoldname') == -1)
        {
            this.createUrl += 'scaffoldname/' + this.scObject + '/scaffoldfield/' + this.scField + '/';
        }

        var _options = {

            url:    Assetmanager.createUrl,
            type:   Assetmanager.requestType,
            data:   _data,

            success:function(response)
            {
                eval(response);
                if ( Assetmanager.folderCreated )
                {
                    Assetmanager.load(Assetmanager.parent, Assetmanager.page);
                    $("#"+Assetmanager.idFolderInput).attr('value', Assetmanager.defaultFolder);
                }
                else
                {
                    alert(Assetmanager.messageCreateError + $("#"+Assetmanager.idFolderInput).attr('value'));
                }
            }
        };

        jQuery.ajax(_options);
    },

    onDeletePressed: function()
    {
        this.onBtnDeleteClick();
    },

    onArrowPressed: function()
    {

        var value = this.navigationGetSelected();
        if (value != null)
        {
            if (isNaN(value))
            {
                //file
                this.selectedFolderID = -1;
                this.onItemClick(value);
            }
            else
            {
                //folder
                this.selectedItemURL    = '';
                this.selectedItemHash   = '';
                $("#"+this.idPreview+" > *").css('background-color', this.defaultBg);
                this.selectedFolderID = value;
            }
        }
    },

    onEnterPressed: function()
    {
        var value = this.navigationGetSelected();
        if (value != null)
        {
            if (isNaN(value))
            {
                //file
                this.onItemDblClick(value);
            }
            else
            {
                //folder
                this.selectedItemURL    = '';
                this.selectedItemHash   = '';
                $("#"+this.idPreview+" > *").css('background-color', this.defaultBg);
                this.load(value, 1);
            }
        }
    },

    load: function(parent, page)
    {

        this.selectedItemHash = '';
        this.selectedFolderID = -1;
        parent = new String(parent);
        try
        {
            parent = isNaN(parent) ? '0' : parent.replace('___', '/');
        }
        catch (e)
        {
            parent = '0';
        }
       
        this.selectedItemURL = '';
        this.parent = parent;

        var _data = 'parent=' + encodeURIComponent(parent) + '&page=' + page;

        if (this.scObject != '' && this.scField != '' && this.requestUrl.indexOf('scaffoldname') == -1)
        {
            this.requestUrl += 'scaffoldname/' + this.scObject + '/scaffoldfield/' + this.scField + '/';
        }

        var _options = {

            url:    Assetmanager.requestUrl,
            type:   Assetmanager.requestType,
            data:   _data,

            success:function(response)
            {
                eval(response);
                Assetmanager.render();
            }
        };

        jQuery.ajax(_options);

    },


    render: function()
    {
        $("#"+this.idPreview).empty();
        if (this.page < 2)
        {
            this.renderNavigation();
        }
        this.renderPager();
        this.renderPreview();
    },


    renderNavigation: function()
    {
        var html = '';
        var option = null;
        var list = $("#"+this.idList);
        $('#am_selectable').selectable('destroy');
        list.html('<ol id="am_selectable"></ol>');
        if (this.top >= 0)
        {
            var item = $('<li class="ui-widget-content am_folder" id="amf_'+this.top+'" val="'+this.top+'">..</li>');
            $('#am_selectable').append(item);
        }
        for (var i = 0; i < this.folders.length; i++)
        {
            var item = $('<li class="ui-widget-content am_folder" id="f_'+this.folders[i].id+'" val="'+(!isNaN(this.folders[i].id) ? this.folders[i].id : this.folders[i].id.replace('/', '___'))+'"><div class="am_folder_title">[' + this.folders[i].name + ']</div><!--div class="am_folder_delete"><input type="checkbox" id="d_'+this.folders[i].id+'" value="'+this.folders[i].id+'" class="am_delete_box"/></div--></li>');
            $('#am_selectable').append(item);
        }

        $('#am_selectable').selectable({
            selected: function(event, ui) { 
                Assetmanager.load($(ui.selected).attr('val'), 1);
            }
        });
    },


    renderPreview: function()
    {
        for (var i = 0; i < this.files.length; i++)
        {
            $(this.renderItem(this.files[i])).appendTo("#"+this.idPreview);
        }
    },

    urlOrHash: function(hash)
    {
        var url = '';
        for (var i = 0; i < this.files.length; i++)
        {
            if (this.files[i]['hash'] == hash)
            {
                //CDN url
                url = this.directUrl(hash);
                if (url == '')
                {
                    url = undefined == this.files[i]['url'] ? this.generateURL(hash) : this.files[i]['url'];
                }
            }
        }
        if (url == '')
        {
            this.generateURL(hash);
        }
        return url;
    },

    directUrl: function(hash)
    {
        var url = '';
        for (var i = 0; i < this.files.length; i++)
        {
            if (this.files[i]['hash'] == hash)
            {
                url = undefined == this.files[i]['directurl'] ? '' : this.files[i]['directurl'];
            }
        }
        return url;
    },

    renderItem: function(item)
    {
    	var html = "";
    	switch (item['type'])
    	{
    		case 'image':
    			html = this.renderImg(item);
    			break;
    		case 'flash':
    			html = this.renderFlash(item);
    			break;
    		default:
    			html = this.renderDocument(item);
    			break;
    	}
        return html;
    },

    renderDocument: function(item)
    {
        var html = "<div class='am_item' id='item_" + item['hash'] + "' width='150' height='150'><div class='am_thumb'>";
        var url  = undefined == item['url'] ? this.generateURL(item['hash'], this.itemWidth, this.itemHeight) : item['url'];
        html    += "<img src='" + url + "' ";
        html    += "onclick=\"Assetmanager.onItemClick('" + item['hash'] + "')\" ";
        html    += "ondblclick=\"Assetmanager.onItemDblClick('" + item['hash'] + "')\" ";
        html    += "id='" + item['hash'] + "' /></div>" + item['name'];
        html    += "</div>";
        return html;
    },

    renderFlash: function(item)
    {
        var html = "<div class='am_item' id='item_" + item['hash'] + "' width='150' height='150'><div class='am_thumb' id='" + item['hash'] + "' onclick=\"Assetmanager.onItemClick('" + item['hash'] + "')\" ondblclick=\"Assetmanager.onItemDblClick('" + item['hash'] + "')\">";
        var url  = undefined == item['url'] ? this.generateURL(item['hash'], this.itemWidth, this.itemHeight) : item['url'];
        html    += "<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0' width='" + this.itemWidth + "' height='" + this.itemHeight + "'>";
        html 	+= "<param name='movie' value='" + url + "' />";
        html	+= "<param name='wmode' value='transparent' />";
        html	+= "<embed src='" + url + "' wmode='transparent' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash' width='" + this.itemWidth + "' height='" + this.itemHeight + "'></embed>";
        html	+= "</object>";
        html    += "</div>" + item['name'];
        html    += "</div>";
        return html;
    },

    renderImg: function(item)
    {
        var html = "<div class='am_item' id='item_" + item['hash'] + "'";
        html    += "onclick=\"Assetmanager.onItemClick('" + item['hash'] + "')\" ";
        html    += "ondblclick=\"Assetmanager.onItemDblClick('" + item['hash'] + "')\">";
        html    += "<div class='am_title'>" + item['name'] + '</div>';
        html    += "<div class='am_thumb'>";
        var url  = undefined == item['url'] ? this.generateURL(item['hash'], this.itemWidth, this.itemHeight) : item['url'];
        
        html    += "<img style='max-height:100px;max-width:100px' class='am_thumb_image' src='" + url + "' ";
        html    += "onclick=\"Assetmanager.onItemClick('" + item['hash'] + "')\" ";
        html    += "ondblclick=\"Assetmanager.onItemDblClick('" + item['hash'] + "')\" ";
        html    += "id='" + item['hash'] + "' /></div>";
        html    += "</div>";
        return html;
    },

    renderPager: function()
    {
        $("#"+this.idPager).empty();
        if (this.pages > 1)
        {
            var html = "<span>Pages:</span> ";
            for (var i = 1; i <= this.pages; i++)
            {
                if (i != this.page)
                {
                    html += "<span class='am_pager_item' onclick='Assetmanager.load(\"" + this.parent + "\"," + i + ");'>" + i + "</span>";
                }
                else
                {
                    html += "<strong>" + i + "</strong>";
                }
                if (i != this.pages)
                {
                    html += "&nbsp;|&nbsp;";
                }
            }
            $(html).appendTo("#"+this.idPager);
        }
    },


    navigationGetSelect: function()
    {
        return $("#"+this.idList)[0];
    },

    navigationFocus: function()
    {
        //var select = this.navigationGetSelect();
        //select.focus();
    },

    navigationUnselectAll: function()
    {
        //var select = this.navigationGetSelect();
        //for (var i = 0; i < select.options.length; i++)
        //{
        //    select.options[i].selected = false;
        //}
        $('.am_delete_box').attr('checked', false);
    },

    navigationSelect: function(hash)
    {
        //this.navigationUnselectAll();
        //var select = this.navigationGetSelect();
        /*for (var i = 0; i < select.options.length; i++)
        {
            if (select.options[i].value == hash)
            {
                select.options[i].selected = true;
            }
        }*/

    },

    navigationGetSelected: function()
    {
        /*var select = this.navigationGetSelect();
        var value  = null;
        for (var i = 0; i < select.options.length; i++)
        {
            if (select.options[i].selected)
            {
                value = select.options[i].value;
            }
        }
        return value;*/
    },

    generateURL: function(hash, width, height)
    {
        var url = "/util/asset/thumbnail/item/" + hash + "/";
        if (width != undefined)
        {
            url += "width/" + width + "/";
        }
        if (height != undefined)
        {
            url += "height/" + height + "/";
        }
        return url;
    },

    onItemClick: function(hash)
    {
        this.selectedItemURL    = this.urlOrHash(hash);
        this.selectedItemHash   = hash;
        this.hightlight(hash);
        this.navigationSelect(hash);
    },

    onItemDblClick: function(hash)
    {
        this.selectedItemURL = this.urlOrHash(hash);
        this.hightlight(hash);
        this.onBtnOkClick();
    },

    onBtnOkClick: function()
    {
        if (this.selectedItemURL != '')
        {
            if(navigator.appName.indexOf('Microsoft')!=-1)
            {
                //window.returnValue = this.selectedItemURL;
                openerWin.setAssetValue(this.selectedItemURL);
            }
            else
            {
                //window.opener.setAssetValue(this.selectedItemURL);
                openerWin.setAssetValue(this.selectedItemURL);
            }
            window.close();
        }
        else
        {
            alert(this.messageNothingSelected);
        }
    },

    onBtnDeleteClick: function()
    {
        if (this.selectedItemHash != '')
        {
            if ( confirm(this.messageConfirmDelete + 'file'))
            {
                this.deleteAsset(this.selectedItemHash);
            }
        }
        else if (this.selectedFolderID != -1)
        {
            if ( confirm(this.messageConfirmDelete + 'folder'))
            {
                this.deleteFolder(this.selectedFolderID);
            }
        }
        else
        {
            alert(this.messageNothingSelected);
        }

    },

    hightlight: function(hash)
    {
        $("#"+this.idPreview+" > *").css('background', this.defaultBg);
        $("#"+this.idPreview+" > *").css('background-color', this.defaultBg);
        $("#item_"+hash).css('background-color', this.hightlightBg);
        $("#item_"+hash).css('background', this.hightlightBg);
        //$("#item_"+hash).effect('highlight');
    },

    //delete asset
    deleteAsset: function(hash)
    {
        var _data = 'hash=' + hash + '&directurl=' + encodeURIComponent(this.directUrl(hash));

        if (this.scObject != '' && this.scField != '' && this.deleteUrl.indexOf('scaffoldname') == -1)
        {
            this.deleteUrl += 'scaffoldname/' + this.scObject + '/scaffoldfield/' + this.scField + '/';
        }

        var _options = {
            url:    Assetmanager.deleteUrl,
            type:   Assetmanager.requestType,
            data:   _data,
            success:function(response)
            {
                eval(response);
                Assetmanager.load(Assetmanager.parent, Assetmanager.page);
            }
        };
        jQuery.ajax(_options);
    },

    //delete folder
    deleteFolder: function(id)
    {
        if (isNaN(id))
        {
            id = id.replace('___', '/');
        }
        var _data = 'id=' + encodeURIComponent(id);

        if (this.scObject != '' && this.scField != '' && this.deleteFolderUrl.indexOf('scaffoldname') == -1)
        {
            this.deleteFolderUrl += 'scaffoldname/' + this.scObject + '/scaffoldfield/' + this.scField + '/';
        }

        var _options = {
            url:    Assetmanager.deleteFolderUrl,
            type:   Assetmanager.requestType,
            data:   _data,
            success:function(response)
            {
                eval(response);
                Assetmanager.load(Assetmanager.parent, Assetmanager.page);
            }
        };
        jQuery.ajax(_options);
    },

    synchFolder: function()
    {
        var _data = 'folder=' + encodeURIComponent(this.parent);
        var _options = {
            url:    Assetmanager.synchUrl,
            type:   Assetmanager.requestType,
            data:   _data,
            success:function(response)
            {
                eval(response);
                Assetmanager.load(Assetmanager.parent, Assetmanager.page);
            }
        };
        jQuery.ajax(_options);
    }

};