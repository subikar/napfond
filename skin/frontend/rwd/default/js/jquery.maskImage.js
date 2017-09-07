// JavaScript Document
(function( $ ){
	var methods = {
		init : function( options ) {
			if (!options.src) {
				return alert('no src provided');
			}
			return this.each(function(){
				var $this = $(this);
				var src1loaded=0, src2loaded=0;
				var src1=$this.attr('src'), src2=options.src;
				if (/MSIE/.test(navigator.userAgent)) { // IE doesn't work with the Canvas method...
					return $this.attr('src', 'jquery.maskImage.php?src='+src1+'&mask='+src2);
				}
				var img1=$('<img src="'+src1+'" style="position:absolute;left:-6000px"/>')
					.load(function(){
						src1loaded=1;
						if (src2loaded) {
							runMask();
						}
					});
				var img2=$('<img src="'+src2+'" style="position:absolute;left:-6000px"/>')
					.load(function(){
						src2loaded=1;
						if (src1loaded) {
							runMask();
						}
					});
				function runMask() {
					var bufferCanvas=document.createElement('canvas');
					var buffer=bufferCanvas.getContext('2d');
					var height=img1[0].height, width=img1[0].width;
					bufferCanvas.width=width;
					bufferCanvas.height=height*2;
					buffer.drawImage(img1[0], 0, 0);
					buffer.drawImage(img2[0], 0, height);
					var imageData=buffer.getImageData(0, 0, width, height);
					var alphaData=buffer.getImageData(0, height, width, height).data;
					for (var i=3, l=imageData.data.length; i<l; i+=4) {
						imageData.data[i]=alphaData[i];
					}
					var imageCanvas=document.createElement('canvas');
					var image=imageCanvas.getContext('2d');
					imageCanvas.width=width;
					imageCanvas.height=height;
					image.clearRect(0, 0, width, height);
					image.putImageData(imageData, 0, 0);
					$this.attr('src', imageCanvas.toDataURL());
					img1.remove();
					img2.remove();
				}
			});
		},
		destroy : function( ) {
			return this.each(function(){
				var $this = $(this);
				return alert('todo: destroy!');
			})
		}
	};
	$.fn.maskImage = function( method ) {
		if ( methods[method] ) {
			return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
		}
		else if ( typeof method === 'object' || ! method ) {
			return methods.init.apply( this, arguments );
		}
		else {
			$.error( 'Method ' +  method + ' does not exist on jQuery.maskImage' );
		}
	};
})( jQuery );
