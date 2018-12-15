/// <reference path="jquery-vsdoc.js" />
/// <reference path="jquery.extend.js" />

(function ($) {
	$.fn.userControl = function (options) {
		if (this.length == 0) return this;

		var defaults = { className: '.userControl', url: null, data: {} };
		options = $.extend(defaults, options);

		var formatGolds = function(golds) {
			var v = 0;
			v = 1000000000; if (golds >= v) return (golds%v==0 ? (golds/v) : (golds/v).toFixed(2)) + 'B'; //10亿
			v = 1000000; if (golds >= v) return (golds%v==0 ? (golds/v) : (golds/v).toFixed(2)) + 'M';
			//v = 10000; if (golds >= v) return (golds%v==0 ? (golds/v) : (golds/v).toFixed(2)) + 'W';
			v = 1000; if (golds >= v) return (golds%v==0 ? (golds/v) : (golds/v).toFixed(2)) + 'K';
			return golds;
		};
		var me = this, ids = [], idmaps = {}, menu = null, ajaxMaxLen = 100;
		var html = '\
	<div class="userControl-wrapper hide">\
		<b class="menu-close">x</b>\
		<div class="userinfo-box">\
			<div class="userinfo-avatar"><img src="" width="70" height="70" class="avatar"><br /><span class="uid"></span><br /><span class="umotor"></span></div>\
			<div class="userinfo">\
				<div class="uname">昵称(名称)性别</div>\
				<div class="starpoker">荷豆：<span class="gold golds" title=""></span>/宝石：<span class="gold stones" title=""></span></div>\
				<div>注册：<span class="regip"></span></div>\
				<div>注册时间：<span class="regtime"></span></div>\
				<div>登陆：<span class="loginip"></span></div>\
				<div>登陆时间：<span class="logintime"></span></div>\
			</div>\
		</div>\
		<div class="usernav-box">\
			<a id="meInfo" href="/admin/log/me?uid={0}">查看资料</a> |\
			<a id="meWater" href="/admin/log/user_golds_log?key={0}">消耗记录</a> |<br />\
			<a id="meT" href="">踢人</a> |\
			<a id="meC" href="">强行退出游戏</a> |\
			<a id="meDumb" href="/admin/setting/dumb_list?uid={0}">禁言</a> |\
			<a id="meForb" href="/admin/setting/forbid_list?key={0}">封号</a> \
		</div>\
	</div>';

		me.bind = function(obj) {
			me.find(options.className).live('mousemove', function(){
				var uid = $(this).attr('uid');
				if (!options.data[uid]) return false;
				var o = options.data[uid];
				var golds = parseFloat(o.golds) + parseFloat(o.bonus_golds) + parseFloat(o.bank);
				var stones = parseFloat(o.stones) + parseFloat(o.bonus_stones);
				if (o.avatar && o.avatar.indexOf('/') == 0) menu.find(".avatar").attr('src', '/client'+o.avatar).show();
				else if (o.avatar != '') menu.find(".avatar").attr('src', o.avatar).show();
				else menu.find(".avatar").hide();
				menu.find(".uid").text(uid);
				menu.find(".uname").text(o.nickname + '-' + o.utype + (o.pid ? ('('+o.pid+')') : '') + '-' + o.gender);
				menu.find(".water").text(o.changes);
				menu.find(".gold").text(formatGolds(golds)).attr('title', golds);
				menu.find(".stones").text(formatGolds(stones)).attr('title', stones);
				menu.find(".regip").text(o.regip + "(" + o.reg_ip + ")");
				menu.find(".regtime").text(o.reg_date);
				menu.find(".roomcard").text(o.room_card || '0');
				menu.find(".loginip").text(o.loginip + (o.login_ip ? ("(" + o.login_ip + ")") : ""));
				menu.find(".logintime").text(o.login_date);
				if (o.ismotor == 1) 
					menu.find(".umotor").text('机器人');
				else if (o.ismotor == 10) 
					menu.find(".umotor").text('一代');
				else if (o.ismotor == 20) 
					menu.find(".umotor").text('二代');
				else if (o.ismotor == 30) 
					menu.find(".umotor").text('省代');
				else if (o.ismotor == 40) 
					menu.find(".umotor").text('合作伙伴');
				else if (o.ismotor == 0) 
					menu.find(".umotor").text('用户');
				menu.find(".starpoker").show().end().find(".changes").hide();
				menu.showMenu($(this), 'left', 'top', -5, 20);
				menu.attr('uid', uid);
			}).live('mouseleave', function(){ 
				setTimeout(function(){
					if (menu.attr('state') != 'enter') menu.hideMenu();
				}, 100);
			});
			menu.find('> .menu-close').click(function(){ menu.hideMenu(); });
			menu.find(".usernav-box > a").live('click', function(){
				var _ = $(this), uid = _.parent().parent().attr('uid'), href = _.attr('href'), id = this.id, o = options.data[uid];
				switch (id) {
					case 'meFB':
						if (o.utype != 'fb') return false;
						window.open('https://www.facebook.com/' + o.username.split('@')[0]);
						break;
					case 'meT':
						$(window).confirm({ text: o.nickname + ' 踢下线？', okClick: function(){
							$.ajax({ type: "post", url: options.url.split('_')[0] + '_ttt__', data: { uids: [o.uid] }, success: function (json) {
								$(window).alert({ text: '已通知服务器给用户踢下线！'});
							} });
						} });
						break;
					case 'meC':
						$(window).confirm({ text: o.nickname + ' 强行退出游戏？', okClick: function(){
							$.ajax({ type: "post", url: options.url.split('_')[0] + '_cg__', data: { uids: [o.uid] }, success: function (json) {
								$(window).alert({ text: '已通知服务器让用户强行退出游戏！'});
							} });
						} });
						break;
					default:
						(top.tab || window["tab"]).add(id + uid, $(this).html(), $(this).attr("href").format(uid));
						break;
				}
				return false;
			});
		}
		me.loadJson = function(json){
			if (!json) return false;
			for(o in json) {
				if (!json[o]) { me.find(options.className + '[uid="' + o + '"]').html(''); continue; }
				options.data[o] = json[o];
				var name = json[o].nickname;
				//如果四项都为0的话不显示
				if(json[o].pay!='0.00'||json[o].pay_num!='0'){ //||parseInt(json[o].agent_pay)||parseInt(json[o].to_agent)
					name += ' <font color="red">¥' + parseInt(json[o].pay) + '</font>/';
					name += '<font color="green">' + json[o].pay_num + '</font>';
					//name += '/<font color="blue">' + parseInt(json[o].agent_pay) + '</font>/';
					//name += '<font color="violet">' + parseInt(json[o].to_agent) + '</font>';
				}
				me.find(options.className + '[uid="' + o + '"]').html('<img src="/client/images/ico/view2.gif" class="ico"> ' + name);
			}
			me.bind();
		}
		me.init = function() {
			me.append(html);
			menu = me.find(options.className + '-wrapper');
			menu.mouseenter(function(){ menu.attr('state', 'enter'); }).mouseleave(function(){ menu.attr('state', ''); menu.hideMenu(); });
			me.find(options.className).click(function(){ return false; }).each(function(){
				var id = $(this).attr('uid');
				if (!idmaps[id]) ids.push(id);
				idmaps[id] = true;
			});
			if (ids.length == 0) return false;
			while(ids.length > 0) {
				var len = ids.length, end = len < ajaxMaxLen ? len : ajaxMaxLen;
				var nids = ids.slice(0, end);
				if (len < ajaxMaxLen) ids = []; else ids = ids.slice(end, len);
				$.get(options.url, { ids: nids }, function (json) { me.loadJson(json); });
				//$.ajax({ type: "get", url: options.url, data: { ids: nids }, success: function (json) { me.loadJson(json); } });
			}
		};
		me.init();
		return me;
	};
    String.prototype.format = function() {
        var resultStr = this.toString();
        // 参数为对象
        if(typeof arguments[0] === "object") {
            for(var i in arguments[0]) {
                resultStr = resultStr.replace("{" + i + "}", arguments[0][i]);
            }
        }
        // 多个参数
        else {
            for(var i = 0; i < arguments.length; i ++) {
                resultStr = resultStr.replace("{" + i + "}", arguments[i]);
            }
        }
        return resultStr;
    };
	$.fn.smallLang = function (options) {
		if (this.length == 0) return this;

		var defaults = { lang: { 'zh_TW': '繁体', 'zh_CN': '简体', 'en_US': '英文' }, def: "zh_TW", change: null };
		options = $.extend(defaults, options);

		var me = this;
		me.bind = function(obj) {
			var json = JSON.parse(obj.val()) || {}, key = obj.attr('key');
			obj.prev().change(function(){
				var _this = $(this), val = _this.val(), def = _this.attr('def');
				if (val == def) return false;
				var lang = _this.prev().val();
				json[lang] = val;
				var str = JSON.stringify(json);
				obj.val(str);
				if (options.change) options.change(key, str);
			}).prev().change(function(){
				var lang = $(this).val();
				if (json && json[lang]) obj.prev().val(json[lang]).attr("def", json[lang]); else obj.prev().val('').attr("def", '');
				$(this).next().focus();
			});
		}
		me.init = function(){
			var html = '<select class="select_lang">';
			for(var key in options.lang) {
				html += '<option value="{0}">{1}</option>'.format(key, options.lang[key]);
			}
			html += '</select> <input type="text" class="input_value" value="" />';
			$(me).each(function(){
				var obj = $(this), json = JSON.parse(obj.val()) || {};
				obj.hide().before(html);
				me.bind(obj);
				obj.prev().prev().val(options.def);
				if (json && json[options.def]) obj.prev().val(json[options.def]); else obj.prev().val('');
			});
		};
		me.init();
		return me;
	};
	$.fn.LangBox = function (options) {
		if (this.length == 0) return this;

		var me = this, defaults = { lang: { 'zh_TW': '繁体', 'zh_CN': '简体', 'en_US': '英文' }, def: 'zh_TW', width: $('body').hasClass('mobile') ? 320 : 405, data: null, textarea: false };
		options = $.extend(defaults, options);
		options.data = options.data || {};
		var meName = me.attr('name');
		if (meName.length == 0) return this;

		var lastTR = null, lang_count = 0, lang_array = [];
		var lang_html = ''; for(var key in options.lang) { lang_html += '<option value="{0}">{1}</option>'.format(key, options.lang[key]); lang_count++; }
		var box_html = '\
	<table style="width:{0}px" class="gridview">\
		<colgroup><col class="_w20" /><col /><col class="_w18" /></colgroup>\
		<tr>\
			<th class="center t">语言</th>\
			<th class="center t">内容</th>\
			<th class="center t e"><a href="#" class="langbox_clear" title="清除数据"><img src="/client/images/ico/clear.gif" class="ico" /> 清除</a></th>\
		</tr>\
		<tr>\
			<td class="pl5"></td>\
			<td class="center"></td>\
			<td class="center"><a href="#" class="langbox_add"	><img src="/client/images/ico/add.gif" class="ico" /> 添加</a></td>\
		</tr>\
	</table>', box_item_html = '\
	<tr class="langbox_item">\
		<td class="center"><select class="langbox_lang" name="{1}Langs[]">{0}</select></td>\
		<td class="center">{2}</td>\
		<td class="center"><a href="#" class="langbox_delete" ><img src="/client/images/ico/delete.gif" class="ico" /> 删除</a></td>\
	</tr>', input_html = '<input type="text" style="width:95%" class="langbox_value" name="" />',
		textarea_html = '<textarea type="text" style="width:95%;{0}" class="langbox_value" name=""></textarea>';
		me.bind = function() {
			me.find(".langbox_clear").click(function(){
				if (!confirm('确定清除所有吗？')) return false;
				me.find(".langbox_item").remove();
				return false;
			});
			me.find(".langbox_add").click(function(){
				var count = me.find('.langbox_item').length;
				if (count >= lang_count) return false;
				lastTR.before(box_item_html.format(lang_html, meName, options.textarea ? textarea_html.format(options.inputHeight ? ('height:'+options.inputHeight+'px') : '') : input_html));
				if (count == 0) {
					lastTR.prev().find('.langbox_lang').val(options.def).attr('def', options.def);
					options.lang[options.def] = null;
					lastTR.prev().find(".langbox_value").val(options.data[options.def] || '').attr('name', meName + "[" + options.def + "]").focus();
				} else {
					for(var key in options.lang){
						if (options.lang[key]) {
							lastTR.prev().find('.langbox_lang').val(key).attr('def', key);
							options.lang[key] = null;
							lastTR.prev().find(".langbox_value").val(options.data[key] || '').attr('name', meName + "[" + key + "]").focus();
							break;
						}
					}
				}
				return false;
			});
			me.find(".langbox_delete").live('click', function(){
				var obj = $(this).parent().parent().find(".langbox_lang"), lang = obj.val(), text = obj.find("option:selected").text();
				if (confirm('删除' + text + '吗？')){
					options.lang[lang] = true;
					$(this).parent().parent().remove();
					return false;
				}
				return false;
			});
			me.find(".langbox_lang").live('change', function(){
				var _this = $(this), lang = _this.val(), def = _this.attr('def'), text = _this.find("option:selected").text();

				if (lang == def) return false;

				var isexist = false;
				me.find(".langbox_item .langbox_lang").each(function(){
					var _def = $(this).attr("def");
					if (lang == _def) isexist = true;
				});
				if (isexist) { alert(text + " - 已存在！"); _this.val(def).focus(); return false; }
				_this.attr('def', lang);
				options.lang[lang] = null;
				options.lang[def] = true;
				_this.parent().next().find(".langbox_value").val(options.data[lang] || '').attr('name', meName + "[" + lang + "]").focus();
			});
		}
		me.init = function(){
			me.append(box_html.format(options.width));
			lastTR = me.find('tr:last');
			me.bind();
			for(var key in options.data) {
				if (!options.lang[key]) continue;
				lastTR.before(box_item_html.format(lang_html, meName, options.textarea ? textarea_html.format(options.inputHeight ? ('height:'+options.inputHeight+'px') : '') : input_html));
				lastTR.prev().find('.langbox_lang').val(key).attr('def', key);
				options.lang[key] = null;
				lastTR.prev().find(".langbox_value").val(options.data[key].replace(/\\/g, '')).attr('name', meName + "[" + key + "]");
			}
		};
		me.init();
		return me;
	};
	$.fn.PropBox = function (options) {
		if (this.length == 0) return this;
		var me = this, defaults = { prop: null, def: 1, width: $('body').hasClass('mobile') ? 320 : 405, data: null, textarea: false };
		options = $.extend(defaults, options);
		options.data = options.data || {};
		options.prop = options.prop || {};
		var meName = me.attr('name');
		if (meName.length == 0) return this;

		var lastTR = null, prop_count = 0, prop_array = [];
		var prop_html = ''; for(var key in options.prop) { prop_html += '<option value="{0}">{1}</option>'.format(key, options.prop[key]); prop_count++; }
		var box_html = '\
	<table style="width:{0}px" class="gridview">\
		<colgroup><col class="_w20" /><col /><col class="_w18" /></colgroup>\
		<tr>\
			<th class="center t">道具</th>\
			<th class="center t">数量</th>\
			<th class="center t e"><a href="#" class="propbox_clear" title="清除数据"><img src="/client/images/ico/clear.gif" class="ico" /> 清除</a></th>\
		</tr>\
		<tr>\
			<td class="pl5"></td>\
			<td class="center"></td>\
			<td class="center"><a href="#" class="propbox_add"	><img src="/client/images/ico/add.gif" class="ico" /> 添加</a></td>\
		</tr>\
	</table>', box_item_html = '\
	<tr class="propbox_item">\
		<td class="center"><select class="propbox_prop" name="{1}Props[]">{0}</select></td>\
		<td class="center">{2}</td>\
		<td class="center"><a href="#" class="propbox_delete" ><img src="/client/images/ico/delete.gif" class="ico" /> 删除</a></td>\
	</tr>', input_html = '<input type="text" style="width:95%" class="propbox_value" name="" />',
		textarea_html = '<textarea type="text" style="width:95%;{0}" class="propbox_value" name=""></textarea>';
		me.bind = function() {
			me.find(".propbox_clear").click(function(){
				if (!confirm('确定清除所有吗？')) return false;
				me.find(".propbox_item").remove();
				return false;
			});
			me.find(".propbox_add").click(function(){
				var count = me.find('.propbox_item').length;
				if (count >= prop_count) return false;
				lastTR.before(box_item_html.format(prop_html, meName, options.textarea ? textarea_html.format(options.inputHeight ? ('height:'+options.inputHeight+'px') : '') : input_html));
				if (count == 0) {
					lastTR.prev().find('.propbox_prop').val(options.def).attr('def', options.def);
					options.prop[options.def] = null;
					lastTR.prev().find(".propbox_value").val(options.data[options.def] || '').attr('name', meName + "[" + options.def + "]").focus();
				} else {
					for(var key in options.prop){
						if (options.prop[key]) {
							lastTR.prev().find('.propbox_prop').val(key).attr('def', key);
							options.prop[key] = null;
							lastTR.prev().find(".propbox_value").val(options.data[key] || '').attr('name', meName + "[" + key + "]").focus();
							break;
						}
					}
				}
				return false;
			});
			me.find(".propbox_delete").live('click', function(){
				var obj = $(this).parent().parent().find(".propbox_prop"), prop = obj.val(), text = obj.find("option:selected").text();
				if (confirm('删除' + text + '吗？')){
					options.prop[prop] = true;
					$(this).parent().parent().remove();
					return false;
				}
				return false;
			});
			me.find(".propbox_prop").live('change', function(){
				var _this = $(this), prop = _this.val(), def = _this.attr('def'), text = _this.find("option:selected").text();

				if (prop == def) return false;

				var isexist = false;
				me.find(".propbox_item .propbox_prop").each(function(){
					var _def = $(this).attr("def");
					if (prop == _def) isexist = true;
				});
				if (isexist) { alert(text + " - 已存在！"); _this.val(def).focus(); return false; }
				_this.attr('def', prop);
				options.prop[prop] = null;
				options.prop[def] = true;
				_this.parent().next().find(".propbox_value").val(options.data[prop] || '').attr('name', meName + "[" + prop + "]").focus();
			});
		}
		me.init = function(){
			me.append(box_html.format(options.width));
			lastTR = me.find('tr:last');
			me.bind();
			for(var key in options.data) {
				if (!options.prop[key]) continue;
				lastTR.before(box_item_html.format(prop_html, meName, options.textarea ? textarea_html.format(options.inputHeight ? ('height:'+options.inputHeight+'px') : '') : input_html));
				lastTR.prev().find('.propbox_prop').val(key).attr('def', key);
				options.prop[key] = null;
				lastTR.prev().find(".propbox_value").val(options.data[key].replace(/\\/g, '')).attr('name', meName + "[" + key + "]");
			}
		};
		me.init();
		return me;
	};
	$.fn.fixTable=function(pRow, pCol, splitColor){
		//滚动条宽度
		var scrW = 6;

		//设置分隔线颜色
		splitColor = splitColor || '#333';

		//得到表格本身
		var t = $(this);
		var pid = 'fixbox_'+t.attr('id');

		t.show();

		//得到表格实际大小
		var tw = t.outerWidth(true);
		var th = t.outerHeight(true);

		//在外部包一个DIV,用来获取允许显示区域大小
		t.wrap("<div id='"+pid+"' ></div>");
		var p = $('#'+pid);
		p.css({
			width: '100%',
			height: '100%',
			border: '0px',
			margin: '0 0 0 0',
			padding: '0 0 0 0'
		});

		//允许显示区域大小
		t.hide();
		var cw = p.outerWidth(true);
		var ch = p.outerHeight(true);
		t.show();

		//拿到表格的HTML代码
		var thtml = p.html();

		//判断是否需要固定行列头
		if(tw<=cw && th<=ch){
			return;
		}
		//判断需要固定行/列/行列
		var fixType = 4;	//全固定
		if(tw<=cw-scrW){	//宽度够, 高度不够
			fixType = 1;	//行固定
			cw = tw+scrW;
		}else if(th<=ch-scrW){	//高度够, 宽度不够
			fixType = 2;	//列固定
			ch = th+scrW;
		}
		//固定单元格的位置
		var w1 = 0;
		var h1 = 0;

		var post = t.offset();
		var p1, p2, p3, p4;
		if(fixType==4){	//行头列头都需固定
			//取出指定行列单元格左上角的位置,单位px
			var pos = t.find('tr').eq(pRow).find('td').eq(pCol).offset();

			w1 = pos.left - post.left;
			h1 = pos.top - post.top;

			var tmp='<table style="background: #ECE9D8;" ';
			tmp+='border="0" cellspacing="0" cellpadding="0">';
			tmp+='<tr><td style="border-right: 1px solid '+splitColor+
				';border-bottom: 1px solid '+splitColor+'">';
			tmp+='<div id="'+pid+'1"></div></td>';
			tmp+='<td style="border-bottom: 1px solid '+splitColor+
				';"><div id="'+pid+'2"></div></td></tr>';
			tmp+='<tr><td valign="top" style="border-right: 1px solid '+
				splitColor+';"><div id="'+pid+'3"></div></td>';
			tmp+='<td><div id="'+pid+'4"></div></td></tr>';
			tmp+='</table>';

			p.before(tmp);

			$('div[id^='+pid+']').each(function(){
				$(this).css({
					background: 'white',
					overflow: 'hidden',
					margin: '0 0 0 0',
					padding: '0 0 0 0',
					border: '0'
				});
			});
			p1 = $('#'+pid+'1');
			p2 = $('#'+pid+'2');
			p3 = $('#'+pid+'3');
			p4 = $('#'+pid+'4');

			//左上角方块
			p1.html(thtml).css({width: w1-1, height: h1-1});
			p1.find('table:first').attr('id',undefined);

			//右上方块
			p2.html(thtml).css({width: cw-w1-scrW, height: h1-1});
			p2.find('table:first').css({
				position: 'relative',
				left: -w1
			}).attr('id',undefined);

			//左下方块
			p3.html(thtml).css({width: w1-1, height: ch-h1-scrW});
			p3.find('table:first').css({
				position: 'relative',
				top: -h1
			}).attr('id',undefined);

			//主方块
			p4.append(p).css({
				width: cw-w1,
				height: ch-h1,
				overflow: 'auto'
			});

			t.css({
				position: 'relative',
				top: -h1,
				left: -w1
			});

			p.css({width: tw-w1, height: th, overflow: 'hidden'});

			p4.scroll(function(){
				p2.scrollLeft($(this).scrollLeft());
				p3.scrollTop($(this).scrollTop());
			});
		}else if(fixType==1){	//只需固定行头
			var pos = t.find('tr').eq(pRow).find('td').first().offset();
			h1 = pos.top - post.top;

			var tmp='<table style="background: #ECE9D8;" ';
			tmp+='border="0" cellspacing="0" cellpadding="0">';
			tmp+='<tr><td style="border-bottom: 1px solid '+splitColor+'">';
			tmp+='<div id="'+pid+'1"></div></td></tr>';
			tmp+='<tr><td><div id="'+pid+'2"></div></td></tr>';
			tmp+='</table>';

			p.before(tmp);

			$('div[id^='+pid+']').each(function(){
				$(this).css({
					background: 'white',
					overflow: 'hidden',
					margin: '0 0 0 0',
					padding: '0 0 0 0',
					border: '0'
				});
			});
			p1 = $('#'+pid+'1');
			p2 = $('#'+pid+'2');
			//上方方块
			p1.html(thtml).css({width: tw, height: h1-1});
			p1.find('table:first').attr('id',undefined);

			//主方块
			p2.append(p).css({
				width: cw+1,
				height: ch-h1,
				overflow: 'auto'
			});

			t.css({
				position: 'relative',
				top: -h1,
				left: 0
			});

			p.css({width: tw, height: th-h1, overflow: 'hidden'});
		}else if(fixType==2){	//只需固定列头
			var pos = t.find('tr').first().find('td').eq(pCol).offset();
			w1 = pos.left - post.left;

			var tmp='<table style="background: #ECE9D8;" ';
			tmp+='border="0" cellspacing="0" cellpadding="0">';
			tmp+='<tr><td valign="top" style="border-right: 1px solid '+splitColor+'">';
			tmp+='<div id="'+pid+'1"></div></td>';
			tmp+='<td><div id="'+pid+'2"></div></td></tr>';
			tmp+='</table>';

			p.before(tmp);

			$('div[id^='+pid+']').each(function(){
				$(this).css({
					background: 'white',
					overflow: 'hidden',
					margin: '0 0 0 0',
					padding: '0 0 0 0',
					border: '0'
				});
			});
			p1 = $('#'+pid+'1');
			p2 = $('#'+pid+'2');
			//上方方块
			p1.html(thtml).css({width: w1-1, height: th});
			p1.find('table:first').attr('id',undefined);

			//主方块
			p2.append(p).css({
				width: cw-w1,
				height: ch+1,
				overflow: 'auto'
			});

			t.css({
				position: 'relative',
				top: 0,
				left: -w1
			});

			p.css({width: tw-w1, height: th, overflow: 'hidden'});
		}
	};

	$.fn.checkDefault = function (title, valAttr) {
		/// <summary>
		///	 驗證默認数据是否被修改
		/// </summary>
		/// <param name="output" type="string">輸出被处理過的数据</param>
		title = title.split('：')[0].split(':')[0];
		var defaultV = this.attr("default"), val = valAttr ? this.attr(valAttr) : this.val();
		return (defaultV == val) ? "" : ('<tr><th class="pl5" title="{2}：">{2}：</th><td class="pl5"><span class="blue">{0}</span> 修改为：<span class="red">{1}</span></td></tr>'.format(defaultV == '' ? '空' : defaultV, val == '' ? '空' : val, title));
	};
	$.fn.checkDefaults = function (title, valAttr) {
		/// <summary>
		///	 驗證默認数据是否被修改
		/// </summary>
		/// <param name="output" type="string">輸出被处理過的数据</param>
		title = title.split('：')[0].split(':')[0];
		var defaultV = this.attr("default"), val = valAttr ? this.attr(valAttr) : this.val();
		if (defaultV.length > 0) defaultV += ';';
		if (valAttr && val.length > 0) val += ';';
		val = valAttr && val.length > 0 ? val.substring(0, val.length - 1) : val;
		defaultV = defaultV.length > 0 ? defaultV.substring(0, defaultV.length - 1) : defaultV;
		return (defaultV == val) ? "" : ('<tr><th class="pl5" title="{2}：">{2}：</th><td class="pl5"><span class="blue">{0}</span> 修改为：<span class="red">{1}</span></td></tr>'.format(defaultV == '' ? '空' : defaultV, val == '' ? '空' : val, title));
	};
	$.fn.navTree = function (itemClick, expandAll) {
		/// <summary>
		///	 簡單的樹
		/// </summary>
		/// <param name="itemClick" type="function">樹的頁子單擊事件</param>
		/// <param name="expandAll" type="bool">是否展開全部節點</param>
		var me = this;
		this.find(".node").click(function () {
			var _this = $(this);
			if (_this.hasClass("open")) {
				_this.removeClass("open").addClass("close");
				_this.next().hide();
			} else {
				_this.removeClass("close").addClass("open");
				_this.next().show();
			}
			return false;
		});
		this.find("a").click(function () {
			if (itemClick || null) {
				itemClick(this);
				me.find("a").removeClass("active");
				$(this).addClass("active");
			}
			return false;
		});
		if (expandAll || false) this.find(".node").click(); else if (this.find(".node").length < 5) this.find(".node").first().click();
		return me;
	};
	$.fn.navTabs = function () {
		/// <summary>
		///	 簡單的选项卡
		/// </summary>
		var iframe = '<iframe class="tab-page-iframe" frameborder="0" width="100%" id="{0}Iframe" src="{1}" scrolling="auto"></iframe>';
		var item = '<li id="{1}Tab"><i></i><a href="#" id="a{1}Tab">{0}</a></li>';
		var me = this, me_left = me.offset().left;
		var me_box = this.next();

		//綁定右鍵菜單
		$(document).bind("contextmenu", function (e) {
			var id = (e.target || event.srcElement).id;
			if (id.indexOf("Tab") != -1) return false;
		});
		$(document).bind("click", function (e) { if (3 != e.which) $(".nav-tab-right-menu").hide(); });
		me.find("li").live('mousedown', function (e) {
			var _this = $(this);
			if (!_this.hasClass('active')) return false;
			if (e && e.toElement && e.toElement.localName && e.toElement.localName == 'i') return false;
			//if (3 == e.which) {
				$(".nav-tab-right-menu").hide();
				if (_this.find("i").length == 0) {
					$(".nav-tab-right-menu a,.nav-tab-right-menu i").hide();
					$(".nav-tab-right-menu .refresh,.nav-tab-right-menu .cancel,.nav-tab-right-menu i:first").show();
				} else {
					$(".nav-tab-right-menu a,.nav-tab-right-menu i").show();
				}
				var off = _this.offset();
				$(".nav-tab-right-menu").css({
					"left": off.left,
					"top": off.top + 25
				}).show();
				me.current = _this;
				return false;
			//};
		});

		$(".nav-tab-close").click(function () {
			if (me.find(".active > i").length == 0) me.find(".active").next().click();
			me.close();
			return false;
		});
		me.find("li").live('click', function () {
			var id = this.id.substring(0, this.id.length - 3),
				_this = $(this);
			me.find("li").removeClass("active");
			_this.addClass("active");

			me_box.find("iframe").removeClass("active");
			$("#" + id + "Iframe").addClass("active");
			$(window).resize();
			if ($(".nav-tree #" + id).length == 1) {
				$(".nav-tree a").removeClass("active");
				$("#" + id).addClass("active");
			};
			me_left = me.offset().left;
			var lastLi = me.find("li:last"), allLiW = lastLi.width() + lastLi.offset().left - me_left;
			var thisW = _this.offset().left - me_left + _this.width(), meW = me.width();
			if (allLiW > meW) me.find("li:not(.hide)").first().addClass("hide");
			if (thisW < 0) {
				me.find("li:not(.hide)").last().addClass("hide");
				_this.removeClass("hide");
			}
			_this = null;
			return false;
		});
		me.find("i").die().live('click', function () {
			var id = $(this).parent().attr("id");
			id = id.substring(0, id.length - 3);
			var currClose = $("#" + id + "Tab").hasClass("active");
			if (currClose) $("#" + id + "Tab").prev().click();
			$("#" + id + "Tab").remove();
			$("#" + id + "Iframe").remove();
			$(".nav-tab-right-menu").hide();
			if ($.isIE6()) {
				setTimeout(function () {
					me.active();
				}, 100);
			}
			return false;
		});

		this.add = function (id, title, url) {
			/// <summary>
			///	 添加頁面到选项卡
			/// </summary>
			/// <param name="id" type="string">指定ID</param>
			/// <param name="title" type="string">指定標題</param>
			/// <param name="url" type="string">顯示網址</param>
			var prevTab = me.find(".active");
			var prevIframe = me_box.find(".active")
			var currTab = $("#" + id + "Tab");

			if (currTab.length == 1) currTab.click(); else {
				if (prevTab.length == 1) {
					prevTab.after(item.format(title, id).replace('src=', 'src="/client/images/ico/loading.gif" src1='));
					prevIframe.after(iframe.format(id, url));
				} else {
					me.append(item.format(title, id).replace('src=', 'src="/client/images/ico/loading.gif" src1='));
					me_box.append(iframe.format(id, url));
					me.find("i").remove();
					$("#" + id + "Tab").css("padding-right", "10px");
				}
				$("#" + id + "Iframe").load(function () {
					$("#" + id + "Tab img").attr("src", $("#" + id + "Tab img").attr("src1"));
					$(".nav-tab-close").tooltip();
				});
				$("#" + id + "Tab").click();
				$(".nav-tab-close").tooltip({ text: '正在加载数据，请等待...', type: 'process', width: 140, left: -185, top: 40 });
			}
		};
		this.complete = function (id) {
			/// <summary>
			///	 加载完成更新loading图片
			/// </summary>
			/// <param name="id" type="string">指定ID為空時刷新當前选项卡</param>
			if (id == null) {
				id = me.find(".active").attr("id");
				id = id.substring(0, id.length - 3);
			};
			$("#" + id + "Tab img").attr("src", $("#" + id + "Tab img").attr("src1"));
			$(".nav-tab-close").tooltip();
		}
		this.refreshAll = function () {
			me.find("> li").each(function () {
				var id = this.id.replace("Tab", "");
				me.refresh(id, true);
			});
		}
		this.refresh = function (id, refIframe) {
			/// <summary>
			///	 刷新选项卡
			/// </summary>
			/// <param name="id" type="string">指定ID為空時刷新當前选项卡</param>
			if (id || null) {
				$("#" + id + "Tab img").attr("src", "/client/images/ico/loading.gif");
			} else {
				me.find(".active img").attr("src", "/client/images/ico/loading.gif");
				id = me.find(".active").attr("id");
				id = id.substring(0, id.length - 3);
			}
			$(".nav-tab-close").tooltip({ text: '正在加载数据，请等待...', type: 'process', width: 140, left: -185, top: 40 });
			if (refIframe || null) {
				if (top.window.frames[id + "Iframe"] && top.window.frames[id + "Iframe"].location) top.window.frames[id + "Iframe"].location.reload();
				else if (document.getElementById(id + "Iframe")) document.getElementById(id + "Iframe").contentDocument.location.reload();
				else return false;
				return true;
				//try {
				//	(top.window.frames[id + "Iframe"] || document.getElementById(id + "Iframe").contentDocument).location.reload();
				//} catch (ex) { }
			}
			return false;
		}
		this.iframerefresh = function (id, refIframe) {
			/// <summary>
			///	 刷新选项卡中的iframe
			/// </summary>
			/// <param name="id" type="string">指定ID為空時刷新當前选项卡</param>
			if (id || null) {
				$("#" + id + "Tab img").attr("src", "/client/images/ico/loading.gif");
			} else {
				me.find(".active img").attr("src", "/client/images/ico/loading.gif");
				id = me.find(".active").attr("id");
				id = id.substring(0, id.length - 3);
			}
			$(".nav-tab-close").tooltip({ text: '正在加载数据，请等待...', type: 'process', width: 140, left: -185, top: 40 });
			if (refIframe || null) {
				try {
					document.getElementById(id + "Iframe").contentDocument.getElementById("ztree-iframe").contentDocument.location.reload();
				} catch (ex) {
					try {
						top.window.frames[id + "Iframe"].frames["ztree-iframe"].location.reload();
					} catch (ex) { }
				}
			}
		}
		this.active = function (id, func) {
			/// <summary>
			///	 激活选项卡
			/// </summary>
			/// <param name="id" type="string">指定ID為空時刷新當前选项卡</param>
			/// <param name="func" type="string">回調方法</param>
			if (id || null) $("#" + id + "Tab").click(); else me.find("li.active").click();
			if (func || null) func();
		};
		this.close = function (id, func) {
			/// <summary>
			///	 关闭选项卡
			/// </summary>
			/// <param name="id" type="string">指定ID為空時刷新當前选项卡</param>
			/// <param name="func" type="string">回調方法</param>
			if (id || null) $("#" + id + "Tab > i").click(); else me.find("li.active i").click();
			if (func || null) func();
		};
		this.current = null;

		return this;
	}
	$.fn.checkBoxList = function (options) {
		/// <summary>
		///	 多選列表控件
		/// </summary>
		/// <param name="options" type="object">
		///	 參數列表：
		///	 &#10;	width				  寬
		///	 &#10;	height				 高
		///	 &#10;	url					接口URL
		///	 &#10;	data				   json数据
		///	 &#10;	defValue			   默認值列表
		///	 &#10;	isSearch			   是否顯示搜索
		///	 &#10;	isSelectAll			是否顯示全選
		///	 &#10;	isResetDefaultValue	是否顯示還原默認值
		///	 &#10;	className			  附加class
		///	 &#10;	itemClick			  每一項單擊事件
		/// </param>
		var $this = this, defaults = { width: 0, height: 0, url: '', data: [], defValue: [], isSearch: true, isSelectAll: true, isResetDefaultValue: true, className: '', itemClick: null };
		options = $.extend(defaults, options);
		var templates = '\
			<div class="checkboxlist checkboxlist{0} {2}">\
				<ul class="checkboxlist-container">{1}</ul>\
				<div class="checkboxlist-opterate">\
					<div class="checkboxlist-search"><input type="text" class="txtSearch" placeholder="请輸入搜索關鍵字" /></div>\
					<div class="checkboxlist-left">\
						<label><input type="checkbox" class="chkAll" /> 选择全部</label>&nbsp;&nbsp;\
						<input type="button" class="btnReset" title="恢复默認值" />\
					</div>\
				</div>\
			</div>', templates_item = '<li><label><input type="checkbox" class="chkItem" value="{0}" /> {1}</label></li>';
		var change = function (me, checkbox) {
			var value1 = me.val(), value2 = checkbox.value;
			if (checkbox.checked) me.val(value1 + value2 + ";"); else me.val(value1.replace(value2 + ";", ""));
			if (options.change) me.change();
		}
		var event = function (me, meprev) {
			meprev.find(".chkItem").attr("checked", false).unbind("click").click(function () { change(me, this); if (options.itemClick) options.itemClick(me, this); });
			var val = '';
			$(options.defValue).each(function () {
				meprev.find(".chkItem[value='{0}']".format(this.id)).attr("checked", true);
				val += this.id + ";";
			});
			me.val(val);
			if (options.change) me.change();
		}
		this.init = function (me, async) {
			var mew = options.width > 0 ? options.width : me.width(),
				meh = options.height > 0 ? options.height : me.height(),
				meprev = async ? me.prev().prev() : me.prev(), meoph = meprev.find(".checkboxlist-opterate").height();

			if (!options.isSearch && !options.isSelectAll && !options.isResetDefaultValue) { meoph = 3; meprev.find(".checkboxlist-opterate").height(meoph).css("border-top", "0px"); }
			if (!options.isSearch) meprev.find(".checkboxlist-search").hide();
			if (!options.isSelectAll) meprev.find(".checkboxlist-opterate label").hide();
			if (!options.isResetDefaultValue) meprev.find(".btnReset").hide();
			meprev.width(mew).height(meh);
			meprev.find(".checkboxlist-container").height(meh - meoph);
			meprev.find(".txtSearch").textTip();

			event(me, meprev);
			meprev.find(".chkAll").click(function () {
				var checked = this.checked;
				meprev.find(".chkItem").each(function () {
					if ($(this).parent().parent().css("display") != 'none') {
						if (checked) {
							if (!this.checked) { this.checked = true; change(me, this); if (options.itemClick) options.itemClick(me, this); }
						} else {
							if (this.checked) { this.checked = false; change(me, this); if (options.itemClick) options.itemClick(me, this); }
						}
					}
				});
			});
			meprev.find(".btnReset").click(function () { meprev.find(".chkAll").attr("checked", false); event(me, meprev); });
			var itemList = meprev.find(".checkboxlist-container li");
			meprev.find(".txtSearch").keyup(function (e) {
				var val = this.value, _this = null;
				if (val == '') { itemList.show(); return; }
				itemList.each(function () {
					_this = $(this);
					if (_this.text().indexOf(val) < 0) _this.hide(); else _this.show();
				});
			});
			me.prev().find(".checkcomboboxlist2").addClass("checkcomboboxlist2active");
		};
		this.loadJson = function (json, defValue) {
			if (json) options.data = json;
			if (defValue) options.defValue = defValue;
			$this.each(function () {
				var me = $(this);
				if (me.prev().hasClass("checkboxlist")) me.prev().remove();
				if (me.prev().prev().hasClass("checkboxlist")) me.prev().prev().remove();
				var mew = options.width > 0 ? options.width : me.width(),
					html = templates.format(mew, '', options.className);
				me.before(html);
				var mepbox = me.prev().find(".checkboxlist-container");
				$(options.data).each(function () { mepbox.append(templates_item.format(this.id, this.name)); });
				$this.init(me);
			});
		};
		this.loadAjax = function (url, defValue) {
			if (url) options.url = url;
			if (defValue) options.defValue = defValue;
			$.ajax({ type: "get", url: options.url, success: function (json) { options.data = json; $this.loadJson(); } });
		};
		this.asyncLoadJson = function (json, defValue) {
			if (json) options.data = json;
			if (defValue) options.defValue = defValue;
			$this.each(function () {
				var me = $(this);
				if (me.prev().hasClass("checkboxlist")) me.prev().remove();
				if (me.prev().prev().hasClass("checkboxlist")) me.prev().prev().remove();
				var mew = options.width > 0 ? options.width : me.width(),
					html = templates.format(mew, '', options.className);
				me.before(html);
				var mepbox = me.prev().find(".checkboxlist-container");
				$.chunk(options.data, function (i, item) {
					if (item) mepbox.append(templates_item.format(item.id, item.name));
				}, function () { $this.init(me, true); });
			});
		};
		this.asyncLoadAjax = function (url, defValue) {
			if (url) options.url = url;
			if (defValue) options.defValue = defValue;
			$.ajax({ type: "get", url: options.url, success: function (json) { options.data = json; $this.asyncLoadJson(); } });
		};
		if (options.url.length > 0) $this.loadAjax(); else $this.loadJson();
		return this;
	};
	$.fn.checkComboBoxList = function (options) {
		/// <summary>
		///	 多選下拉列表控件
		/// </summary>
		/// <param name="options" type="object">
		///	 參數列表：
		///	 &#10;	width				  寬
		///	 &#10;	height				 高
		///	 &#10;	url					接口URL
		///	 &#10;	data				   json数据
		///	 &#10;	defValue			   默認值列表
		///	 &#10;	isSearch			   是否顯示搜索
		///	 &#10;	isSelectAll			是否顯示全選
		///	 &#10;	isResetDefaultValue	是否顯示還原默認值
		///	 &#10;	className			  附加class
		///	 &#10;	itemClick			  每一項單擊事件
		/// </param>
		var $this = this, templates = '\
			<div class="checkcomboboxlist checkcomboboxlist{0}"><div class="checkcomboboxlist2">\
				<ul class="checkcomboboxlist-container clearfix">{1}</ul>\
				<div class="checkcomboboxlist-bottom"></div>\
			</div></div>', templates_item = '<li>{1}<input type="button" class="btnDelete" title="刪除" val="{0}" /></li>';
		var defaults = {
			width: 0, height: 0, change: false,
			url: '', data: [], defValue: [],
			isSearch: true, isSelectAll: true, isResetDefaultValue: true,
			className: 'checkcomboboxlist-box',
			itemClick: function (me, checkbox) {
				var value = checkbox.value, text = $(checkbox).parent().text(),
					box = $(checkbox).parent().parent().parent().parent(),
					meprev = box.next();
				if (checkbox.checked) {
					meprev.find(".checkcomboboxlist-container")
						.append(templates_item.format(value, text))
						.find(".btnDelete").last().unbind("click").click(function () { deleteClick(me, box, this); return false; });
				} else meprev.find(".btnDelete[val='{0}']".format(value)).click();
				var offset = meprev.find(".checkcomboboxlist2").offset(), thish = meprev.find(".checkcomboboxlist2").height();
				box.css({ "left": offset.left, "top": offset.top + thish });
			}
		}
		options = $.extend(defaults, options);
		var $box = $(this).checkBoxList(options);
		var getItemHtml = function (data) {
			var html = ''; $(data).each(function () { html += templates_item.format(this.id, this.name); });
			return html;
		}
		var deleteClick = function (me, box, obj) {
			var value = $(obj).attr("val");
			me.val(me.val().replace(value + ";", ""));
			if (options.change) me.change();
			box.find(".chkItem[value='{0}']".format(value)).attr('checked', false);
			$(obj).parent().remove();
		}
		this.init = function (me) {
			me = $(me);
			if (me.prev().hasClass("checkcomboboxlist")) me.prev().remove();
			if (me.prev().prev().hasClass("checkcomboboxlist")) me.prev().prev().remove();
			var mew = options.width > 0 ? options.width : me.width(),
				meh = options.height > 0 ? options.height : me.height(),
				html = templates.format(mew, getItemHtml(options.defValue));
			me.before(html);
			var meprev = me.prev(), box = meprev.prev(), state = box.css("display");
			meprev.find(".checkcomboboxlist2").click(function () {
				state = box.css("display");
				$(".checkcomboboxlist-box").hide();
				if (state != 'none') box.show();
				if (state == 'none') {
					var offset = $(this).offset(), thish = $(this).height();
					box.css({ "left": offset.left, "top": offset.top + thish }).show();
				} else box.hide();
			});
			box.mouseleave(function () { box.hide(); });
			meprev.find(".btnDelete").unbind("click").click(function () { deleteClick(me, box, this); return false; });
			box.find(".btnReset").click(function () {
				meprev.find(".checkcomboboxlist-container").html(getItemHtml(options.defValue));
				meprev.find(".btnDelete").unbind("click").click(function () { deleteClick(me, box, this); return false; });
				var offset = meprev.find(".checkcomboboxlist2").offset(), thish = meprev.find(".checkcomboboxlist2").height();
				box.css({ "left": offset.left, "top": offset.top + thish });
			});
		}
		this.loadJson = function (json, defValue) {
			if (json) options.data = json;
			if (defValue) options.defValue = defValue;
			$box.loadJson(json, defValue);
			$this.each(function () { $this.init(this); });
			$(this).prev().find(".checkcomboboxlist2").addClass("checkcomboboxlist2active");
		};
		this.loadAjax = function (url, defValue) {
			if (url) options.url = url;
			if (defValue) options.defValue = defValue;
			$box.loadAjax(url, defValue);
			$(this).prev().find(".checkcomboboxlist2").addClass("checkcomboboxlist2active");
		};
		this.asyncLoadJson = function (json, defValue) {
			if (json) options.data = json;
			if (defValue) options.defValue = defValue;
			$box.asyncLoadJson(json, defValue);
			$this.each(function () { $this.init(this); });
		};
		this.asyncLoadAjax = function (url, defValue) {
			if (url) options.url = url;
			if (defValue) options.defValue = defValue;
			$box.asyncLoadAjax(url, defValue);
		};
		if (options.url.length > 0) $this.loadAjax(); else $this.loadJson();
		return this;
	}
	$.fn.checkBoxTree = function (options) {
		/// <summary>
		///	 多選樹控件
		/// </summary>
		/// <param name="options" type="object">
		///	 參數列表：
		///	 &#10;	width				  寬
		///	 &#10;	height				 高
		///	 &#10;	url					接口URL
		///	 &#10;	data				   json数据
		/// </param>
		var $this = this, defaults = { width: 0, height: 0, url: '', data: [], checkbox: true, allTitle: "所有数据" };
		options = $.extend(defaults, options);
		var templates = '<div class="checkboxtree checkboxtree{0}"><div class="checkboxtree2">\
				<ul class="ztree ztree-container" id="{1}Tree"></ul>\
				<div class="checkboxtree-bottom"></div>\
			</div></div>\
			<div class="checkboxtree checkboxtree{0} checkboxtree-status {2}"><div class="checkboxtree2"></div><div class="checkboxtree-bottom"></div></div>';
		this.init = function (me) {
			me = $(me);
			if (me.prev().hasClass("checkboxtree")) me.prev().remove();
			if (me.prev().hasClass("checkboxtree")) me.prev().remove();
			if (me.prev().hasClass("checkboxtree")) me.prev().remove();
			var mew = options.width > 0 ? options.width : me.width(),
				meh = options.height > 0 ? options.height : me.height(),
				html = templates.format(mew, me.attr("id"), options.checkbox==null ? 'hide' : '');
			me.before(html);
			html = '';
			$(options.data).each(function () { html += this.checked ? (this.id + ";") : "" }); //html += this.checked && this.pId != '' ? (this.id + ";") : ""
			me.val(html);
			var meprev = me.prev().prev(), meprev2 = me.prev().find("> .checkboxtree2");
			meprev2.text(me.attr("default"));
			var setting = {
				view: { dblClickExpand: true },
				data: { simpleData: { enable: true} },
				callback: {
					beforeClick: function (treeId, treeNode) {
						if (treeNode.isParent) $.fn.zTree.getZTreeObj(me.attr("id") + "Tree").expandNode(treeNode); else $.fn.zTree.getZTreeObj(me.attr("id") + "Tree").checkNode(treeNode, null, false, true);
						return false;
					},
					onCheck: function (e, treeId, treeNode) {
						var zTree = $.fn.zTree.getZTreeObj(me.attr("id") + "Tree"),
							nodes = zTree.getCheckedNodes(true), v = "";
						for (var i = 0, l = nodes.length; i < l; i++) {
							if (nodes[i].isParent) continue;
							v += nodes[i].id + ";";
						}
						me.val(v);
						v = '';
						for (var i = 0, l = nodes.length; i < l; i++) {
							if (nodes[i].isParent) continue;
							v += nodes[i].name + ";";
						}
						me.attr("nameValue", v);
						meprev2.text(v);
					}
				}
			};
			if (options.checkbox == true) setting.check = { enable: true };
			if (options.checkbox == false) setting.check = { enable: true, chkStyle: "radio", radioType: "all" };
			if (options.checkbox == true) options.data.push({ "id": "", "pId": "", "name": options.allTitle, "open": true, "checked": true });
			$.fn.zTree.init(meprev.find(".ztree-container"), setting, options.data);
		}
		this.loadJson = function (json) {
			if (json) options.data = json;
			$this.each(function () { $this.init(this); });
		};
		this.loadAjax = function (url) {
			if (url) options.url = url;
			$this.each(function () {
				var obj = this;
				$.ajax({ type: "get", url: options.url, success: function (json) { options.data = json; $this.init(obj); } });
			});
		};
		if (options.url.length > 0) $this.loadAjax(); else $this.loadJson();
		return this;
	}
	$.fn.checkComboBoxTree = function (options) {
		/// <summary>
		///	 多選樹控件
		/// </summary>
		/// <param name="options" type="object">
		///	 參數列表：
		///	 &#10;	width				  寬
		///	 &#10;	height				 高
		///	 &#10;	url					接口URL
		///	 &#10;	data				   json数据
		/// </param>
		var $this = this, defaults = { width: 0, height: 0, url: '', data: [], checkbox: null, title: '', allTitle: "所有数据" };
		options = $.extend(defaults, options);
		var templates = '<div class="checkboxtree checkboxtree{0} checkcomboboxtree{0} checkboxtree-status2"><div class="checkboxtree2"></div><div class="checkboxtree-bottom"></div></div>\
			<div class="checkboxtree checkboxtree{0} checkcomboboxtreebox{0} hide"><div class="checkboxtree2">\
				<ul class="ztree ztree-container" id="{1}Tree"></ul>\
				<div class="checkboxtree-bottom"></div>\
			</div></div>';
		this.init = function (me) {
			me = $(me).hide();
			if (me.prev().hasClass("checkboxtree")) me.prev().remove();
			if (me.prev().hasClass("checkboxtree")) me.prev().remove();
			if (me.prev().hasClass("checkboxtree")) me.prev().remove();
			var mew = options.width > 0 ? options.width : me.outerWidth(),
				meh = options.height > 0 ? options.height : me.height(),
				html = templates.format(mew, me.attr("id"));
			me.before(html);
			html = '';
			$(options.data).each(function () { html += this.checked && this.pId != '' ? (this.id + ";") : ""; options.title += this.checked && this.pId != '' ? (this.name + ";") : ""; });
			me.val(html);
			var meprev = me.prev(), meprev2 = meprev.prev().find("> .checkboxtree2"), state = meprev.css("display");
			meprev2.text(options.title == '' ? "-请选择區域-" : options.title).click(function () {
				state = meprev.css("display");
				meprev.hide();
				if (state != 'none') meprev.show();
				if (state == 'none') {
					var offset = $(this).offset(), thish = $(this).outerHeight() + 4;
					meprev.css({ "left": offset.left, "top": offset.top + thish }).show();
				} else meprev.hide();
			});
			var setting = {
				view: { dblClickExpand: true },
				data: { simpleData: { enable: true} },
				callback: {
					beforeClick: function (treeId, treeNode) {
						if (treeNode.isParent) $.fn.zTree.getZTreeObj(me.attr("id") + "Tree").expandNode(treeNode); else {
							if (options.checkbox == null) {
								var v = treeNode.id, n = treeNode.name;
								me.val(v);
								me.attr("nameValue", n);
								meprev2.text(n);
								meprev.hide();
							} else $.fn.zTree.getZTreeObj(me.attr("id") + "Tree").checkNode(treeNode, null, false, true);
						}
						return false;
					},
					onCheck: function (e, treeId, treeNode) {
						var zTree = $.fn.zTree.getZTreeObj(me.attr("id") + "Tree"),
							nodes = zTree.getCheckedNodes(true), v = "";
						for (var i = 0, l = nodes.length; i < l; i++) {
							if (nodes[i].isParent) continue;
							v += nodes[i].id + ";";
						}
						me.val(v);
						v = '';
						for (var i = 0, l = nodes.length; i < l; i++) {
							if (nodes[i].isParent) continue;
							v += nodes[i].name + ";";
						}
						me.attr("nameValue", v);
						meprev2.text(v);
						meprev.hide();
					}
				}
			};
			if (options.checkbox == true) setting.check = { enable: true };
			if (options.checkbox == false) setting.check = { enable: true, chkStyle: "radio", radioType: "all" };
			if (options.checkbox == true) options.data.push({ "id": "", "pId": "", "name": options.allTitle, "open": true, "checked": true });
			$.fn.zTree.init(meprev.find(".ztree-container"), setting, options.data);
		}
		this.loadJson = function (json) {
			if (json) options.data = json;
			$this.each(function () { $this.init(this); });
		};
		this.loadAjax = function (url) {
			if (url) options.url = url;
			$this.each(function () {
				var obj = this;
				$.ajax({ type: "get", url: options.url, success: function (json) { options.data = json; $this.init(obj); } });
			});
		};
		if (options.url.length > 0) $this.loadAjax(); else $this.loadJson();
		return this;
	}
	$.fn.spager = function (options) {
		var me = this, defaults = { page: 1, pageSize: 10, total: 0, prevText: "&nbsp;", nextText: "下一頁&nbsp;", linkText: "{0}", className: "", call: null, url: "", showOnePage: true };
		options = $.extend(defaults, options);
		var a_templates = '<a href="{0}" value="{2}" class="{3}">{1}</a>&nbsp;', span_templates = '<span>{0}</span>&nbsp;',
			pages = parseInt(options.total / options.pageSize + (options.total % options.pageSize == 0 ? 0 : 1)),
			pager = function (page, text, className) { return a_templates.replace("{0}", options.url ? options.url.replace("{0}", page) : "#").replace("{1}", text).replace("{2}", page).replace("{3}", className); },
			active = function (page) { return span_templates.replace("{0}", page); };
		var parse = function () {
			if (options.total == 0) return "";
			if (pages <= 1) return options.showOnePage ? active(1) : "";
			if (pages < options.page) options.page = pages;
			var html = '', start = 2, end = pages < 9 ? pages : 9;
			if (options.page <= 1) options.page = 1; else html += pager(options.page - 1, options.prevText, 'prev') + pager(1, options.linkText.replace("{0}", 1));
			for (var i = start; i < options.page; i++) html += pager(i, options.linkText.replace("{0}", i));
			html += active(options.page);
			for (var i = options.page + 1; i <= end; i++) html += pager(i, options.linkText.replace("{0}", i));
			if (options.page < pages) html += pager(options.page + 1, options.nextText, 'next');
			return html;
		};
		var init = function (_me, html) {
			_me = $(_me);
			_me.addClass(options.className).addClass(options.align).remove("> *").html(html);
			if (!options.url) {
				_me.find(" > a").unbind().click(function () {
					var id = parseInt($(this).attr("value")) || 0;
					me.go(id);
				});
				if (options.call) options.call(options.page, me);
			}
		};
		this.go = function (page) {
			options.page = page;
			var html = parse();
			me.each(function () { init(this, html); });
		};
		this.go(options.page);
		return this;
	}
	$.fn.pager = function (options) {
		/// <summary>
		///	 分頁控件
		/// </summary>
		/// <param name="options" type="object">
		///	 參數列表：
		///	 &#10;	page					 頁碼
		///	 &#10;	pageSize				 每頁條數
		///	 &#10;	total					總記錄數
		///	 &#10;	prevText				 上一頁
		///	 &#10;	nextText				 下一頁
		///	 &#10;	linkText				 第N頁
		///	 &#10;	totalText				共N條記錄
		///	 &#10;	className				附加class
		///	 &#10;	call					 回調
		///	 &#10;	url					  接口URL
		///	 &#10;	align					對齊方式 left/right/center
		///	 &#10;	showGo				   是否顯示輸入頁碼條轉
		///	 &#10;	showOnePage			  是否顯示只有一頁的頁碼
		/// </param>
		var me = this, defaults = { page: 1, pageSize: 10, total: 0, prevText: "上一頁", nextText: "下一頁", linkText: "{0}", totalText: "{0}條記錄", className: "pager", call: null, url: null, align: "right", showGo: false, showOnePage: true };
		options = $.extend(defaults, options);
		var a_templates = '<a href="{0}" value="{2}">{1}</a>&nbsp;', span_templates = '<span>{0}</span>&nbsp;', em_templates = '<strong{1}>{0}</strong>&nbsp;',
			input_templage = '<input type="text" maxlength="9" size="2" value="{0}" onfocus="this.value=this.defaultValue;this.select();" title="可以輸入頁碼按回車鍵自動跳轉" class="txt" name="gopage">',
			pages = parseInt(options.total / options.pageSize + (options.total % options.pageSize == 0 ? 0 : 1)),
			pager = function (page, text) { return a_templates.format(options.url ? options.url.format(page) : "#", text, page); },
			active = function (page) { return span_templates.format(page); },
			em = function (text, style) { return em_templates.format(text, style || ""); };
		var parse = function () {
			if (options.total == 0) return "";
			if (pages <= 1) return options.showOnePage ? active(1) : "";
			if (pages < options.page) options.page = pages;
			var html = '', start = 2, end = pages < 9 ? pages : 9;
			if (options.page <= 1) options.page = 1; else html += pager(options.page - 1, options.prevText) + pager(1, options.linkText.format(1));
			if (options.page >= 7) { html += em("..."); start = options.page - 4; end = pages < options.page + 4 ? pages : options.page + 4; }
			for (var i = start; i < options.page; i++) html += pager(i, options.linkText.format(i));
			html += active(options.page);
			for (var i = options.page + 1; i <= end; i++) html += pager(i, options.linkText.format(i));
			if (end < pages) html += em("...") + pager(pages, options.linkText.format(pages));
			if (options.showGo) html += em(options.totalText.format(options.total), ' style="font-weight:normal;"') + input_templage.format(options.page);
			if (options.page < pages) html += pager(options.page + 1, options.nextText);
			return html;
		};
		var init = function (_me, html) {
			_me = $(_me);
			_me.addClass(options.className).addClass(options.align).remove("> *").html(html);
			if (!options.url) {
				_me.find(" > a").unbind().click(function () {
					var id = parseInt($(this).attr("value")) || 0;
					me.go(id);
				});
				if (options.call) options.call(options.page, me);
			}
			_me.find(" > input").unbind().keydown(function (e) {
				e = window.event || e;
				var code = e.keyCode || e.which;
				if (code == 13 && !options.url) me.go(parseInt(this.value) || 1);
				if (code == 13 && options.url) window.location = options.url.format(parseInt(this.value) > 0 ? parseInt(this.value) : 1);
				return (code >= 48 && code <= 57) || (code >= 96 && code <= 105) || code == 8;
			});
		};
		this.go = function (page) {
			options.page = page;
			var html = parse();
			me.each(function () { init(this, html); });
		};
		this.go(options.page);
		return this;
	}
	$.fn.fixTH = function (rows) {
		//table固定前Ｎ行
		rows = rows || 1;
		var me = this, $this = $(this);
		if ($this.length == 0) return;
		this.H = null; this.doc = null; this.W = null; this.docH = null; this.L = null; this.top = null; this.fix = null;
		this.cloneTable = $this.clone(true).find("tr:gt(" + (rows - 1) + ")").remove().end().hide().addClass("gridview-top");
		$this.before(me.cloneTable);
		var init = function(){
			me.H = $this.height();
			me.W = $this.width() + 2;
			me.doc = $(document);
			me.docH = me.doc.height();
			me.top = me.top || $this.offset().top;
			me.L = me.L || $this.offset().left;
			for(var i=0;i<rows;i++) {
				var k = "tr:eq({0})".format(i);
				var k2 = "th:eq({0})";
				me.cloneTable.find(k).each(function(){
					$(this).find('>th').each(function(index){
						$(this).width($this.find(k).find(k2.format(index)).width());
					});
				})
			}
		}

		$(document).scroll(function () {
			var top = me.doc.scrollTop();
			if (top > me.top) {
				if (!me.fix) { init(); me.fix = true; me.cloneTable.css({ "position": "fixed", "top": "0px", "left": me.L, "width": me.W }).show(); }
				else me.cloneTable.css({ "left": me.L - me.doc.scrollLeft() });
			} else {
				if (me.fix) { me.fix = false; me.cloneTable.hide(); }
			}
		});
		$(window).resize(function () {
			me.fix = false;
			init();
		}).focus(function () {
			$(document).scroll();
		}).resize();
		return me;
	}
	$.fn.btnSearch = function (form) {
		//搜索表單控件按回車鍵自動產生搜索按鈕單擊事件
		var me = this, obj = {}, url = '';
		me.click(function () {
			form.find("input:text,input:password,input:hidden,textarea,:radio,:checkbox,select").each(function () {
				var name = this.name, id = this.id, type = this.type;
				if (!this.disabled) {
					if (type == 'radio' && this.checked) {
						if (name) obj[name] = (obj[name] || "") + this.value + ','; else if (id) obj[id] = this.checked;
					} else if (type == 'checkbox' && this.checked) {
						if (name) obj[name] = (obj[name] || "") + this.value + ','; else if (id) obj[id] = this.checked;
					} else if (type != 'radio' && type != 'checkbox') {
						if (name) obj[name] = (obj[name] || "") + ($(this).attr("default") == this.value ? "" : this.value) + ',';
						else if (id) obj[id] = $(this).attr("default") == this.value ? "" : this.value;
					}
				}
			});
			for (key in obj) if (obj[key] && obj[key].toString().substr(obj[key].toString().length - 1) == ',') obj[key] = obj[key].toString().substr(0, obj[key].toString().length - 1);
			for (key in obj) url += key + "=" + obj[key] + "&";
			url = url == '' ? "" : '?' + url.substr(0, url.length - 1)
			location.href = url;
		});
		return me;
	}
	$.fn.treeTable = function (lineClick) {
		//簡單table樹
		var me = this.last();
		me.find(lineClick ? ".tree_table_node" : ".tree_table_node .item").click(function () {
			var isopen = false, _this = lineClick ? $(this) : $(this).parent();
			if (_this.hasClass("tree_table_close")) { _this.removeClass("tree_table_close"); isopen = true; } else _this.addClass("tree_table_close");
			var begin = _this.index("tr"), next = _this.next();
			if (!next.hasClass("tree_table_item")) return;
			while(true) {
				if (!next.hasClass("tree_table_item")) break;
				if (isopen) next.removeClass("hide"); else next.addClass("hide");
				next = next.next();
			}
		});
		me.find("tr").each(function(){
			var _this = $(this), _next = _this.next();
			if (_this.hasClass("tree_table_node") && (_next.hasClass("tree_table_node") || _next.length==0)) _this.find("td:first").removeClass("item");
		});
		return me;
	}
	$.fn.imageList = function (options) {
		var $this = this, defaults = { width:0, height:0, url: '', data: [], path: "", savePath: false, imgwidth: "auto", imgheight: "auto", checkbox: false };
		options = $.extend(defaults, options);
		var templates = '<div class="imagelist imagelist{0}">\
							<div class="imagelist-container">{1}</div>\
							<div class="imagelist-opterate"></div>\
						</div>',
			templates_item = '<img src="{0}" width="{1}" height="{2}" class="{3}" />';

		this.event = function (me) {
			var box = me.prev().find(".imagelist-container");
			box.find("img").click(function(){
				var _this = $(this);
				var val = _this.attr("src");
				if (!options.savePath) val = val.replace(options.path, "");

				if (options.checkbox == false) {
					box.find("img").removeClass("imagelist-item-active");
					_this.addClass("imagelist-item-active");
					me.val(val);
				} else {
					if (_this.hasClass("imagelist-item-active")) _this.removeClass("imagelist-item-active"); else _this.addClass("imagelist-item-active");
					var vallist = '';
					box.find("img.imagelist-item-active").each(function(){
						var val = $(this).attr("src");
						if (!options.savePath) val = val.replace(options.path, "");
						vallist += val + ';';
					});
					me.val(vallist);
				}
			});
		};
		this.loadJson = function (json) {
			if (json) options.data = json;
			$this.each(function () {
				var me = $(this);
				if (me.prev().hasClass("imagelist")) me.prev().remove();
				if (me.prev().prev().hasClass("imagelist")) me.prev().prev().remove();
				var mew = options.width > 0 ? options.width : me.width(),
					meh = options.height > 0 ? options.height : me.height(),
					html = templates.format(mew, ''), defValue = me.val().split(';');
				me.before(html);
				var mepbox = me.prev().find(".imagelist-container");
				me.prev().height(meh);
				mepbox.height(meh - 5);
				$(options.data).each(function () { mepbox.append(templates_item.format(options.path + this, options.imgwidth, options.imgheight, defValue.indexOf(this) == -1 ? "" : "imagelist-item-active")); });
				$this.event(me);
			});
		};

		if (options.url.length > 0) $this.loadAjax(); else $this.loadJson();
		return this;
	};
	$.fn.upload = function (opt, callback) {
		var $this = this; def = { width: 100, height: 100, length: 1, url: "", path: "", resize: 0, rewidth: 0, reheight: 0 };
		opt = $.extend(def, opt);

		var temp_box = '<table class="nup-img"><tr></tr></table>';
		var temp_item = '<td id="nup{0}" class="nup-img-item"><i class="nup-img-del"></i><img class="nup-img-img" src="{1}" /><input type="file" accept="image/*" class="nup-img-file" name="filedata" tabindex="-1" /></td>';
		this.upload = function(me, item, img, file, cb) {
			var formData = new FormData();
			formData.append('filedata', file);
			$.ajax({
				type: 'post', url: opt.url, data: formData,
				contentType: false, processData: false, cache: false,
				success: function(d) {
					if (d.ret == 0) {
						console.log(img, d.url);
						var text = me.text().trim2(';');
						if (!img) text = text + ';' + d.url;
						else text = text.replace(img, d.url);
						me.text(text.trim2(';'));
					}
					if (cb) cb(d);
				},
				error: function () { if (cb) cb({ ret: 2, msg: '上传失败！' }); }
			});
		};
		this.file2img = function(file, cb) {
			var reader = new FileReader();
			reader.onload = function(e) {
				var base64Img = e.target.result;
				var img = new Image();
				img.src = base64Img;
				img.onload = function() { if (cb) cb(img, img.width, img.height); }
			};
			reader.readAsDataURL(file);
		};
		this.resize = function(img, w, h) {
			var info = { w: w, h: h };
			console.log(info);
			var percent = parseFloat(info.w/info.h);
			var maxwidth = { w: opt.rewidth, h: parseInt(opt.rewidth/percent) };
			var maxheight = { w: parseInt(opt.reheight*percent), h: opt.reheight };
			if (maxwidth.h <= opt.reheight) return maxwidth;
			if (maxheight.w <= opt.rewidth) return maxheight;
			return { w: opt.rewidth, h: opt.reheight };
		};
		this.reimg64 = function(img, nw, nh, ow, oh, cb) {
			var canvas = document.createElement("canvas");
			canvas.width = nw; canvas.height = nh;
			var ctx = canvas.getContext('2d');
			ctx.drawImage(img, 0, 0, ow, oh, 0, 0, nw, nh);
			var base64 = canvas.toDataURL("image/jpeg", 0.8);
			if (cb) cb(base64, canvas);
		};
		this.toblob = function (dataurl) {
			var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
				bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
			while (n--) u8arr[n] = bstr.charCodeAt(n);
			return new Blob([u8arr], { type: mime });
		}
		this.event = function (me, item, img) {
			var file = item.find(".nup-img-file");
			var del = item.find('.nup-img-del');
			var upload = function(file) {
				$this.upload(me, item, img, file, function(d) {
					if (callback) callback(item, d);
					if (d.ret == 0 && !img) {
						img = d.url;
						del.show();
					}
				});
			}
			file.change(function() {
				var file = this.files[0];
				var size = file.size;
				console.log(size, opt.resize * 1024, file);
				if (opt.rewidth > 0 && opt.reheight > 0) { //启动压缩
					if (opt.resize == 0 || (opt.resize > 0 && size > opt.resize * 1024)) {
						$this.file2img(file, function(img, w, h) {
							var ns = $this.resize(img, w, h);
							console.log(ns.w, ns.h, w, h);
							$this.reimg64(img, ns.w, ns.h, w, h, function(base64, canvas) {
								var blob = $this.toblob(base64);
								blob.name = file.name;
								console.log(blob);
								upload(blob);
							});
						});
					} else upload(file);
				} else upload(file);
			});
			del.click(function() {
				del.hide();
				item.find('.nup-img-img').attr('src', '');
				var text = me.text().trim2(';');
				text = text.replace(img + ';', '');
				text = text.replace(img, '');
				me.text(text.trim2(';'));
				img = '';
			});
			if (img) del.show();
		};
		this.list = function (v) {
			var l = [], vs = v.split(';');
			for (var i = 0; i < vs.length; i++) if (vs[i].trim().length > 0) l.push(vs[i].trim());
			return l;
		};
		this.init = function () {
			$this.each(function () {
				var me = $(this), list = $this.list(me.text());
				me.before(temp_box);
				var box = me.prev().find('tr');
				for (var i = 0; i < opt.length; i++) {
					var img = list.length > i ? list[i] : '';
					var id = Math.random().toString().replace('0.', '');
					box.append(temp_item.format(id, img ? (opt.path + img) : ''));
					var item = box.find('#nup' + id);
					item.css({width: opt.width, height: opt.height});
					$this.event(me, item, img);
					if (opt.init) opt.init(item, i, me);
				}
			});
		};
		this.size = function(w, h) {
			var me = $(this);
			var items = me.prev().find('.nup-img-item');
			for (var i = 0; i < opt.length; i++) {
				var item = $(items[i]);
				item.css({width: w, height: h});
				if (opt.init) opt.init(item, i, me);
			}
		}
		$this.init();
		return this;
	};
} (jQuery));
