/**
 *     Translucent image slideshow gallery
 *     Copyright (C) 2012  www.gopiplus.com
 *     http://www.gopiplus.com/work/2010/07/18/translucent-image-slideshow-gallery/
 * 
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 * 
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 * 
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
 
jQuery.noConflict()

function TISG_translideshow(options){
	var $=jQuery
	this.setting={TISG_displaymode:{type:'auto', pause:2000, cycles:2, pauseonmouseover:true}, TISG_orientation:'h', TISG_persist:true, TISG_slideduration:500} //default settings
	jQuery.extend(this.setting, options) //merge default settings with options
	var curslide=(this.setting.TISG_persist)? TISG_translideshow.routines.getCookie("slider-"+this.setting.TISG_wrapperid) : 0
	this.curslide=(curslide==null || curslide>this.setting.TISG_imagearray.length-1)? 0 : parseInt(curslide) //make sure curslide index is within bounds
	this.curstep=0
	this.zIndex=1
	this.animation_isrunning=false //variable to indicate whether an image is currently being slided in
	this.posprop=(this.setting.TISG_orientation=="h")? "left" : "top"
	options=null
	var slideshow=this, setting=this.setting, preloadimages=[], slidesHTML=''
	for (var i=0; i<setting.TISG_imagearray.length; i++){ //preload images
		preloadimages[i]=new Image()
		preloadimages[i].src=setting.TISG_imagearray[i][0]
		slidesHTML+=TISG_translideshow.routines.getSlideHTML(setting.TISG_imagearray[i], setting.TISG_dimensions[0]+'px', setting.TISG_dimensions[1]+'px', this.posprop)+'\n'
	}
	jQuery(function($){ //on document.ready
		slideshow.init($, slidesHTML)
	})
	$(window).bind('unload', function(){ //on window onload
		if (slideshow.setting.TISG_persist) //remember last shown slide's index?
			TISG_translideshow.routines.setCookie("slider-"+setting.TISG_wrapperid, slideshow.curslide)
	})
}

TISG_translideshow.prototype={

	slide:function(nextslide, dir){ //possible values for dir: "left", "right", "top", or "down"
		if (this.curslide==nextslide)
			return
		var slider=this
		var nextslide_initialpos=this.setting.TISG_dimensions[(dir=="right"||dir=="left")? 0 : 1] * ((dir=="right"||dir=="down")? -1 : 1)
		var curslide_finalpos=-nextslide_initialpos
		var posprop=this.posprop
		if (this.animation_isrunning!=null)
			this.animation_isrunning=true //indicate animation is running
		//this.$imageslides.eq(dir=="left"||dir=="top"? nextslide : this.curslide).css("zIndex", ++this.zIndex) //increase zIndex of upcoming slide so it overlaps outgoing
		this.$imageslides.eq(nextslide).css(TISG_translideshow.routines.createobj(['visibility', 'visible'], ['zIndex', ++this.zIndex], ['opacity', 0.3], [posprop, nextslide_initialpos])) //show upcoming slide
			.animate(TISG_translideshow.routines.createobj([posprop, 0]), this.setting.TISG_slideduration, function(){
				jQuery(this).css('opacity', 1)
				slider.animation_isrunning=false
			})
		//this.$imageslides.eq(this.curslide).animate(TISG_translideshow.routines.createobj([posprop, curslide_finalpos]), this.setting.TISG_slideduration, function(){jQuery(this).css("visibility", "hidden")}) //hide outgoing slide
		this.curslide=nextslide
	},

	navigate:function(keyword){ //keyword: "back" or "forth", or "integer"
		clearTimeout(this.rotatetimer)
		var dir=(keyword=="back" || parseInt(keyword)<this.curslide)? (this.setting.TISG_orientation=="h"? "right" : "down") : (this.setting.TISG_orientation=="h"? "left" : "up")
		var targetslide=(keyword=="back")? this.curslide-1 : (keyword=="forth")? this.curslide+1 : parseInt(keyword)
		targetslide=(targetslide<0)? this.$imageslides.length-1 : (targetslide>this.$imageslides.length-1)? 0 : targetslide //wrap around
		if (this.animation_isrunning==false)
			this.slide(targetslide, dir)
	},

	rotate:function(){
		var slideshow=this
		if (this.ismouseover){ //pause slideshow onmouseover
			this.rotatetimer=setTimeout(function(){slideshow.rotate()}, this.setting.TISG_displaymode.pause)
			return
		}
		var nextslide=(this.curslide<this.$imageslides.length-1)? this.curslide+1 : 0
		this.slide(nextslide, this.posprop) //go to next slide, either to the left or upwards depending on setting.TISG_orientation setting
		if (this.setting.TISG_displaymode.cycles==0 || this.curstep<this.maxsteps-1){
			this.rotatetimer=setTimeout(function(){slideshow.rotate()}, this.setting.TISG_displaymode.pause)
			this.curstep++
		}
	},

	init:function($, slidesHTML){
		var slideshow=this, setting=this.setting
		this.$wrapperdiv=$('#'+setting.TISG_wrapperid).css({position:'relative', visibility:'visible', overflow:'hidden', width:setting.TISG_dimensions[0], height:setting.TISG_dimensions[1]}) //main DIV
		if (this.$wrapperdiv.length==0){ //if no wrapper DIV found
			alert("Error: DIV with ID \""+setting.TISG_wrapperid+"\" not found on page.")
			return
		}
		this.$wrapperdiv.html(slidesHTML)
		this.$imageslides=this.$wrapperdiv.find('div.slide')
		this.$imageslides.eq(this.curslide).css(TISG_translideshow.routines.createobj([this.posprop, 0])) //set current slide's CSS position (either "left" or "top") to 0
		if (this.setting.TISG_displaymode.type=="auto"){ //auto slide mode?
			this.setting.TISG_displaymode.pause+=this.setting.TISG_slideduration
			this.maxsteps=this.setting.TISG_displaymode.cycles * this.$imageslides.length
			if (this.setting.TISG_displaymode.pauseonmouseover){
				this.$wrapperdiv.mouseenter(function(){slideshow.ismouseover=true})
				this.$wrapperdiv.mouseleave(function(){slideshow.ismouseover=false})
			}
			this.rotatetimer=setTimeout(function(){slideshow.rotate()}, this.setting.TISG_displaymode.pause)
		}
	}

}

TISG_translideshow.routines={

	getSlideHTML:function(imgref, w, h, posprop){
		var posstr=posprop+":"+((posprop=="left")? w : h)
		var layerHTML=(imgref[1])? '<a href="'+imgref[1]+'" target="'+imgref[2]+'">' : '' //hyperlink slide?
		layerHTML+='<img src="'+imgref[0]+'" style="border-width:0;" />'
		layerHTML+=(imgref[1])? '</a>' : ''
		return '<div class="slide" style="position:absolute;'+posstr+';width:'+w+';height:'+h+';text-align:center;">'
			+'<div style="width:'+w+';height:'+h+';display:table-cell;vertical-align:middle;">'
			+layerHTML
			+'</div></div>' //return HTML for this layer
	},


	getCookie:function(Name){ 
		var re=new RegExp(Name+"=[^;]+", "i"); //construct RE to search for target name/value pair
		if (document.cookie.match(re)) //if cookie found
			return document.cookie.match(re)[0].split("=")[1] //return its value
		return null
	},

	setCookie:function(name, value){
		document.cookie = name+"=" + value + ";path=/"
	},

	createobj:function(){
		var obj={}
		for (var i=0; i<arguments.length; i++){
			obj[arguments[i][0]]=arguments[i][1]
		}
		return obj
	}
}