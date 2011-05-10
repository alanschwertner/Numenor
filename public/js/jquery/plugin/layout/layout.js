
function initPaneScrollbar ( pane, $Pane ) {

    $Pane.find("div.scrolling-content:first")
    // re/init the pseudo-scrollbar
    .jScrollPane()
    // REMOVE the *fixed width & height* just set on jScrollPaneContainer
    .parent(".jScrollPaneContainer").css({
        width: '100%',
        height: '100%'
    });
};

var outerLayout;

$(document).ready( function() {
    // create the OUTER LAYOUT

    outerLayout = $("body").layout(Layout);

    initPaneScrollbar('west', outerLayout.panes.west);
    initPaneScrollbar('east', outerLayout.panes.east);
    initPaneScrollbar('center', outerLayout.panes.center);

    // BIND events to hard-coded buttons in the NORTH toolbar
    //    outerLayout.addToggleBtn( "#tbarToggleNorth", "north" );
    //    outerLayout.addOpenBtn( "#tbarOpenSouth", "south" );
    //    outerLayout.addCloseBtn( "#tbarCloseSouth", "south" );
    //    outerLayout.addPinBtn( "#tbarPinWest", "west" );
    //    outerLayout.addPinBtn( "#tbarPinEast", "east" );

    // save selector strings to vars so we don't have to repeat it
    // must prefix paneClass with "body > " to target ONLY the outerLayout panes
    var westSelector = "body > .ui-layout-west"; // outer-west pane
    var eastSelector = "body > .ui-layout-east"; // outer-east pane

    // CREATE SPANs for pin-buttons - using a generic class as identifiers
    $("<span></span>").addClass("pin-button").prependTo( westSelector );
    $("<span></span>").addClass("pin-button").prependTo( eastSelector );
    // BIND events to pin-buttons to make them functional
    outerLayout.addPinBtn( westSelector +" .pin-button", "west");
    outerLayout.addPinBtn( eastSelector +" .pin-button", "east" );

    // CREATE SPANs for close-buttons - using unique IDs as identifiers
    $("<span></span>").attr("id", "west-closer" ).prependTo( westSelector );
    $("<span></span>").attr("id", "east-closer").prependTo( eastSelector );
    // BIND layout events to close-buttons to make them functional
    outerLayout.addCloseBtn("#west-closer", "west");
    outerLayout.addCloseBtn("#east-closer", "east");

    outerLayout.hide('east');
    outerLayout.hide('west');
    
    
    

});

var codigoGerado;


