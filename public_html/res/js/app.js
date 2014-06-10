/**************  FIELDS-PIPE   ***************/
(function($){
    $.fn.pipeFields = function(options)
    {
        function pipeField(element,options)
        {
            this.variables = options.fields;
            this.element = $(element);
            this.wrapper = null;
            this.toglier = null;
            this.selector = null;
            this.build = function()
            {
                this.element.wrap('<div class="pipe-wrapper" style="position: relative;"></div>');
                this.wrapper = this.element.parents('.pipe-wrapper').first();
                this.toglier = $('<a class="icon-plus-sign" title="'+options.label+'" href="javascript:void(0)"></a>').appendTo(this.wrapper);
                this.toglier
                    .data('pipe', this)
                    .css({
                        position: 'absolute',
                        left: -(this.toglier.outerWidth()/2),
                        top: -(this.toglier.outerHeight()/2)
                    });
                this.toglier.click(function(event){
                    event.preventDefault();
                    $(this).data('pipe').toggleSelector();
                    return false;
                });

                this.selector = $('<ul class="pipe-selector"></ul>');
                for(var i in this.variables)
                {
                    if(typeof(this.variables[i]) != 'function')
                    {
                        $('<li><a href="#" class="pipe-item">'+this.variables[i]+'</a></li>').appendTo(this.selector);
                    }
                }
                this.selector
                    .appendTo(this.wrapper);
            };

            this.toggleSelector = function()
            {
                if(this.selector.css('display') == 'none')
                {
                    var self = this;
                    this.selector
                        .css({
                            left: (this.toglier.outerWidth()/2),
                            top: (this.toglier.outerHeight()/2)
                        })
                        .show();
                    this.selector.find('li').removeClass('active');
                    $(document).bind('click.hidePipe',function(event){

                        if($(event.target).hasClass('pipe-item'))
                        {
                            self.pipeVariable($(event.target).html());
                            event.preventDefault();
                            self.selector.hide();
                            $(document).unbind('click.hidePipe');
                            $(this).parents('li').removeClass('active');
                            return false;
                        }
                        self.selector.hide();
                        $(document).unbind('click.hidePipe');
                    });
                }
                else
                {
                    this.selector.hide();
                }
            }

            this.pipeVariable = function(variable)
            {
                var inserted = false;
                if (window.getSelection) {
                    sel = window.getSelection();
                    if (sel.rangeCount) {
                        range = sel.getRangeAt(0);
                        range.deleteContents();
                        range.insertNode(document.createTextNode(variable));
                        inserted = true;
                    }
                }
                else if (document.selection && document.selection.createRange) {
                    range = document.selection.createRange();
                    range.text = variable;
                    inserted = true;
                }
                if(!inserted)
                {
                    $(this.element.val(this.element.val()+variable));
                }
            }

            this.build();
        }

        return this.each(function(){
            $(this).data('pipe', new pipeField(this, options));
        })
    }
})(jQuery);

/********   BOTTOM SLIDES   ***********/
(function($){
    $.fn.bottomSlides = function(options)
    {
        return this.each(function(){
            var ul = $(this).find('ul');
            var slides = ul.children('li');
            ul.css('width', slides.length * (slides.first().outerWidth()+parseInt(slides.first().css('margin-right'))));

            $(this).find('.next').click(function(){
                var container = ul.get(0).parentNode;
                var dist = $(container).width() - ul.children('li').outerWidth();
                dist += $(container).scrollLeft();
                $(container).scrollTo({top:'0px', left:(dist+'px')},300);
                return false;
            });

            $('.gallery .prev').click(function(){
                var container = ul.get(0).parentNode;
                var dist = $(container).width() - ul.children('li').outerWidth();
                dist = $(container).scrollLeft() - dist;
                $(container).scrollTo({top:'0px', left:(dist+'px')},300);
                return false;
            })
        });
    }
})(jQuery);

