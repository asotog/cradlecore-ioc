YUI.add('yui-mobile-menu', function (Y) {
    Y.MobileMenu = Y.Base.create('yui-mobile-menu', Y.Widget, [], {
        destructor: function () {
            
        },

        renderUI: function () {
        },
        
        bindUI: function () {
            this.get('contentBox').one('.menu-link').on('click', this.toggleMenu, this);
        },
        
        toggleMenu: function() {
            this.get('contentBox').one('.menu-link').toggleClass('pressed');
            this.get('contentBox').one('.menu').toggleClass('visible');
        },
        syncUI: function () {
        }
    }, {
        ATTRS: {
        }
    });
}, '1.0', {
    requires: ['base-build', 'widget']
});