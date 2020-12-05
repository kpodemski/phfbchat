{if $phfbchat_init}
<div id="fb-root"></div>
{/if}
<style>fscript {ldelim}display: none;{rdelim}</style>
<script data-keepinline="true">
var _thirdPartyScriptsLoaded = false;
var _head = document.getElementsByTagName('head')[0] || document.documentElement;

function _handleThirdPartyScripts() {ldelim}
    var fscripts = document.querySelectorAll('fscript');
    [].forEach.call(fscripts, function(fscript) {
        var script = document.createElement('script');
        script.type = 'text/javascript';

        if (fscript.hasAttributes()) {ldelim}
            for (var attributeKey in fscript.attributes) {ldelim}
                if (fscript.attributes.hasOwnProperty(attributeKey)) {ldelim}
                    script[ fscript.attributes[ attributeKey ].name ] = fscript.attributes[ attributeKey ].value || true;
                {rdelim}
            {rdelim}
        {rdelim} else {ldelim}
            script.appendChild( document.createTextNode( fscript.innerHTML ) );
        {rdelim}

        _head.insertBefore( script, _head.firstChild );
    {rdelim});
{rdelim}

function loadThirdPartyAssets() {ldelim}
    if (_thirdPartyScriptsLoaded) {
        return;
    }

    _thirdPartyScriptsLoaded = true;

    setTimeout(function() {ldelim}
        if ('requestIdleCallback' in window) {ldelim}
            requestIdleCallback(_handleThirdPartyScripts, {ldelim} timeout: 1000 {rdelim});
        {rdelim} else {ldelim}
            _handleThirdPartyScripts();
        {rdelim}
    {rdelim}, 2000);
{rdelim}

window.onload = function() {
    loadThirdPartyAssets();
};

window.fbAsyncInit = window.fbAsyncInit || function() {
    FB.init({
      autoLogAppEvents : true,
      xfbml            : true,
      version          : 'v8.0'
    });
};
</script>
<fscript id="facebook-jssdk" src="//connect.facebook.net/{$phfbchat_locale}/sdk/xfbml.customerchat.js"></fscript>
<div class="fb-customerchat"
page_id="{$phfbchat_page_id}"
{if $phfbchat_logged_in}
logged_in_greeting="{$phfbchat_logged_in}"
{/if}
{if $phfbchat_logged_out}
logged_out_greeting="{$phfbchat_logged_out}"
{/if}
{if $phfbchat_theme_color}
theme_color="{$phfbchat_theme_color}"
{/if}
{if $phfbchat_greeting_dialog_delay}
greeting_dialog_delay="{$phfbchat_greeting_dialog_delay}"
{/if}
></div>
