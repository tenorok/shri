/*
Copyright (c) 2010 Thomas Peri
http://www.tumuski.com/
MIT License
*/

/*global jQuery */
/*jslint white: true, browser: true, onevar: true, undef: true, 
nomen: true, eqeqeq: true, plusplus: true, bitwise: true, regexp: true,
newcap: true, immed: true */

/**
 * Asynchronous Hover jQuery Plugin
 * 
 * Like .hover(), except that the handlers get called 
 * asynchronously, optionally with delay.
 * 
 * Mouse Leave cancels a pending Mouse Enter, and vice versa.
 *
 * .hoverDelay(handlerIn(eventObject), delayIn, handlerOut(eventObject), delayOut)
 *    Returns the jQuery object on which .hoverDelay is called.
 *    handlerIn(eventObject)
 *       A function to execute when the mouse pointer enters the element.
 *    delayIn
 *       The number of milliseconds to wayt before invoking handlerIn.
 *    handlerOut(eventObject)
 *       A function to execute when the mouse pointer leaves the element.
 *    delayOut
 *       The number of milliseconds to wait before invoking handlerOut.
 * 
 * .hoverDelay(handlerInOut(eventObject), delayInOut)
 *    Returns the jQuery object on which .hoverDelay is called.
 *    handlerInOut(eventObject)
 *       A function to execute when the mouse pointer enters or leaves the element.
 *    delayInOut
 *       The number of milliseconds to wait before invoking handlerInOut.
 * 
 * version 2010-09-11
 */
(function ($) {
	$.fn.hoverDelay = function (handlerIn, delayIn, handlerOut, delayOut) {
		if (arguments.length === 2) {
			handlerOut = handlerIn;
			delayOut = delayIn;
		}
		return this.each(function () {
			var element, update, timeout, isHovering;
			
			element = this;
			isHovering = false;
			
			// General handler for enter and leave.
			update = function (hover, args) {
			
				// In case there's already a mouseenter or mouseleave waiting to fire,
				// clear that to make way for the newest one.
				clearTimeout(timeout);
				
				// If we get a mouseenter while already inside, there's nothing to do.
				// Likewise if we get a mouseleave while already outside.
				if (hover !== isHovering) {
				
					// Distinguish between enter and leave, and set the appropriate timeout.
					if (hover) {
						timeout = setTimeout(function () {
							isHovering = hover;
							handlerIn.apply(element, args);
						}, delayIn);
					} else {
						timeout = setTimeout(function () {
							isHovering = hover;
							handlerOut.apply(element, args);
						}, delayOut);
					}
				}
			};
	
			// The actual handlers just pass the buck to the general handler.
			$(this).hover(function () {
				update(true, arguments);
			}, function () {
				update(false, arguments);
			});
		});
	};
}(jQuery));