var Layout = {
    name: "outerLayout",
    triggerEventsOnLoad: false,
    resizeWhileDragging: true,
    triggerEventsWhileDragging:	false,
    //useStateCookie: true,                       // NO FUNCTIONAL USE, but could be used by custom code to 'identify' a layout

    defaults: {
        size:"auto",
        minSize:50,
        paneClass:"pane",                       // default = 'ui-layout-pane'
        resizerClass:"resizer",                 // default = 'ui-layout-resizer'
        togglerClass:"toggler",                 // default = 'ui-layout-toggler'
        buttonClass:"button",                   // default = 'ui-layout-button'
        contentSelector:".content",             // inner div to auto-size so only it scrolls, not the entire pane!
        contentIgnoreSelector:"span",           // 'paneSelector' for content to 'ignore' when measuring room for content
        togglerLength_open:35,                  // WIDTH of toggler on north/south edges - HEIGHT on east/west edges
        togglerLength_closed:35,                // "100%" OR -1 = full height
        hideTogglerOnSlide:true,                // hide the toggler when pane is 'slid open'
        togglerTip_open:"Close This Pane",
        togglerTip_closed:"Open This Pane",
        resizerTip:"Resize This Pane",          // effect defaults - overridden on some panes
        fxName:"slide",                         // none, slide, drop, scale
        fxSpeed_open:750,
        fxSpeed_close:1500,
        fxSettings_open:{
            easing: "easeInQuint"
        },
        fxSettings_close:{
            easing: "easeOutQuint"
        }
    },
    north: {
        onresize: initPaneScrollbar,
        size:27,
        minSize:20,
        spacing_open:1,                         // cosmetic spacing
        togglerLength_open:0,                   // HIDE the toggler button
        togglerLength_closed:-1,                // "100%" OR -1 = full width of pane
        resizable:false,
        slidable:false,                         // override default effect
        fxName:"none"
    },
    south: {
        onresize: initPaneScrollbar,
        maxSize: 200,
        spacing_closed:0,                       // HIDE resizer & toggler when 'closed'
        slidable: false,                        // REFERENCE - cannot slide if spacing_closed = 0
        initClosed: true
    //	CALLBACK TESTING...
    },
    west: {
        onresize: initPaneScrollbar,
        minSize:220,
        spacing_closed:21,                      // wider space when closed
        togglerLength_closed:21,                // make toggler 'square' - 21x21
        togglerAlign_closed:"top",              // align to top of resizer
        togglerLength_open:0,                   // NONE - using custom togglers INSIDE west-pane
        togglerTip_open:"Close West Pane",
        togglerTip_closed:"Open West Pane",
        resizerTip_open:"Resize West Pane",
        slideTrigger_open:"click",              // default
        initClosed:true,                       // add 'bounce' option to default 'slide' effect
        fxName:"drop",
        fxSpeed:"normal",
        fxSettings:{
            easing: ""
        }
    },
    east: {
        onresize: initPaneScrollbar,
        size:350,
        spacing_closed:	21,                     // wider space when closed
        togglerLength_closed:21,                // make toggler 'square' - 21x21
        togglerAlign_closed:"top",              // align to top of resizer
        togglerLength_open:0,                   // NONE - using custom togglers INSIDE east-pane
        togglerTip_open:"Close East Pane",
        togglerTip_closed:"Open East Pane",
        resizerTip_open:"Resize East Pane",
        slideTrigger_open:"mouseover",
        initClosed:true,                       // override default effect, speed, and settings
        fxName:"drop",
        fxSpeed:"normal",
        fxSettings:{
            easing: ""
        } // nullify default easing
    },
    center: {
        onresize: initPaneScrollbar,
        minWidth:400,
        minHeight:200
    }
};


var LayoutCodigoGerado = {
    
    name: "codigoGerado",
    triggerEventsOnLoad: false,
    resizeWhileDragging: true,
    triggerEventsWhileDragging:	false,
    //useStateCookie: true,                       // NO FUNCTIONAL USE, but could be used by custom code to 'identify' a layout

    defaults: {
        size:"auto",
        minSize:50,
        paneClass:"pane",                       // default = 'ui-layout-pane'
        resizerClass:"resizer",                 // default = 'ui-layout-resizer'
        togglerClass:"toggler",                 // default = 'ui-layout-toggler'
        buttonClass:"button",                   // default = 'ui-layout-button'
        contentSelector:".content",             // inner div to auto-size so only it scrolls, not the entire pane!
        contentIgnoreSelector:"span",           // 'paneSelector' for content to 'ignore' when measuring room for content
        togglerLength_open:35,                  // WIDTH of toggler on north/south edges - HEIGHT on east/west edges
        togglerLength_closed:35,                // "100%" OR -1 = full height
        hideTogglerOnSlide:true,                // hide the toggler when pane is 'slid open'
        togglerTip_open:"Close This Pane",
        togglerTip_closed:"Open This Pane",
        resizerTip:"Resize This Pane",          // effect defaults - overridden on some panes
        fxName:"slide",                         // none, slide, drop, scale
        fxSpeed_open:750,
        fxSpeed_close:1500,
        fxSettings_open:{
            easing: "easeInQuint"
        },
        fxSettings_close:{
            easing: "easeOutQuint"
        }
    },
        
    west: {
        onresize: initPaneScrollbar,
        minSize:220,
        spacing_closed:21,                      // wider space when closed
        togglerLength_closed:21,                // make toggler 'square' - 21x21
        togglerAlign_closed:"top",              // align to top of resizer
        togglerLength_open:0,                   // NONE - using custom togglers INSIDE west-pane
        togglerTip_open:"Close West Pane",
        togglerTip_closed:"Open West Pane",
        resizerTip_open:"Resize West Pane",
        slideTrigger_open:"click",              // default
        initClosed:false,                       // add 'bounce' option to default 'slide' effect
        fxName:"drop",
        fxSpeed:"normal",
        fxSettings:{
            easing: ""
        }
    },
    center: {
        onresize: initPaneScrollbar,
        minWidth:400
    }
};