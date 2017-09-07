/*  Prototype JavaScript framework, version 1.6.0.3
 *  (c) 2005-2008 Sam Stephenson
 *
 *  Prototype is freely distributable under the terms of an MIT-style license.
 *  For details, see the Prototype web site: http://www.prototypejs.org/
 *
 *--------------------------------------------------------------------------*/

if (Object.isUndefined(Event.Handler)) {
    Event.Handler = Class.create({
        /**
         *  new Event.Handler(element, eventName[, selector], callback)
         *  - element (Element): The element to listen on.
         *  - eventName (String): An event to listen for. Can be a standard browser
         *    event or a custom event.
         *  - selector (String): A CSS selector. If specified, will call `callback`
         *    _only_ when it can find an element that matches `selector` somewhere
         *    in the ancestor chain between the event's target element and the
         *    given `element`.
         *  - callback (Function): The event handler function. Should expect two
         *    arguments: the event object _and_ the element that received the
         *    event. (If `selector` was given, this element will be the one that
         *    satisfies the criteria described just above; if not, it will be the
         *    one specified in the `element` argument).
         *
         *  Instantiates an `Event.Handler`. **Will not** begin observing until
         *  [[Event.Handler#start]] is called.
         **/
        initialize: function(element, eventName, selector, callback) {
            this.element   = $(element);
            this.eventName = eventName;
            this.selector  = selector;
            this.callback  = callback;
            this.handler   = this.handleEvent.bind(this);
        },

        /**
         *  Event.Handler#start -> Event.Handler
         *
         *  Starts listening for events. Returns itself.
         **/
        start: function() {
            Event.observe(this.element, this.eventName, this.handler);
            return this;
        },

        /**
         *  Event.Handler#stop -> Event.Handler
         *
         *  Stops listening for events. Returns itself.
         **/
        stop: function() {
            Event.stopObserving(this.element, this.eventName, this.handler);
            return this;
        },

        handleEvent: function(event) {
            var element = Event.findElement(event, this.selector);
            if (element) this.callback.call(this.element, event, element);
        }
    });
}

if (!Object.isFunction(Event.on)) {
    function on(element, eventName, selector, callback) {
        element = $(element);
        if (Object.isFunction(selector) && Object.isUndefined(callback)) {
            callback = selector, selector = null;
        }

        return new Event.Handler(element, eventName, selector, callback).start();
    }

    Object.extend(Event, {
        on: on
    });
}