/***************   COMBO-LIST ****************/
(function($){
    function buildList(select)
    {
        var list = $('<ul></ul>');
        var options = $(select).children('option, optgroup');
        for(var i=0; i<options.length; i++)
        {
            if(options[i].tagName.toLowerCase() == 'option')
            {
                var li = $('<li class="combo-list-item"><a href="javascript:void(0)">'+$(options[i]).html()+'</a></li>');
                li
                    .data('option', options[i])
                    .appendTo(list);
                if(options[i].getAttribute('selected'))
                {
                    li.addClass('combo-list-active');
                }
            }
            else if(options[i].tagName.toLowerCase() == 'optgroup')
            {
                var li = $('<li class="combo-list-items"><a href="javascript:void(0)">'+options[i].getAttribute('label')+'</a></li>')
                li
                    .data('group', options[i])
                    .append(buildList(options[i]))
                    .appendTo(list);
            }
        }
        return list;
    }

    function optionClick(li)
    {
        var li = $(li);
        if(li.hasClass('combo-list-item'))
        {
            var option = li.data('option');
            var select = $(option).parents('select').first();
            if(select.attr('multiple'))
            {
                if(li.hasClass('combo-list-active'))
                {
                    option.removeAttribute('selected');
                    li.removeClass('combo-list-active');
                }
                else
                {
                    option.setAttribute('selected', 'selected');
                    li.addClass('combo-list-active')
                }
            }
            else
            {
                select.find('option').each(function(){
                    this.removeAttribute('selected');
                });
                option.setAttribute('selected', 'selected');
                li.parents('ul.combo-list').first().find('li.combo-list-active').removeClass('combo-list-active');
                li.addClass('combo-list-active')
            }
            select.trigger('change');
        }
        else
        {
            var mainLi = li;
            var group = li.data('group');
            var select = $(group).parents('select').first();
            if(select.attr('multiple'))
            {
                var activeNodes = mainLi.find('li.combo-list-active');
                if(activeNodes.length > 0)
                {
                    for(var i=0; i<activeNodes.length; i++)
                    {
                        var li = $(activeNodes[i]);
                        var option = li.data('option');
                        option.removeAttribute('selected');
                        li.removeClass('combo-list-active');
                    }
                    select.trigger('change');
                }
                else
                {
                    var allNodes = mainLi.find('li.combo-list-item');
                    for(var i=0; i<allNodes.length; i++)
                    {
                        var li = $(allNodes[i]);
                        var option = li.data('option');
                        option.setAttribute('selected', 'selected');
                        li.addClass('combo-list-active');
                    }
                    select.trigger('change');
                }
            }
        }
    }

    $.fn.comboList = function(options)
    {
        return this.each(function(){
            if($(this).next().hasClass('.combo-list'))
            {
                $(this).next().remove();
            }
            var comboList = buildList(this);
            comboList
                .addClass('combo-list')
                .find('a').click(function(){
                    optionClick(this.parentNode)
                });
            $(this).hide().after(comboList);
        })
    }
})(jQuery);
/****************   SERIALIZE-OBJECT    *******/
(function($){
    $.fn.serializeObject = function(){
        var self = this,
            json = {},
            push_counters = {},
            patterns = {
                "validate": /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
                "key":      /[a-zA-Z0-9_]+|(?=\[\])/g,
                "push":     /^$/,
                "fixed":    /^\d+$/,
                "named":    /^[a-zA-Z0-9_]+$/
            };

        this.build = function(base, key, value){
            base[key] = value;
            return base;
        };

        this.push_counter = function(key){
            if(push_counters[key] === undefined){
                push_counters[key] = 0;
            }
            return push_counters[key]++;
        };

        $.each($(this).serializeArray(), function(){

            // skip invalid keys
            if(!patterns.validate.test(this.name)){
                return;
            }

            var k,
                keys = this.name.match(patterns.key),
                merge = this.value,
                reverse_key = this.name;

            while((k = keys.pop()) !== undefined){

                // adjust reverse_key
                reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');

                // push
                if(k.match(patterns.push)){
                    merge = self.build([], self.push_counter(reverse_key), merge);
                }

                // fixed
                else if(k.match(patterns.fixed)){
                    merge = self.build([], k, merge);
                }

                // named
                else if(k.match(patterns.named)){
                    merge = self.build({}, k, merge);
                }
            }

            json = $.extend(true, json, merge);
        });
        return json;
    };
})(jQuery);

(function($){
    $.fn.userPicker = function(options){
        if(typeof(options.sourceUrl) == 'undefined')
        {
            console.error('userPickerError:', 'Load Url is not set ');
            return false;
        }
        var requestCache = {

        }
        return $(this).each(function(){
            var self = $(this);
            var picker = $('<input type="text" />')
            picker.attr('class', self.attr('class'));
            $(this)
                .hide()
                .after(picker);
            picker.autocomplete({
                minLength: 2,
                source: function( request, response ) {
                    var term = request.term;
                    if ( term in requestCache ) {
                        response( requestCache[ term ] );
                        return;
                    }
                    picker.addClass('facebook-loader');
                    $.getJSON(options.sourceUrl, request, function( data, status, xhr ) {
                        requestCache[ term ] = data;
                        picker.removeClass('facebook-loader');
                        response( data );
                    });
                },
                select: function( event, ui ) {
                    picker.val(ui.item.label);
                    self.val(ui.item.id)
                }
            });
            picker.focus(function(){
                if(picker.val().length > 2)
                {
                    picker.autocomplete( "search", picker.val());
                }
            });
            if(self.val() != '')
            {
                $.ajax({
                    url:options.sourceUrl,
                    type:'GET',
                    dataType:'text',
                    data:{
                        id:self.val()
                    },
                    success:function(data)
                    {
                        if(data != null)
                        {
                            picker.val(data);
                        }
                    }
                })
            }
        });

    }
})(jQuery);

