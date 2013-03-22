!function(exports, $, undefined)
{
    var Plugin = function()
    {
 
        /*-------- PLUGIN VARS ------------------------------------------------------------*/
 
        var priv = {}, // private API
 
            Plugin = {}, // public API
 
            // Plugin defaults
            defaults = {
                id : '',
                name : '',
                url : ''
            };
 
        /*-------- PRIVATE METHODS --------------------------------------------------------*/
 
        priv.options = {}; //private options
 
        priv.method1 = function()
        {
            console.log('Private method 1 called...');
            $('#videos').append('<div id="'+this.options.id+'" class="video-wrap"><h1>'+this.options.name+'</h1></div>');
            priv.method2(this.options);
        };
 
        priv.method2 = function()
        {
            console.log('Private method 2 called...');
            $('#'+priv.options.id).append('<p>'+this.options.url+'</p>'); // append title
            $('#'+priv.options.id).append('<iframe width="420" height="315" src="'+this.options.url+'" frameborder="0" allowfullscreen></iframe>'); //append video
        };
 
        /*-------- PUBLIC METHODS ----------------------------------------------------------*/
 
        Plugin.method1 = function()
        {
            console.log('Public method 1 called...');
            console.log(Plugin);
 
            //options called in public methods must access through the priv obj
            console.dir(priv.options);
        };
 
        Plugin.method2 = function()
        {
            console.log('Public method 2 called...');
            console.log(Plugin);
        };
 
        // Public initialization
        Plugin.init = function(options)
        {
            console.log('new plugin initialization...');
            $.extend(priv.options, defaults, options);
            priv.method1();
            return Plugin;
        }
 
        // Return the Public API (Plugin) we want
        // to expose
        console.log('new plugin object created...');
        return Plugin;
    }
 
    exports.Plugin = Plugin;
 
}(this, jQuery);