$('a.fancyshow').fancybox({
    openEffect	: 'elastic',
    closeEffect	: 'elastic',

    helpers : {
        title : {
            type : 'inside'
        }
    }
});

$('a.fancybox-thumb').fancybox({
    openEffect	: 'elastic',
    closeEffect	: 'elastic',

    helpers : {
        title : {
            type : 'inside'
        },
        thumbs	: {
            width	: 75,
            height	: 75
        }
    }
});

$(document).ready(function(){
    $(".project-item .project-actions .icon-remove-circle").on("click", function(){
        var self = $(this);
        if(self.hasClass('icon-loading'))
        {
            return false;
        }
        var item = $(this).parents('.project-item').first();
        $.ajax({
            url: $(this).attr('href'),
            type:'GET',
            dataType:'json',
            beforeSend:function()
            {
                self.addClass('icon-loading')
            },
            error:function()
            {
                self.removeClass('icon-loading')
            },
            complete:function()
            {
                self.removeClass('icon-loading')
            },
            success: function(data){
                if(data.success == true)
                {
                    item.fadeOut(500, function(){item.remove();})
                }
            }
        });
        return false;
    });
});
/**
 * Copyright (c) 2007-2012 Ariel Flesler - aflesler(at)gmail(dot)com | http://flesler.blogspot.com
 * Dual licensed under MIT and GPL.
 * @author Ariel Flesler
 * @version 1.4.3.1
 */
;(function($){var h=$.scrollTo=function(a,b,c){$(window).scrollTo(a,b,c)};h.defaults={axis:'xy',duration:parseFloat($.fn.jquery)>=1.3?0:1,limit:true};h.window=function(a){return $(window)._scrollable()};$.fn._scrollable=function(){return this.map(function(){var a=this,isWin=!a.nodeName||$.inArray(a.nodeName.toLowerCase(),['iframe','#document','html','body'])!=-1;if(!isWin)return a;var b=(a.contentWindow||a).document||a.ownerDocument||a;return/webkit/i.test(navigator.userAgent)||b.compatMode=='BackCompat'?b.body:b.documentElement})};$.fn.scrollTo=function(e,f,g){if(typeof f=='object'){g=f;f=0}if(typeof g=='function')g={onAfter:g};if(e=='max')e=9e9;g=$.extend({},h.defaults,g);f=f||g.duration;g.queue=g.queue&&g.axis.length>1;if(g.queue)f/=2;g.offset=both(g.offset);g.over=both(g.over);return this._scrollable().each(function(){if(e==null)return;var d=this,$elem=$(d),targ=e,toff,attr={},win=$elem.is('html,body');switch(typeof targ){case'number':case'string':if(/^([+-]=)?\d+(\.\d+)?(px|%)?$/.test(targ)){targ=both(targ);break}targ=$(targ,this);if(!targ.length)return;case'object':if(targ.is||targ.style)toff=(targ=$(targ)).offset()}$.each(g.axis.split(''),function(i,a){var b=a=='x'?'Left':'Top',pos=b.toLowerCase(),key='scroll'+b,old=d[key],max=h.max(d,a);if(toff){attr[key]=toff[pos]+(win?0:old-$elem.offset()[pos]);if(g.margin){attr[key]-=parseInt(targ.css('margin'+b))||0;attr[key]-=parseInt(targ.css('border'+b+'Width'))||0}attr[key]+=g.offset[pos]||0;if(g.over[pos])attr[key]+=targ[a=='x'?'width':'height']()*g.over[pos]}else{var c=targ[pos];attr[key]=c.slice&&c.slice(-1)=='%'?parseFloat(c)/100*max:c}if(g.limit&&/^\d+$/.test(attr[key]))attr[key]=attr[key]<=0?0:Math.min(attr[key],max);if(!i&&g.queue){if(old!=attr[key])animate(g.onAfterFirst);delete attr[key]}});animate(g.onAfter);function animate(a){$elem.animate(attr,f,g.easing,a&&function(){a.call(this,e,g)})}}).end()};h.max=function(a,b){var c=b=='x'?'Width':'Height',scroll='scroll'+c;if(!$(a).is('html,body'))return a[scroll]-$(a)[c.toLowerCase()]();var d='client'+c,html=a.ownerDocument.documentElement,body=a.ownerDocument.body;return Math.max(html[scroll],body[scroll])-Math.min(html[d],body[d])};function both(a){return typeof a=='object'?a:{top:a,left:a}}})(jQuery);